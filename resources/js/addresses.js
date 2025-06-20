document.addEventListener('DOMContentLoaded', function() {
    // Валидация телефона
    const validatePhone = (value) => {
        const phoneRegex = /^(\+7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
        return phoneRegex.test(value);
    };

    // Валидация почтового индекса
    const validatePostalCode = (value) => {
        const postalRegex = /^\d{6}$/;
        return postalRegex.test(value);
    };

    // Валидация имени и фамилии
    const validateName = (value) => {
        const nameRegex = /^[а-яА-ЯёЁ\s-]{2,50}$/;
        return nameRegex.test(value);
    };

    const addValidation = (form) => {
        const inputs = {
            phone: form.querySelector('input[name="phone"]'),
            postal_code: form.querySelector('input[name="postal_code"]'),
            first_name: form.querySelector('input[name="first_name"]'),
            last_name: form.querySelector('input[name="last_name"]')
        };

        // Добавляем валидацию при вводе
        if (inputs.phone) {
            inputs.phone.addEventListener('input', function() {
                if (this.value && !validatePhone(this.value)) {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-eco-200');
                    showError(this, 'Введите корректный номер телефона (+7/8XXXXXXXXXX)');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-eco-200');
                    hideError(this);
                }
            });
        }

        if (inputs.postal_code) {
            inputs.postal_code.addEventListener('input', function() {
                if (!validatePostalCode(this.value)) {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-eco-200');
                    showError(this, 'Введите корректный почтовый индекс (6 цифр)');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-eco-200');
                    hideError(this);
                }
            });
        }

        if (inputs.first_name) {
            inputs.first_name.addEventListener('input', function() {
                if (!validateName(this.value)) {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-eco-200');
                    showError(this, 'Имя должно содержать только русские буквы, пробел или дефис (2-50 символов)');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-eco-200');
                    hideError(this);
                }
            });
        }

        if (inputs.last_name) {
            inputs.last_name.addEventListener('input', function() {
                if (!validateName(this.value)) {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-eco-200');
                    showError(this, 'Фамилия должна содержать только русские буквы, пробел или дефис (2-50 символов)');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-eco-200');
                    hideError(this);
                }
            });
        }

        // Валидация формы перед отправкой
        form.addEventListener('submit', function(e) {
            let isValid = true;

            if (inputs.phone && inputs.phone.value && !validatePhone(inputs.phone.value)) {
                inputs.phone.classList.add('border-red-500');
                showError(inputs.phone, 'Введите корректный номер телефона');
                isValid = false;
            }

            if (inputs.postal_code && !validatePostalCode(inputs.postal_code.value)) {
                inputs.postal_code.classList.add('border-red-500');
                showError(inputs.postal_code, 'Введите корректный почтовый индекс');
                isValid = false;
            }

            if (inputs.first_name && !validateName(inputs.first_name.value)) {
                inputs.first_name.classList.add('border-red-500');
                showError(inputs.first_name, 'Введите корректное имя');
                isValid = false;
            }

            if (inputs.last_name && !validateName(inputs.last_name.value)) {
                inputs.last_name.classList.add('border-red-500');
                showError(inputs.last_name, 'Введите корректную фамилию');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    };

    const showError = (input, message) => {
        // Удаляем предыдущее сообщение об ошибке, если оно есть
        hideError(input);

        // Создаем элемент с сообщением об ошибке
        const error = document.createElement('p');
        error.className = 'text-red-500 text-sm mt-1';
        error.textContent = message;
        input.parentNode.appendChild(error);
    };

    const hideError = (input) => {
        const existingError = input.parentNode.querySelector('.text-red-500');
        if (existingError) {
            existingError.remove();
        }
    };

    // Добавляем валидацию ко всем формам адресов
    document.querySelectorAll('form').forEach(form => {
        if (form.matches('[action*="addresses"]')) {
            addValidation(form);
        }
    });
});