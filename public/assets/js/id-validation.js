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

        if (!input.validity.valid) {
            input.setCustomValidity(getValidationMessage(input, input.validity));
        } else {
            input.setCustomValidity('');
        }
    }

    /**
     * Initialize validation for all forms
     */
    function initFormValidation() {
        // Select all inputs with validation attributes
        const selectors = [
            'input[required]',
            'input[min]',
            'input[max]',
            'input[minlength]',
            'input[maxlength]',
            'input[pattern]',
            'input[type="email"]',
            'input[type="url"]',
            'select[required]',
            'textarea[required]',
            'textarea[minlength]',
            'textarea[maxlength]'
        ];

        const inputs = document.querySelectorAll(selectors.join(', '));

        inputs.forEach(input => {
            // Add event listeners for validation
            input.addEventListener('input', function() {
                setValidationMessage(this);
            });

            input.addEventListener('blur', function() {
                setValidationMessage(this);
            });

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

                    // Find first invalid input
                    const firstInvalid = this.querySelector(':invalid');
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
