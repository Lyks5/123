document.addEventListener('DOMContentLoaded', function() {
    // Обработчики для модального окна создания адреса
    const addModal = document.getElementById('add-address-modal');
    const addModalCloseButtons = addModal.querySelectorAll('button[type="button"]');
    
    // Закрытие модального окна создания по клику вне его области
    addModal?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    
    // Закрытие модального окна создания по кнопке
    addModalCloseButtons.forEach(button => {
        button.addEventListener('click', function() {
            addModal.classList.add('hidden');
        });
    });

    // Инициализация формы редактирования
    const editForm = document.getElementById('edit-address-form');
    const editModal = document.getElementById('edit-address-modal');

    // Функция для создания HTML адресной карточки
    function createAddressCard(address) {
        const isDefault = address.is_default ? 'bg-eco-100' : '';
        const addressType = address.type === 'billing' ? 'Для счетов' : 'Для доставки';
        
        return `
            <div class="border border-eco-100 rounded-lg p-4">
                <div class="flex justify-between mb-2">
                    <h3 class="font-medium text-eco-900">
                        ${address.first_name} ${address.last_name}
                        ${address.is_default ? '<span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-eco-100 text-eco-800">По умолчанию</span>' : ''}
                    </h3>
                    <button type="button" class="edit-address-btn text-eco-600 hover:text-eco-700" data-address-id="${address.id}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>
                <div class="text-eco-600 text-sm">
                    <p>${address.address_line1}</p>
                    ${address.address_line2 ? `<p>${address.address_line2}</p>` : ''}
                    <p>${address.city}, ${address.state} ${address.postal_code}</p>
                    <p>${address.country}</p>
                    ${address.phone ? `<p class="mt-1">${address.phone}</p>` : ''}
                </div>
            </div>
        `;
    }

    // Функция для показа уведомлений через NotificationManager
    function showNotification(message, type = 'success') {
        console.log('Отображение уведомления:', { message, type });
        window.dispatchEvent(new CustomEvent('show-notification', {
            detail: { message, type }
        }));
    }

    // Функция-обработчик для кнопки редактирования
    function editButtonHandler() {
        const addressId = this.dataset.addressId;
        const addressCard = this.closest('.border');

        // Заполняем форму данными адреса
        editForm.action = `/account/addresses/${addressId}`;
        
        // Получаем данные из карточки адреса
        const fullName = addressCard.querySelector('h3').textContent.trim().split(/\s+/);
        const firstName = fullName[0] || '';
        const lastName = fullName.slice(1).join(' ') || '';
        const addressLines = addressCard.querySelectorAll('.text-eco-600 p');
        
        // Заполняем поля формы
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_address_line1').value = addressLines[0].textContent;
        document.getElementById('edit_address_line2').value = addressLines[1]?.textContent || '';
        
        const cityStateZip = addressLines[2].textContent.split(', ');
        document.getElementById('edit_city').value = cityStateZip[0];
        document.getElementById('edit_state').value = cityStateZip[1].split(' ')[0];
        document.getElementById('edit_postal_code').value = cityStateZip[1].split(' ')[1];
        document.getElementById('edit_country').value = addressLines[3].textContent;
        
        if (addressLines[4]) {
            document.getElementById('edit_phone').value = addressLines[4].textContent;
        }

        // Устанавливаем тип адреса
        const addressType = addressCard.querySelector('.text-xs').textContent.trim();
        const typeRadio = editForm.querySelector(`input[name="type"][value="${addressType === 'Для счетов' ? 'billing' : 'shipping'}"]`);
        if (typeRadio) typeRadio.checked = true;

        // Устанавливаем чекбокс "по умолчанию"
        const isDefault = addressCard.querySelector('.bg-eco-100') !== null;
        editForm.querySelector('input[name="is_default"]').checked = isDefault;

        // Показываем модальное окно
        editModal.classList.remove('hidden');
    }

    // Назначаем обработчики для существующих кнопок редактирования
    const editButtons = document.querySelectorAll('.edit-address-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', editButtonHandler);
    });

    // Закрытие модального окна редактирования по клику вне его области
    editModal?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Обработка формы создания адреса
    const addressForm = addModal.querySelector('form');
    addressForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                // Если статус не 2xx, пытаемся получить детали ошибки
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Ошибка при обработке запроса');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Показываем уведомление об успехе
                showNotification(data.message, 'success');
                
                // Перезагружаем страницу после короткой задержки
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Показываем ошибки, если есть
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat();
                    showNotification(errorMessages.join('\n'), 'error');
                } else {
                    showNotification(data.message || 'Произошла неизвестная ошибка', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            showNotification(error.message || 'Произошла ошибка при сохранении адреса', 'error');
        });
    });
});