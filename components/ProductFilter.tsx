import React, { useState } from 'react';

interface FilterProps {
  filters: FilterValues;
  onFilterChange: (filters: FilterValues) => void;
}

interface FilterValues {
  priceMin: number;
  priceMax: number;
  category: string[];
  inStock: boolean;
}

export const ProductFilter: React.FC<FilterProps> = ({ filters, onFilterChange }) => {
  const handleChange = (field: keyof FilterValues, value: any) => {
    const newFilters = { ...filters, [field]: value };
    onFilterChange(newFilters);
  };

  return (
    <div className="product-filter">
      <div className="filter-group">
        <h3>Цена</h3>
        <input
          type="number"
          placeholder="От"
          value={filters.priceMin}
          onChange={(e) => handleChange('priceMin', Number(e.target.value))}
        />
        <input
          type="number"
          placeholder="До"
          value={filters.priceMax}
          onChange={(e) => handleChange('priceMax', Number(e.target.value))}
        />
      </div>

      <div className="filter-group">
        <h3>Категория</h3>
        {['Электроника', 'Одежда', 'Книги'].map(cat => (
          <label key={cat}>
            <input
              type="checkbox"
              checked={filters.category.includes(cat)}
              onChange={(e) => {
                const newCategories = e.target.checked 
                  ? [...filters.category, cat]
                  : filters.category.filter(c => c !== cat);
                handleChange('category', newCategories);
              }}
            />
            {cat}
          </label>
        ))}
      </div>

      <div className="filter-group">
        <label>
          <input
            type="checkbox"
            checked={filters.inStock}
            onChange={(e) => handleChange('inStock', e.target.checked)}
          />
          В наличии
        </label>
      </div>

      <button
        className="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        onClick={() => onFilterChange(filters)}
      >
        Применить фильтр
      </button>
    </div>
  );
};
