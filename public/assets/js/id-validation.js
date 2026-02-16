/**
 * Indonesian Form Validation Messages
 * Mengubah semua pesan validasi HTML5 menjadi bahasa Indonesia
 */

document.addEventListener('DOMContentLoaded', function() {
    // Indonesian validation messages
    const validationMessages = {
        valueMissing: 'Bidang ini wajib diisi',
        typeMismatch: {
            email: 'Masukkan alamat email yang valid',
            url: 'Masukkan URL yang valid'
        },
        patternMismatch: 'Format tidak sesuai',
        tooLong: 'Nilai terlalu panjang',
        tooShort: {
            default: 'Nilai terlalu pendek',
            // Will be dynamically set based on minlength attribute
        },
        rangeUnderflow: 'Nilai terlalu kecil',
        rangeOverflow: 'Nilai terlalu besar',
        stepMismatch: 'Nilai tidak valid'
    };

    /**
     * Get validation message based on input type and validity
     */
    function getValidationMessage(input, validity) {
        const type = input.type || input.tagName.toLowerCase();

        if (validity.valueMissing) {
            return validationMessages.valueMissing;
        }

        if (validity.typeMismatch) {
            if (type === 'email') {
                return validationMessages.typeMismatch.email;
            }
            if (type === 'url') {
                return validationMessages.typeMismatch.url;
            }
            return 'Format tidak valid';
        }

        if (validity.patternMismatch) {
            return validationMessages.patternMismatch;
        }

        if (validity.tooLong) {
            return validationMessages.tooLong;
        }

        if (validity.tooShort) {
            const minLength = input.getAttribute('minlength');
            if (minLength) {
                return `Minimal ${minLength} karakter`;
            }
            return validationMessages.tooShort.default;
        }

        if (validity.rangeUnderflow) {
            const min = input.getAttribute('min');
            if (min) {
                return `Nilai minimal adalah ${min}`;
            }
            return validationMessages.rangeUnderflow;
        }

        if (validity.rangeOverflow) {
            const max = input.getAttribute('max');
            if (max) {
                return `Nilai maksimal adalah ${max}`;
            }
            return validationMessages.rangeOverflow;
        }

        if (validity.stepMismatch) {
            return validationMessages.stepMismatch;
        }

        return 'Data tidak valid';
    }

    /**
     * Set custom validation message for an input
     */
    function setValidationMessage(input) {
        // Skip if novalidate is set on form
        const form = input.form;
        if (form && form.getAttribute('novalidate') !== null) {
            return;
        }

        // Skip password fields for type-specific validation
        const type = input.type || '';

        if (!input.validity.valid) {
            // For missing value, use the appropriate message
            if (input.validity.valueMissing) {
                input.setCustomValidity(validationMessages.valueMissing);
            } else if (input.validity.typeMismatch) {
                if (type === 'email') {
                    input.setCustomValidity(validationMessages.typeMismatch.email);
                } else if (type === 'url') {
                    input.setCustomValidity(validationMessages.typeMismatch.url);
                } else {
                    input.setCustomValidity('Format tidak valid');
                }
            } else if (input.validity.patternMismatch) {
                input.setCustomValidity(validationMessages.patternMismatch);
            } else if (input.validity.tooLong) {
                input.setCustomValidity(validationMessages.tooLong);
            } else if (input.validity.tooShort) {
                const minLength = input.getAttribute('minlength');
                if (minLength) {
                    input.setCustomValidity(`Minimal ${minLength} karakter`);
                } else {
                    input.setCustomValidity(validationMessages.tooShort.default);
                }
            } else if (input.validity.rangeUnderflow) {
                const min = input.getAttribute('min');
                if (min) {
                    input.setCustomValidity(`Nilai minimal adalah ${min}`);
                } else {
                    input.setCustomValidity(validationMessages.rangeUnderflow);
                }
            } else if (input.validity.rangeOverflow) {
                const max = input.getAttribute('max');
                if (max) {
                    input.setCustomValidity(`Nilai maksimal adalah ${max}`);
                } else {
                    input.setCustomValidity(validationMessages.rangeOverflow);
                }
            } else if (input.validity.stepMismatch) {
                input.setCustomValidity(validationMessages.stepMismatch);
            } else {
                // For other types (password, text, etc.), if not valid but no specific error, clear message
                input.setCustomValidity('');
            }
        } else {
            input.setCustomValidity('');
        }
    }

    /**
     * Initialize validation for all forms
     */
    function initFormValidation() {
        // Select all inputs with validation attributes - be more specific
        const inputs = document.querySelectorAll(
            'input[required]:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), ' +
            'input[min]:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), ' +
            'input[max]:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), ' +
            'input[minlength]:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), ' +
            'input[maxlength]:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), ' +
            'input[pattern]:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), ' +
            'input[type="email"][required]:not([type="hidden"]), ' +
            'input[type="url"][required]:not([type="hidden"]), ' +
            'select[required], ' +
            'textarea[required], ' +
            'textarea[minlength], ' +
            'textarea[maxlength]'
        );

        inputs.forEach(input => {
            // Track if input has been touched
            let touched = false;

            // Mark as touched on blur
            input.addEventListener('blur', function() {
                touched = true;
            });

            // Only validate on input if already touched
            input.addEventListener('input', function() {
                if (touched) {
                    setValidationMessage(this);
                }
            });

            // Handle invalid event - this is when browser detects an error
            input.addEventListener('invalid', function(e) {
                // Prevent default browser message
                e.preventDefault();
                setValidationMessage(this);
                // Show the custom message
                this.reportValidity();
            });
        });

        // Also handle form submission
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Find first invalid input in THIS form only
                    const firstInvalid = this.querySelector('input:invalid, select:invalid, textarea:invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        setValidationMessage(firstInvalid);
                    }
                }
                this.classList.add('was-validated');
            }, false);
        });
    }

    // Run initialization
    initFormValidation();
});

/**
 * Additional helper functions for common validations
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^(\+62|62|0)[0-9]{9,12}$/;
    return re.test(phone.replace(/\s/g, ''));
}

function showValidationError(inputId, message) {
    const input = document.getElementById(inputId);
    if (input) {
        input.setCustomValidity(message);
        input.reportValidity();
    }
}

function clearValidationError(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.setCustomValidity('');
    }
}
