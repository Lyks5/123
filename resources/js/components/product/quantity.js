export const initQuantityControl = (options = {}) => {
    const {
        minQuantity = 1,
        maxQuantity = 99,
        onChange = () => {}
    } = options;

    const quantityInput = document.querySelector('.product-quantity-input');
    const increaseBtn = document.querySelector('.quantity-increase');
    const decreaseBtn = document.querySelector('.quantity-decrease');

    if (!quantityInput || !increaseBtn || !decreaseBtn) {
        console.error('Quantity control elements not found');
        return null;
    }

    let currentQuantity = parseInt(quantityInput.value) || minQuantity;

    const updateQuantity = (newQuantity) => {
        newQuantity = Math.max(minQuantity, Math.min(maxQuantity, newQuantity));
        currentQuantity = newQuantity;
        quantityInput.value = newQuantity;
        onChange(newQuantity);
    };

    increaseBtn.addEventListener('click', () => {
        updateQuantity(currentQuantity + 1);
    });

    decreaseBtn.addEventListener('click', () => {
        updateQuantity(currentQuantity - 1);
    });

    quantityInput.addEventListener('change', (e) => {
        const value = parseInt(e.target.value) || minQuantity;
        updateQuantity(value);
    });

    // Инициализация начального значения
    updateQuantity(currentQuantity);

    // Публичные методы
    return {
        getValue: () => currentQuantity,
        setValue: (value) => updateQuantity(value),
        increment: () => updateQuantity(currentQuantity + 1),
        decrement: () => updateQuantity(currentQuantity - 1)
    };
};