<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecurityHelper
{
    // Rate limiting constants
    public const LOGIN_MAX_ATTEMPTS = 5;
    public const LOGIN_DECAY_MINUTES = 15;
    public const REGISTER_MAX_ATTEMPTS = 3;
    public const REGISTER_DECAY_MINUTES = 60;
    public const PASSWORD_RESET_MAX_ATTEMPTS = 3;
    public const PASSWORD_RESET_DECAY_MINUTES = 60;

    // Password requirements
    public const PASSWORD_MIN_LENGTH = 8;
    public const PASSWORD_REQUIRE_UPPERCASE = true;
    public const PASSWORD_REQUIRE_LOWERCASE = true;
    public const PASSWORD_REQUIRE_NUMBER = true;
    public const PASSWORD_REQUIRE_SPECIAL = true;

    // Token expiration (in minutes)
    public const PASSWORD_RESET_TOKEN_EXPIRY = 60;
    public const EMAIL_VERIFICATION_TOKEN_EXPIRY = 1440; // 24 hours
    public const TWO_FACTOR_CODE_EXPIRY = 5;

    /**
     * Check if rate limit is exceeded for login attempts
     */
    public static function isRateLimited(string $email, string $ip): bool
    {
        $key = 'login:' . $email . ':' . $ip;
        return RateLimiter::tooManyAttempts($key, self::LOGIN_MAX_ATTEMPTS);
    }

    /**
     * Get rate limit remaining attempts
     */
    public static function getRateLimitRemaining(string $email, string $ip): int
    {
        $key = 'login:' . $email . ':' . $ip;
        return RateLimiter::remaining($key, self::LOGIN_MAX_ATTEMPTS);
    }

    /**
     * Hit rate limiter for failed login attempt
     */
    public static function hitRateLimiter(string $email, string $ip): void
    {
        $key = 'login:' . $email . ':' . $ip;
        RateLimiter::hit($key, self::LOGIN_DECAY_MINUTES * 60);
    }

    /**
     * Clear rate limit after successful login
     */
    public static function clearRateLimiter(string $email, string $ip): void
    {
        $key = 'login:' . $email . ':' . $ip;
        RateLimiter::clear($key);
    }

    /**
     * Get rate limit seconds until available
     */
    public static function getRateLimitSeconds(string $email, string $ip): int
    {
        $key = 'login:' . $email . ':' . $ip;
        return RateLimiter::availableIn($key);
    }

    /**
     * Validate password strength
     */
    public static function validatePassword(string $password): array
    {
        $errors = [];

        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            $errors[] = 'Password minimal harus ' . self::PASSWORD_MIN_LENGTH . ' karakter';
        }

        if (self::PASSWORD_REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf besar (A-Z)';
        }

        if (self::PASSWORD_REQUIRE_LOWERCASE && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf kecil (a-z)';
        }

        if (self::PASSWORD_REQUIRE_NUMBER && !preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password harus mengandung angka (0-9)';
        }

        if (self::PASSWORD_REQUIRE_SPECIAL && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = 'Password harus mengandung karakter khusus (!@#$%^&*...)';
        }

        return $errors;
    }

    /**
     * Check if password has been used before (password history)
     */
    public static function isPasswordUsedBefore(User $user, string $password): bool
    {
        $passwordHistory = \DB::table('password_history')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($passwordHistory as $oldPassword) {
            if (Hash::check($password, $oldPassword->password)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save password to history
     */
    public static function savePasswordToHistory(User $user, string $password): void
    {
        \DB::table('password_history')->insert([
            'user_id' => $user->id,
            'password' => Hash::make($password),
            'created_at' => Carbon::now(),
        ]);

        // Keep only last 10 passwords
        $historyIds = \DB::table('password_history')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->skip(10)
            ->take(10)
            ->pluck('id');

        if ($historyIds->isNotEmpty()) {
            \DB::table('password_history')
                ->whereIn('id', $historyIds)
                ->delete();
        }
    }

    /**
     * Generate secure random token
     */
    public static function generateToken(int $length = 64): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Generate numeric verification code
     */
    public static function generateNumericCode(int $length = 6): string
    {
        return str_pad((string) random_int(0, str_repeat('9', $length)), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Hash token for storage
     */
    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Generate QR code URL for 2FA
     */
    public static function generateTwoFactorQrCodeUrl(User $user): string
    {
        $secret = $user->two_factor_secret;
        $appName = config('app.name', 'Database INOPAK');

        return "otpauth://totp/{$appName}:{$user->email}?secret={$secret}&issuer={$appName}";
    }

    /**
     * Verify 2FA code
     */
    public static function verifyTwoFactorCode(User $user, string $code): bool
    {
        if (!$user->two_factor_secret || !$user->two_factor_enabled) {
            return false;
        }

        // Use PHP's built-in TOTP verification
        $secret = $user->two_factor_secret;

        // Get current time counter
        $time = floor(time() / 30);

        // Check current and adjacent time windows
        for ($i = -1; $i <= 1; $i++) {
            if (self::getTOTP($secret, $time + $i) === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate TOTP code (simplified implementation)
     */
    private static function getTOTP(string $secret, int $time): string
    {
        $secretKey = self::base32Decode($secret);
        $timeHex = str_pad(dechex($time), 16, '0', STR_PAD_LEFT);
        $timeBinary = hex2bin($timeHex);

        $hash = hash_hmac('sha1', $timeBinary, $secretKey, true);

        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
        $binary = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        );

        return str_pad((string) ($binary % 1000000), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Base32 decode for TOTP
     */
    private static function base32Decode(string $encoded): string
    {
        $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $encoded = strtoupper($encoded);
        $encoded = str_replace('=', '', $encoded);

        $binaryString = '';
        foreach (str_split($encoded) as $char) {
            $val = strpos($base32Chars, $char);
            if ($val === false) continue;
            $binaryString .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }

        return binaryString($binaryString);
    }

    /**
     * Generate recovery codes for 2FA
     */
    public static function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = self::generateToken(4) . '-' . self::generateToken(4);
        }
        return $codes;
    }

    /**
     * Check if recovery code is valid
     */
    public static function verifyRecoveryCode(User $user, string $code): bool
    {
        if (!$user->two_factor_recovery_codes) {
            return false;
        }

        $codes = json_decode($user->two_factor_recovery_codes, true);
        $code = strtoupper($code);

        foreach ($codes as $index => $storedCode) {
            if (hash_equals(strtoupper($storedCode), $code)) {
                // Remove used code
                unset($codes[$index]);
                $user->two_factor_recovery_codes = json_encode(array_values($codes));
                $user->save();
                return true;
            }
        }

        return false;
    }

    /**
     * Detect device information from user agent
     */
    public static function detectDevice(string $userAgent): array
    {
        $device = [
            'device_type' => 'desktop',
            'browser' => 'unknown',
            'os' => 'unknown',
        ];

        // Device type detection
        if (preg_match('/mobile|android|iphone|ipod|blackberry|windows phone/i', $userAgent)) {
            $device['device_type'] = 'mobile';
        } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            $device['device_type'] = 'tablet';
        }

        // Browser detection
        if (preg_match('/chrome\/(\d+)/i', $userAgent, $matches)) {
            $device['browser'] = 'Chrome ' . $matches[1];
        } elseif (preg_match('/firefox\/(\d+)/i', $userAgent, $matches)) {
            $device['browser'] = 'Firefox ' . $matches[1];
        } elseif (preg_match('/safari\/(\d+)/i', $userAgent, $matches) && !preg_match('/chrome/i', $userAgent)) {
            $device['browser'] = 'Safari ' . $matches[1];
        } elseif (preg_match('/edge\/(\d+)/i', $userAgent, $matches)) {
            $device['browser'] = 'Edge ' . $matches[1];
        } elseif (preg_match('/opera|opera mini/i', $userAgent)) {
            $device['browser'] = 'Opera';
        }

        // OS detection
        if (preg_match('/windows nt 10/i', $userAgent)) {
            $device['os'] = 'Windows 10';
        } elseif (preg_match('/windows nt 11/i', $userAgent)) {
            $device['os'] = 'Windows 11';
        } elseif (preg_match('/mac os x/i', $userAgent)) {
            $device['os'] = 'macOS';
        } elseif (preg_match('/android/i', $userAgent, $matches)) {
            $device['os'] = 'Android';
        } elseif (preg_match('/iphone|ipad|ios/i', $userAgent, $matches)) {
            $device['os'] = 'iOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $device['os'] = 'Linux';
        }

        return $device;
    }

    /**
     * Generate device fingerprint
     */
    public static function generateDeviceFingerprint(Request $request): string
    {
        $fingerprintData = [
            $request->header('User-Agent'),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
            $request->ip(),
            $request->header('X-Forwarded-For'),
        ];

        return hash('sha256', implode('|', $fingerprintData));
    }

    /**
     * Check if login is from new device
     */
    public static function isNewDevice(User $user, string $fingerprint, string $ip): bool
    {
        $existingDevice = \DB::table('login_devices')
            ->where('user_id', $user->id)
            ->where(function ($query) use ($fingerprint, $ip) {
                $query->where('fingerprint', $fingerprint)
                    ->orWhere('ip_address', $ip);
            })
            ->where('is_verified', true)
            ->first();

        return !$existingDevice;
    }

    /**
     * Check if account is locked
     */
    public static function isAccountLocked(User $user): bool
    {
        if ($user->locked_until && Carbon::parse($user->locked_until)->isFuture()) {
            return true;
        }

        // Check database rate limiting
        $rateLimit = \DB::table('rate_limitations')
            ->where('email', $user->email)
            ->where('action', 'login')
            ->where('locked_until', '>', Carbon::now())
            ->first();

        return $rateLimit !== null;
    }

    /**
     * Lock account temporarily
     */
    public static function lockAccount(User $user, int $minutes = 30): void
    {
        $user->locked_until = Carbon::now()->addMinutes($minutes);
        $user->save();

        // Also store in rate_limitations table
        \DB::table('rate_limitations')->updateOrInsert(
            ['email' => $user->email, 'action' => 'login'],
            [
                'attempts' => self::LOGIN_MAX_ATTEMPTS,
                'locked_until' => Carbon::now()->addMinutes($minutes),
                'updated_at' => Carbon::now(),
            ]
        );
    }

    /**
     * Unlock account
     */
    public static function unlockAccount(User $user): void
    {
        $user->locked_until = null;
        $user->failed_login_attempts = 0;
        $user->save();

        // Clear rate limitations
        \DB::table('rate_limitations')
            ->where('email', $user->email)
            ->where('action', 'login')
            ->delete();
    }

    /**
     * Record failed login attempt
     */
    public static function recordFailedLogin(User $user): void
    {
        $user->failed_login_attempts = ($user->failed_login_attempts ?? 0) + 1;

        // Lock after max attempts
        if ($user->failed_login_attempts >= self::LOGIN_MAX_ATTEMPTS) {
            self::lockAccount($user, self::LOGIN_DECAY_MINUTES);
        } else {
            $user->save();
        }
    }

    /**
     * Clean up old rate limit records
     */
    public static function cleanupRateLimits(): void
    {
        \DB::table('rate_limitations')
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->delete();
    }

    /**
     * Generate HTML safe output
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email format
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if email is from disposable email provider
     */
    public static function isDisposableEmail(string $email): bool
    {
        $domain = strtolower(explode('@', $email)[1] ?? '');

        $disposableDomains = [
            'tempmail.com', 'throwaway.email', '10minutemail.com',
            'guerrillamail.com', 'mailinator.com', 'getnada.com',
            'yopmail.com', 'trashmail.com', 'dispostable.com',
        ];

        return in_array($domain, $disposableDomains);
    }

    /**
     * Get client IP address (handles proxies)
     */
    public static function getClientIp(\Illuminate\Http\Request $request): string
    {
        // Check for forwarded IP from proxy
        foreach (['X-Forwarded-For', 'X-Real-IP', 'CF-Connecting-IP'] as $header) {
            $ip = $request->header($header);
            if ($ip) {
                // Take the first IP if multiple
                return explode(',', $ip)[0];
            }
        }

        return $request->ip();
    }
}

// Helper function for binary string conversion
if (!function_exists('binaryString')) {
    function binaryString(string $binary): string {
        return pack('H*', base_convert($binary, 2, 16));
    }
}
