import React from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { ProductForm } from '@/components/products/ProductForm';
import type { Category, Attribute, ProductFormData } from '@/types/product';
import { productSchema } from '@/lib/validations/product';
import { createProduct } from '@/lib/api';
import { FormStorage } from '@/lib/storage';

interface CreateProductPageProps {
  categories: Category[];
  attributes: Attribute[];
}

export function CreateProduct() {
  const [isSubmitting, setIsSubmitting] = React.useState(false);
  
  const rootElement = document.getElementById('create-product-root');
  const categories = JSON.parse(rootElement?.dataset.categories || '[]');
  const attributes = JSON.parse(rootElement?.dataset.attributes || '[]');

  // Загружаем сохраненное состояние формы или используем значения по умолчанию
  const savedState = FormStorage.loadFormState('create_product') || {
    name: '',
    description: '',
    sku: '',
    price: 0.01,
    category_id: undefined,
    status: 'draft',
    attributes: [],
    images: []
  };

  const form = useForm<ProductFormData>({
    resolver: zodResolver(productSchema),
    defaultValues: savedState,
    mode: 'onChange'
  });

  // Сохраняем состояние формы при изменении
  React.useEffect(() => {
    const subscription = form.watch((value) => {
      if (Object.keys(value).length > 0) {
        FormStorage.saveFormState('create_product', value);
        console.log('Form state saved:', value);
      }
    });
    return () => subscription.unsubscribe();
  }, [form.watch]);

  const onSubmit = async (data: ProductFormData) => {
    console.log('Submitting form data:', data);
    try {
      setIsSubmitting(true);
      const response = await createProduct(data);
      
      if (response.status === 'success' && response.data?.id) {
        // Очищаем сохраненное состояние формы после успешного создания
        FormStorage.clearFormState('create_product');
        window.location.href = `/admin/products/${response.data.id}/edit`;
      } else {
        const message = response.message || 'Произошла ошибка при создании товара';
        alert(`Ошибка: ${message}`);
      }
    } catch (error) {
      const errorMessage = error instanceof Error ? error.message : 'Неизвестная ошибка';
      alert(`Ошибка при создании товара: ${errorMessage}`);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="py-6">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 className="text-2xl font-semibold text-gray-900">
          Создание товара
        </h1>
        
        <div className="mt-6">
          <ProductForm
            categories={categories}
            attributes={attributes}
            onSubmit={onSubmit}
            isSubmitting={isSubmitting}
          />
        </div>
      </div>
    </div>
  );
}
