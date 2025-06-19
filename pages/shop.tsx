import React, { useEffect, useState } from 'react';
import { ProductFilter } from '../components/ProductFilter';
import { ProductList, Product } from '../components/ProductList';

interface FilterValues {
  priceMin: number;
  priceMax: number;
  category: string[];
  inStock: boolean;
}

function parseQuery(): FilterValues {
  const params = new URLSearchParams(window.location.search);
  return {
    priceMin: Number(params.get('priceMin')) || 0,
    priceMax: Number(params.get('priceMax')) || 999999,
    category: params.get('category') ? params.get('category')!.split(',') : [],
    inStock: params.get('inStock') === 'true',
  };
}

function buildQuery(filters: FilterValues): string {
  const params = new URLSearchParams();
  params.set('priceMin', String(filters.priceMin));
  params.set('priceMax', String(filters.priceMax));
  if (filters.category.length > 0) params.set('category', filters.category.join(','));
  if (filters.inStock) params.set('inStock', 'true');
  return params.toString();
}

export default function Shop() {
  const [filters, setFilters] = useState<FilterValues>(parseQuery());
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(false);

  // Синхронизация фильтров с URL
  useEffect(() => {
    const query = buildQuery(filters);
    window.history.replaceState(null, '', '?' + query);
  }, [filters]);

  // Загрузка товаров с сервера при изменении фильтров
  useEffect(() => {
    setLoading(true);
    fetch('/api/products?' + buildQuery(filters))
      .then(res => res.json())
      .then(data => {
        setProducts(data.products || []);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [filters]);

  const handleFilterChange = (newFilters: FilterValues) => {
    setFilters(newFilters);
  };

  return (
    <div className="shop-container">
      <div className="shop-filters">
        <ProductFilter filters={filters} onFilterChange={handleFilterChange} />
      </div>
      <div className="shop-products">
        {loading ? (
          <div>Загрузка...</div>
        ) : (
          <ProductList products={products} />
        )}
      </div>
    </div>
  );
}
