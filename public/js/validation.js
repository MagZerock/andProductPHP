document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-product-form]');

    if (!form) {
        return;
    }

    const fields = {
        name: form.querySelector('#name'),
        brand: form.querySelector('#brand'),
        flavor: form.querySelector('#flavor'),
        category: form.querySelector('#category'),
        price: form.querySelector('#price')
    };

    const messages = {
        required: 'Please fill out this field.',
        priceInvalid: 'Price must be a valid number greater than or equal to 0.',
        selectCategory: 'Please select a category.'
    };

    function setError(input, message) {
        input.setCustomValidity(message);
    }

    function clearError(input) {
        input.setCustomValidity('');
    }

    function validateRequired(input) {
        if (!input.value.trim()) {
            setError(input, messages.required);
            return false;
        }

        clearError(input);
        return true;
    }

    function validateCategory(input) {
        if (!input.value) {
            setError(input, messages.selectCategory);
            return false;
        }

        clearError(input);
        return true;
    }

    function validatePrice(input) {
        const value = Number(input.value);

        if (input.value === '' || Number.isNaN(value) || value < 0) {
            setError(input, messages.priceInvalid);
            return false;
        }

        clearError(input);
        return true;
    }

    function validateForm() {
        const checks = [
            validateRequired(fields.name),
            validateRequired(fields.brand),
            validateCategory(fields.category),
            validatePrice(fields.price)
        ];

        return checks.every(Boolean);
    }

    Object.values(fields).forEach(function (input) {
        if (!input) {
            return;
        }

        input.addEventListener('input', function () {
            clearError(input);
        });

        input.addEventListener('change', function () {
            clearError(input);
        });
    });

    form.addEventListener('submit', function (event) {
        if (!validateForm()) {
            event.preventDefault();
            form.reportValidity();
        }
    });
});
