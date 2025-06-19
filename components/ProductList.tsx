import React from 'react';
import { ProductCard } from './ProductCard';

export interface Product {
  id: number;
  name: string;
  price: number;
  category: string;
  inStock: boolean;
}

interface ProductListProps {
  products: Product[];
}

export const ProductList: React.FC<ProductListProps> = ({ products }) => (
  <div className="product-list grid grid-cols-1 md:grid-cols-3 gap-4">
    {products.map(product => (
      <ProductCard key={product.id} product={product} />
    ))}
  </div>
);