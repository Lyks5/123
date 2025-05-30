import React from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Form, FormField, FormItem, FormLabel, FormControl, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { ImageUpload } from './ImageUpload'
import { AttributesManager } from './AttributesManager'
import { PriceManager } from './PriceManager'
import { EcoFeaturesManager } from './EcoFeaturesManager'
import { StatusBar } from './StatusBar'
import { Category, Attribute, EcoFeature, ProductFormProps, ProductFormData } from '@/types/product'
import { productSchema } from '@/lib/validations/product'
import { FormStorage } from '@/lib/storage'
import { ApiError } from '@/lib/api'

interface Props {
  categories: Category[];
  attributes: Attribute[];
  ecoFeatures: EcoFeature[];
  onSubmit: (data: ProductFormData) => Promise<void>;
  isSubmitting?: boolean;
}

export function ProductForm({ categories, attributes, ecoFeatures, onSubmit, isSubmitting = false }: Props) {
  const [formError, setFormError] = React.useState<string | null>(null);
  const [fieldErrors, setFieldErrors] = React.useState<Record<string, string[]>>({});
  
  const form = useForm<ProductFormData>({
    resolver: zodResolver(productSchema),
    defaultValues: {
      name: '',
      description: '',
      sku: '',
      price: 0.01,
      category_id: undefined,
      status: 'draft',
      is_featured: false,
      attributes: [],
      images: [],
      eco_features: []
    },
    mode: 'onChange',
    shouldUnregister: false
  });

  // Загружаем сохраненное состояние при монтировании
  React.useEffect(() => {
    const savedState = FormStorage.loadFormState('create_product');
    if (savedState) {
      Object.keys(savedState).forEach((key) => {
        if (key !== 'currentTab') {
          const value = savedState[key];
          if (value !== undefined && value !== null) {
            form.setValue(key as any, value, {
              shouldValidate: true,
              shouldDirty: true
            });
          }
        }
      });
    }
  }, []);

  // Сохраняем изменения формы
  React.useEffect(() => {
    const subscription = form.watch((value) => {
      FormStorage.saveFormState('create_product', {
        ...value,
        currentTab: document.querySelector('[data-state="active"]')?.getAttribute('data-value') || 'basic'
      });
    });
    return () => subscription.unsubscribe();
  }, [form.watch]);

  const clearErrors = () => {
    setFormError(null);
    setFieldErrors({});
  };

  const handleSubmit = async (data: ProductFormData) => {
    try {
      clearErrors();
      await onSubmit(data);
    } catch (error) {
      if (error instanceof ApiError && error.errors) {
        setFieldErrors(error.errors);
        // Переключаемся на вкладку с первой ошибкой
        const firstErrorField = Object.keys(error.errors)[0];
        const tabMapping: Record<string, string> = {
          name: 'basic',
          description: 'basic',
          sku: 'basic',
          category_id: 'basic',
          images: 'images',
          price: 'pricing',
          attributes: 'attributes'
        };
        const targetTab = tabMapping[firstErrorField] || 'basic';
        const tabsTrigger = document.querySelector(`[data-value="${targetTab}"]`) as HTMLElement;
        if (tabsTrigger) {
          tabsTrigger.click();
        }
      } else {
        setFormError(error instanceof Error ? error.message : 'Произошла неизвестная ошибка');
      }
    }
  };

  // Очищаем сохраненное состояние при размонтировании
  React.useEffect(() => {
    return () => {
      FormStorage.clearFormState('create_product');
    };
  }, []);

  const handleSaveDraft = async () => {
    try {
      clearErrors();
      const isValid = await form.trigger();
      if (!isValid) return;
      
      const data = form.getValues();
      data.status = 'draft';
      await onSubmit(data);
    } catch (error) {
      if (error instanceof ApiError && error.errors) {
        setFieldErrors(error.errors);
      } else {
        setFormError(error instanceof Error ? error.message : 'Произошла неизвестная ошибка');
      }
    }
  };

  const handlePublish = async () => {
    try {
      clearErrors();
      if (form.formState.isSubmitting) return;
      
      const isValid = await form.trigger();
      if (!isValid) {
        setFormError('Пожалуйста, исправьте ошибки в форме перед публикацией');
        return;
      }
      
      const data = form.getValues();
      data.status = 'published';
      
      await onSubmit(data);
    } catch (error) {
      if (error instanceof ApiError && error.errors) {
        setFieldErrors(error.errors);
      } else {
        setFormError(error instanceof Error ? error.message : 'Произошла неизвестная ошибка');
      }
    }
  };

  return (
    <div className="product-form">
      <Form {...form}>
        <form
          onSubmit={async (e) => {
            e.preventDefault();
            await form.handleSubmit(handleSubmit)(e);
          }}
          className={isSubmitting ? 'opacity-50 pointer-events-none' : ''}
        >
          <div className="space-y-6">
            {formError && (
              <div className="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-4">
                <p className="text-sm font-medium">{formError}</p>
              </div>
            )}

            <Card>
              <CardHeader>
                <CardTitle>Создание товара</CardTitle>
              </CardHeader>
              <CardContent>
                <Tabs
                  defaultValue={FormStorage.loadFormState('create_product')?.currentTab || 'basic'}
                  className="w-full"
                  onValueChange={(value) => {
                    const formData = form.getValues();
                    const currentState = FormStorage.loadFormState('create_product') || {};
                    FormStorage.saveFormState('create_product', {
                      ...currentState,
                      ...formData,
                      currentTab: value
                    });
                  }}
                >
                  <TabsList className="tab-list">
                    <TabsTrigger value="basic" className="tab-trigger">
                      Основное
                      {Object.keys(form.formState.errors).some(key => ['name', 'description', 'sku', 'category_id'].includes(key)) && (
                        <span className="ml-2 text-red-500">●</span>
                      )}
                    </TabsTrigger>
                    <TabsTrigger value="images" className="tab-trigger">
                      Изображения
                      {form.formState.errors.images && (
                        <span className="ml-2 text-red-500">●</span>
                      )}
                    </TabsTrigger>
                    <TabsTrigger value="pricing" className="tab-trigger">
                      Цены
                      {form.formState.errors.price && (
                        <span className="ml-2 text-red-500">●</span>
                      )}
                    </TabsTrigger>
                    <TabsTrigger value="attributes" className="tab-trigger">
                      Характеристики
                      {form.formState.errors.attributes && (
                        <span className="ml-2 text-red-500">●</span>
                      )}
                    </TabsTrigger>
                    <TabsTrigger value="eco-features" className="tab-trigger">
                      Эко-характеристики
                      {form.formState.errors.eco_features && (
                        <span className="ml-2 text-red-500">●</span>
                      )}
                    </TabsTrigger>
                  </TabsList>
                  
                  <TabsContent value="basic" className="space-y-4 mt-4">
                    <FormField
                      control={form.control}
                      name="name"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Название товара</FormLabel>
                          <FormControl>
                            <Input 
                              placeholder="Введите название товара" 
                              {...field} 
                              className="form-input"
                              error={!!fieldErrors.name}
                            />
                          </FormControl>
                          <FormMessage />
                          {fieldErrors.name && (
                            <p className="text-sm text-red-500">{fieldErrors.name.join(', ')}</p>
                          )}
                        </FormItem>
                      )}
                    />

                    <FormField
                      control={form.control}
                      name="description"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Описание</FormLabel>
                          <FormControl>
                            <Textarea
                              placeholder="Введите описание товара"
                              className="min-h-[120px]"
                              {...field}
                              error={!!fieldErrors.description}
                            />
                          </FormControl>
                          <FormMessage />
                          {fieldErrors.description && (
                            <p className="text-sm text-red-500">{fieldErrors.description.join(', ')}</p>
                          )}
                        </FormItem>
                      )}
                    />

                    <div className="grid grid-cols-2 gap-4">
                      <FormField
                        control={form.control}
                        name="sku"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Артикул (SKU)</FormLabel>
                            <FormControl>
                              <Input 
                                placeholder="Введите артикул" 
                                {...field} 
                                className="form-input"
                                error={!!fieldErrors.sku}
                              />
                            </FormControl>
                            <FormMessage />
                            {fieldErrors.sku && (
                              <p className="text-sm text-red-500">{fieldErrors.sku.join(', ')}</p>
                            )}
                          </FormItem>
                        )}
                      />

                      <FormField
                        control={form.control}
                        name="category_id"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Категория</FormLabel>
                            <FormControl>
                              <Select
                                onValueChange={(value) => field.onChange(value)}
                                value={field.value}
                                error={!!fieldErrors.category_id}
                                required
                                type="number"
                              >
                                <SelectTrigger>
                                  <SelectValue placeholder="Выберите категорию" />
                                </SelectTrigger>
                                <SelectContent>
                                  {categories.map((category) => (
                                    <SelectItem
                                      key={category.id}
                                      value={category.id.toString()}
                                    >
                                      {category.name}
                                    </SelectItem>
                                  ))}
                                </SelectContent>
                              </Select>
                            </FormControl>
                            <FormMessage />
                            {fieldErrors.category_id && (
                              <p className="text-sm text-red-500">{fieldErrors.category_id.join(', ')}</p>
                            )}
                          </FormItem>
                        )}
                      />
    
                      <FormField
                        control={form.control}
                        name="is_featured"
                        render={({ field }) => (
                          <FormItem className="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4">
                            <FormControl>
                              <input
                                type="checkbox"
                                checked={field.value}
                                onChange={(e) => field.onChange(e.target.checked)}
                                className="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-600"
                              />
                            </FormControl>
                            <div className="space-y-1 leading-none">
                              <FormLabel>Избранный товар</FormLabel>
                              <p className="text-sm text-gray-600">
                                Отметьте, чтобы показать этот товар в разделе "Избранные товары"
                              </p>
                            </div>
                          </FormItem>
                        )}
                      />
                    </div>
                  </TabsContent>
                  
                  <TabsContent value="images">
                    <ImageUpload 
                      maxFiles={5} 
                      maxSize={5242880} 
                      form={form}
                      error={fieldErrors.images?.[0]}
                    />
                  </TabsContent>
                  
                  <TabsContent value="pricing">
                    <PriceManager
                      form={form}
                      error={fieldErrors.price?.[0]}
                      onPriceChange={(price) => {
                        if (fieldErrors.price) {
                          const newErrors = { ...fieldErrors };
                          delete newErrors.price;
                          setFieldErrors(newErrors);
                        }
                      }}
                    />
                  </TabsContent>
                  
                  <TabsContent value="attributes">
                    <AttributesManager
                      form={form}
                      attributes={attributes}
                      error={fieldErrors.attributes?.[0]}
                      onAttributeChange={(attr) => {
                        if (fieldErrors.attributes) {
                          const newErrors = { ...fieldErrors };
                          delete newErrors.attributes;
                          setFieldErrors(newErrors);
                        }
                      }}
                    />
                  </TabsContent>

                  <TabsContent value="eco-features">
                    <EcoFeaturesManager
                      form={form}
                      ecoFeatures={ecoFeatures}
                      error={fieldErrors.eco_features?.[0]}
                      onEcoFeatureChange={(feature) => {
                        if (fieldErrors.eco_features) {
                          const newErrors = { ...fieldErrors };
                          delete newErrors.eco_features;
                          setFieldErrors(newErrors);
                        }
                      }}
                    />
                  </TabsContent>
                </Tabs>
              </CardContent>
            </Card>

            <div>
              {/* Показываем общие ошибки формы */}
              {Object.keys(form.formState.errors).length > 0 && (
                <div className="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-4">
                  <h4 className="text-sm font-medium mb-2">Пожалуйста, исправьте следующие ошибки:</h4>
                  <ul className="list-disc pl-5">
                    {Object.entries(form.formState.errors).map(([key, error]) => (
                      <li key={key} className="text-sm">
                        {error.message}
                      </li>
                    ))}
                  </ul>
                </div>
              )}
              <StatusBar
                form={form}
                onSaveDraft={handleSaveDraft}
                onPublish={handlePublish}
                isSubmitting={isSubmitting}
                error={formError}
              />
            </div>
          </div>
        </form>
      </Form>

      {isSubmitting && (
        <div className="loading-overlay">
          <div className="spinner"></div>
        </div>
      )}
    </div>
  )
}