'use client'

import { useEffect, useState } from 'react'
import { ProductForm } from '@/components/products/ProductForm'
import { Product, Category, Attribute, ProductFormData } from '@/types/product'

interface EditProductProps {
  productId: number;
  initialProduct: Product;
  categories: Category[];
  attributes: Attribute[];
}

export function EditProduct({
  productId,
  initialProduct,
  categories,
  attributes
}: EditProductProps) {

  const handleSubmit = async (data: ProductFormData) => {
    try {
      const response = await fetch(`/api/products/${productId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      });

      if (!response.ok) {
        throw new Error('Ошибка при сохранении товара');
      }

      // Редирект на страницу списка товаров после успешного сохранения
      window.location.href = '/admin/products';
    } catch (error) {
      console.error('Error updating product:', error);
    }
  };

  return (
    <div>
      <h1 className="text-3xl font-bold mb-8">Редактирование товара</h1>
      <ProductForm
        product={initialProduct}
        categories={categories}
        attributes={attributes}
        onSubmit={handleSubmit}
      />
    </div>
  )
}