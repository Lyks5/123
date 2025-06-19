document.addEventListener('DOMContentLoaded', function() {
    // Показать модальное окно
    document.querySelectorAll('.show-modal-btn').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.dataset.modal;
            document.getElementById(modalId).classList.remove('hidden');
        });
    });

    // Скрыть модальное окно
    document.querySelectorAll('.hide-modal-btn').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.dataset.modal;
            document.getElementById(modalId).classList.add('hidden');
        });
    });

    // Обработка кнопки редактирования адреса
    document.querySelectorAll('.edit-address-btn').forEach(button => {
        button.addEventListener('click', function() {
            const addressId = this.dataset.addressId;
            document.getElementById('edit-address-modal').classList.remove('hidden');
            
            // Здесь можно добавить логику загрузки данных адреса
            // и заполнения формы редактирования
        });
    });
});