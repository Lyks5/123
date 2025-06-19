import React from 'react';
import { Product } from '../types/product';

interface ProductCardProps {
  product: Product;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  return (
    <div className="product-card border p-4 rounded shadow-sm">
      <h2 className="text-lg font-semibold mb-2">{product.name}</h2>
      <p className="text-gray-700 mb-1">Category: {product.category}</p>
      <p className="text-gray-700 mb-1">Price: {product.price} ₽</p>
      <p className={`mb-1 ${product.inStock ? 'text-green-600' : 'text-red-600'}`}>
        {product.inStock ? 'In Stock' : 'Out of Stock'}
      </p>
    </div>
  );
};
