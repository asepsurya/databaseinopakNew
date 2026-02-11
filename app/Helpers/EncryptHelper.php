<?php

use Illuminate\Support\Facades\Crypt;

/**
 * Encrypt an ID for secure URL parameters
 *
 * @param int|string $id The ID to encrypt
 * @return string The encrypted ID
 */
function encryptId($id)
{
    try {
        return Crypt::encryptString($id);
    } catch (\Exception $e) {
        \Log::error('ID encryption failed: ' . $e->getMessage());
        return $id;
    }
}

/**
 * Decrypt an ID from URL parameters
 *
 * @param string $encryptedId The encrypted ID
 * @return int|string The decrypted ID, or original if decryption fails
 */
function decryptId($encryptedId)
{
    try {
        return Crypt::decryptString($encryptedId);
    } catch (\Exception $e) {
        \Log::error('ID decryption failed: ' . $e->getMessage());
        return $encryptedId;
    }
}

/**
 * Generate an encrypted URL with ID parameters
 *
 * @param string $route The route name
 * @param array $parameters The route parameters
 * @return string The encrypted URL
 */
function encryptedRoute($route, $parameters = [])
{
    $encryptedParams = [];

    foreach ($parameters as $key => $value) {
        if (is_numeric($value) || is_string($value)) {
            // Encrypt numeric/string IDs
            $encryptedParams[$key] = encryptId($value);
        } else {
            $encryptedParams[$key] = $value;
        }
    }

    return route($route, $encryptedParams);
}
