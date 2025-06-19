export const initTabs = () => {
    const tabButtons = document.querySelectorAll('.product-tab-button');
    const tabPanels = document.querySelectorAll('.product-tab-panel');
    let activeTab = 0;

    const setActiveTab = (index) => {
        // Удаляем активные классы
        tabButtons.forEach(button => button.classList.remove('active'));
        tabPanels.forEach(panel => panel.classList.remove('active'));

        // Устанавливаем новую активную вкладку
        tabButtons[index].classList.add('active');
        tabPanels[index].classList.add('active');
        activeTab = index;

        // Вызываем событие смены вкладки
        const event = new CustomEvent('tab-changed', { 
            detail: { 
                index,
                tabId: tabButtons[index].dataset.tab,
                content: tabPanels[index]
            }
        });
        document.dispatchEvent(event);
    };

    // Добавляем обработчики для кнопок
    tabButtons.forEach((button, index) => {
        button.addEventListener('click', () => setActiveTab(index));
    });

    // Инициализируем первую вкладку
    setActiveTab(0);

    // Публичные методы
    return {
        switchTab: (index) => setActiveTab(index),
        getCurrentTab: () => activeTab,
        getTotalTabs: () => tabButtons.length
    };
};

export const scrollToTab = (tabId) => {
    const tabButton = document.querySelector(`[data-tab="${tabId}"]`);
    if (tabButton) {
        tabButton.scrollIntoView({ behavior: 'smooth' });
        tabButton.click();
    }
};