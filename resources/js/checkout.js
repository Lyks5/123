document.addEventListener('DOMContentLoaded', function() {
    // Переключение формы адреса доставки
    const shippingAddressType = document.querySelectorAll('input[name="shipping_address_type"]');
    const newShippingForm = document.getElementById('new_shipping_form');
    
    if (shippingAddressType && newShippingForm) {
        shippingAddressType.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'new') {
                    newShippingForm.classList.remove('hidden');
                } else {
                    newShippingForm.classList.add('hidden');
                }
            });
        });
    }
    
    // Переключение формы платежного адреса
    const billingAddressType = document.querySelectorAll('input[name="billing_address_type"]');
    const newBillingForm = document.getElementById('new_billing_form');
    const existingBillingAddresses = document.getElementById('existing_billing_addresses');
    
    if (billingAddressType && newBillingForm && existingBillingAddresses) {
        billingAddressType.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'new') {
                    newBillingForm.classList.remove('hidden');
                    existingBillingAddresses?.classList.add('hidden');
                } else if (this.value === 'existing') {
                    newBillingForm.classList.add('hidden');
                    existingBillingAddresses?.classList.remove('hidden');
                } else {
                    newBillingForm.classList.add('hidden');
                    existingBillingAddresses?.classList.add('hidden');
                }
            });
        });
    }
    
    // Обработка формы оплаты
    const paymentMethod = document.querySelectorAll('input[name="payment_method"]');
    const cardPaymentDetails = document.getElementById('card_payment_details');
    
    if (paymentMethod && cardPaymentDetails) {
        paymentMethod.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'credit_card') {
                    cardPaymentDetails.classList.remove('hidden');
                } else {
                    cardPaymentDetails.classList.add('hidden');
                }
            });
        });
    }
    
    // Форматирование полей карты
    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCvv = document.getElementById('card_cvv');
    
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            // Удаляем все нецифровые символы
            let value = this.value.replace(/\D/g, '');
            
            // Добавляем пробелы после каждых 4 цифр
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join(' ');
            }
            
            // Ограничиваем длину
            if (value.length > 19) {
                value = value.substr(0, 19);
            }
            
            this.value = value;
        });
    }
    
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function(e) {
            // Удаляем все нецифровые символы
            let value = this.value.replace(/\D/g, '');
            
            // Форматируем как MM/YY
            if (value.length > 0) {
                if (value.length > 2) {
                    value = value.substr(0, 2) + '/' + value.substr(2);
                }
                
                // Ограничиваем длину
                if (value.length > 5) {
                    value = value.substr(0, 5);
                }
            }
            
            this.value = value;
        });
    }
    
    if (cardCvv) {
        cardCvv.addEventListener('input', function(e) {
            // Удаляем все нецифровые символы
            let value = this.value.replace(/\D/g, '');
            
            // Ограничиваем длину
            if (value.length > 4) {
                value = value.substr(0, 4);
            }
            
            this.value = value;
        });
    }
    
    // Обновление способа доставки с AJAX
    const shippingMethodInputs = document.querySelectorAll('input[name="shipping_method"]');
    
    if (shippingMethodInputs) {
        shippingMethodInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                // Получаем CSRF токен
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Отправляем AJAX запрос
                fetch('/checkout/shipping', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        shipping_method: this.value
                    })
                })
                .then(response => {
                    if (response.ok) {
                        // Перезагружаем страницу для обновления итоговой суммы
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
            });
        });
    }
    
    // Валидация формы перед отправкой
    const checkoutForm = document.getElementById('checkout-form');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Проверка адреса доставки
            const shippingAddressType = document.querySelector('input[name="shipping_address_type"]:checked')?.value;
            
            if (shippingAddressType === 'existing') {
                const shippingAddressId = document.querySelector('input[name="shipping_address_id"]:checked');
                if (!shippingAddressId) {
                    isValid = false;
                    alert('Пожалуйста, выберите адрес доставки');
                }
            } else if (shippingAddressType === 'new') {
                // Проверка обязательных полей нового адреса доставки
                const requiredFields = [
                    'shipping_first_name',
                    'shipping_last_name',
                    'shipping_address_line1',
                    'shipping_city',
                    'shipping_state',
                    'shipping_postal_code',
                    'shipping_country'
                ];
                
                for (const field of requiredFields) {
                    const input = document.getElementById(field);
                    if (!input || !input.value.trim()) {
                        isValid = false;
                        alert('Пожалуйста, заполните все обязательные поля адреса доставки');
                        break;
                    }
                }
            }
            
            // Проверка платежных данных
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
            
            if (paymentMethod === 'credit_card') {
                const cardFields = [
                    'card_number',
                    'card_name',
                    'card_expiry',
                    'card_cvv'
                ];
                
                for (const field of cardFields) {
                    const input = document.getElementById(field);
                    if (!input || !input.value.trim()) {
                        isValid = false;
                        alert('Пожалуйста, заполните все поля данных карты');
                        break;
                    }
                }
                
                // Дополнительная валидация номера карты
                const cardNumber = document.getElementById('card_number');
                if (cardNumber && cardNumber.value.replace(/\s/g, '').length < 16) {
                    isValid = false;
                    alert('Пожалуйста, введите корректный номер карты');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});