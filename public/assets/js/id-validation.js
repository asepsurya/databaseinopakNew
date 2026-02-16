/**
 * Indonesian HTML5 Form Validation
 * Clean & Safe Version
 */

document.addEventListener('DOMContentLoaded', function () {

    const messages = {
        valueMissing: 'Bidang ini wajib diisi',
        email: 'Masukkan alamat email yang valid',
        url: 'Masukkan URL yang valid',
        pattern: 'Format tidak sesuai',
        tooLong: 'Nilai terlalu panjang',
        tooShort: min => `Minimal ${min} karakter`,
        rangeMin: min => `Nilai minimal adalah ${min}`,
        rangeMax: max => `Nilai maksimal adalah ${max}`,
        step: 'Nilai tidak valid'
    };

    function setValidationMessage(input) {
        if (!input || input.validity.valid) {
            input.setCustomValidity('');
            return;
        }

        const v = input.validity;

        if (v.valueMissing) {
            input.setCustomValidity(messages.valueMissing);
        } else if (v.typeMismatch) {
            input.setCustomValidity(
                input.type === 'email' ? messages.email :
                input.type === 'url' ? messages.url :
                'Format tidak valid'
            );
        } else if (v.patternMismatch) {
            input.setCustomValidity(messages.pattern);
        } else if (v.tooLong) {
            input.setCustomValidity(messages.tooLong);
        } else if (v.tooShort) {
            const min = input.getAttribute('minlength');
            input.setCustomValidity(min ? messages.tooShort(min) : messages.tooLong);
        } else if (v.rangeUnderflow) {
            const min = input.getAttribute('min');
            input.setCustomValidity(min ? messages.rangeMin(min) : '');
        } else if (v.rangeOverflow) {
            const max = input.getAttribute('max');
            input.setCustomValidity(max ? messages.rangeMax(max) : '');
        } else if (v.stepMismatch) {
            input.setCustomValidity(messages.step);
        } else {
            input.setCustomValidity('');
        }
    }

    function initFormValidation() {

        const skipForms = ['registerForm', 'loginForm', 'password-form'];

        const inputs = document.querySelectorAll(`
            input[required]:not([type="hidden"]):not([type="password"]),
            input[min]:not([type="hidden"]):not([type="password"]),
            input[max]:not([type="hidden"]):not([type="password"]),
            input[minlength]:not([type="hidden"]):not([type="password"]),
            input[maxlength]:not([type="hidden"]):not([type="password"]),
            input[pattern]:not([type="hidden"]):not([type="password"]),
            select[required],
            textarea[required],
            textarea[minlength],
            textarea[maxlength]
        `);

        inputs.forEach(input => {
            let touched = false;
            let isHandlingInvalid = false;
            input.addEventListener('blur', () => touched = true);

            input.addEventListener('input', function () {
                if (touched) setValidationMessage(this);
            });

            input.addEventListener('invalid', function (e) {
                isHandlingInvalid = true;
                e.preventDefault();

                setValidationMessage(this);

                // Lepas flag di tick berikutnya
                setTimeout(() => {
                    isHandlingInvalid = false;
                }, 0);
            });
        });

        document.querySelectorAll('form').forEach(form => {

            if (
                form.hasAttribute('novalidate') ||
                skipForms.includes(form.id)
            ) return;

            form.addEventListener('submit', function (e) {
                if (!this.checkValidity()) {
                    e.preventDefault();

                    const firstInvalid = this.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        setValidationMessage(firstInvalid);
                    }
                }

                this.classList.add('was-validated');
            });
        });
    }

    initFormValidation();
});

/* Helper utilities */

function showValidationError(inputId, message) {
    const input = document.getElementById(inputId);
    if (!input) return;
    input.setCustomValidity(message);

}

function clearValidationError(inputId) {
    const input = document.getElementById(inputId);
    if (input) input.setCustomValidity('');
}