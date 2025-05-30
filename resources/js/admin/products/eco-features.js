// Обработка эко-характеристик
document.addEventListener('DOMContentLoaded', function() {
    const ecoFeatureCheckboxes = document.querySelectorAll('.eco-feature-checkbox');
    
    ecoFeatureCheckboxes.forEach(checkbox => {
        // Инициализация при загрузке страницы
        const featureId = checkbox.dataset.featureId;
        const valueContainer = document.getElementById(`eco_value_${featureId}`);
        const valueInput = valueContainer.querySelector('input[type="number"]');
        
        // Если есть значение, показываем контейнер и обновляем текст экономии
        if (valueInput.value) {
            valueContainer.classList.remove('hidden');
            updateSavings(featureId, valueInput.value);
        }
        
        checkbox.addEventListener('change', function() {
            const featureId = this.dataset.featureId;
            const valueContainer = document.getElementById(`eco_value_${featureId}`);
            const valueInput = valueContainer.querySelector('input[type="number"]');
            
            if (this.checked) {
                valueContainer.classList.remove('hidden');
            } else {
                valueContainer.classList.add('hidden');
                valueInput.value = '';
                updateSavings(featureId, 0);
            }
        });
    });

    // Обработка изменения значений
    document.querySelectorAll('.eco-feature-value input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            const featureId = this.name.match(/\[(\d+)\]/)[1];
            updateSavings(featureId, this.value);
        });
    });
});

// Обновление текста экономии
function updateSavings(featureId, value) {
    const savingsElement = document.getElementById(`savings_${featureId}`);
    const feature = getFeatureById(featureId);
    
    if (!value || value <= 0) {
        savingsElement.textContent = '';
        return;
    }

    let savingsText = '';
    switch (feature.slug) {
        case 'carbon-footprint':
            savingsText = `Экономия ${value} кг CO₂ по сравнению с обычным производством`;
            break;
        case 'water-saved':
            savingsText = `Экономия ${value} литров воды по сравнению с обычным производством`;
            break;
        case 'recycled-plastic':
            savingsText = `Использовано ${value} кг переработанного пластика`;
            break;
    }
    
    savingsElement.textContent = savingsText;
}

// Получение информации о характеристике
function getFeatureById(id) {
    const slug = window.ecoFeatures[id];
    return { slug: slug };
}