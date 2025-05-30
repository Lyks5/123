import * as z from 'zod';

export const productSchema = z.object({
  is_featured: z.boolean().optional(),
  name: z.string().min(3, 'Название должно содержать минимум 3 символа'),
  description: z.string().min(10, 'Описание должно содержать минимум 10 символов'),
  sku: z.string().min(4, 'SKU должен содержать минимум 4 символа'),
  price: z.number().min(0.01, 'Цена должна быть больше 0'),
  category_id: z.number().min(1, 'Выберите категорию').or(z.undefined()),
  status: z.enum(['draft', 'published', 'archived']),
  attributes: z.array(z.object({
    attribute_id: z.number(),
    value: z.string().min(1, 'Введите значение')
  })),
  images: z.array(z.object({
    id: z.number().optional(),
    url: z.string(),
    order: z.number()
  })),
  eco_features: z.array(z.object({
    id: z.number(),
    value: z.string().optional()
  }))
});

export type ProductFormData = z.infer<typeof productSchema>;