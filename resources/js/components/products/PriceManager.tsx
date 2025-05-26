import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
  FormField,
  FormItem,
  FormLabel,
  FormControl,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Icons } from '@/lib/icons';
import { cn } from '@/lib/utils';
import { ProductFormData } from '@/types/product';
import { UseFormReturn } from 'react-hook-form';

export interface PriceManagerProps {
  form: UseFormReturn<ProductFormData>;
  onPriceChange?: (price: number) => void;
  error?: string;
}

const formatPrice = (value: number): string => {
  return new Intl.NumberFormat('ru-RU', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(value);
};

const validatePrice = (value: number): boolean => {
  return !isNaN(value) && value >= 0 && value <= 999999999.99;
};

export function PriceManager({ form, onPriceChange, error }: PriceManagerProps) {
  const basePrice = form.watch('price') ?? 0;
  const comparePrice = form.watch('compare_at_price') ?? 0;

  // Сохраняем цены в localStorage при изменении
  React.useEffect(() => {
    const subscription = form.watch((value) => {
      if (value.price !== undefined || value.compare_at_price !== undefined) {
        localStorage.setItem('product_prices', JSON.stringify({
          price: value.price,
          compare_at_price: value.compare_at_price
        }));
      }
    });
    return () => subscription.unsubscribe();
  }, [form.watch]);

  // Загружаем сохраненные цены при монтировании
  React.useEffect(() => {
    const savedPrices = localStorage.getItem('product_prices');
    if (savedPrices) {
      try {
        const { price, compare_at_price } = JSON.parse(savedPrices);
        if (validatePrice(price)) {
          form.setValue('price', price, { shouldValidate: true });
        }
        if (validatePrice(compare_at_price)) {
          form.setValue('compare_at_price', compare_at_price, { shouldValidate: true });
        }
      } catch (e) {
        console.error('Ошибка при загрузке сохраненных цен:', e);
      }
    }
  }, []);

  const handlePriceChange = (value: string, field: any) => {
    try {
      console.log('Price change:', {
        fieldName: field.name,
        oldValue: field.value,
        newValue: value
      });

      let numericValue = value === '' ? 0 : parseFloat(value);
      
      if (!validatePrice(numericValue)) {
        console.warn('Invalid price value:', value);
        numericValue = 0;
      }

      form.setValue(field.name, numericValue, {
        shouldValidate: true,
        shouldDirty: true,
        shouldTouch: true
      });
      
      if (onPriceChange && field.name === 'price') {
        onPriceChange(numericValue);
      }
      
      // Валидация соотношения цен
      if (field.name === 'price') {
        const currentComparePrice = form.getValues('compare_at_price') ?? 0;
        if (currentComparePrice > 0 && numericValue >= currentComparePrice) {
          form.setError('compare_at_price', {
            type: 'manual',
            message: 'Старая цена должна быть выше базовой',
          });
        } else {
          form.clearErrors('compare_at_price');
        }
      } else if (field.name === 'compare_at_price') {
        const currentBasePrice = form.getValues('price') ?? 0;
        if (numericValue > 0 && numericValue <= currentBasePrice) {
          form.setError('compare_at_price', {
            type: 'manual',
            message: 'Старая цена должна быть выше базовой',
          });
        } else {
          form.clearErrors('compare_at_price');
        }
      }

      console.log('Price updated:', {
        fieldName: field.name,
        newValue: numericValue,
        formValues: form.getValues()
      });

    } catch (error) {
      console.error('Error updating price:', error);
      form.setError(field.name, {
        type: 'manual',
        message: 'Ошибка при обновлении цены'
      });
    }
  };

  return (
    <Card className={cn(error && "border-red-500")}>
      <CardHeader>
        <CardTitle className="flex items-center">
          <Icons.price className={cn(
            "mr-2 h-4 w-4",
            error ? "text-red-500" : "text-foreground"
          )} />
          Управление ценами
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        {error && (
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-4">
            <p className="text-sm">{error}</p>
          </div>
        )}

        <FormField
          control={form.control}
          name="price"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Базовая цена</FormLabel>
              <FormControl>
                <div className="relative">
                  <Input
                    type="number"
                    step="0.01"
                    min="0"
                    max="999999999.99"
                    placeholder="0.00"
                    {...field}
                    onChange={(e) => handlePriceChange(e.target.value, field)}
                    className={cn("pl-7", error && "border-red-500")}
                  />
                  <span className="absolute left-2 top-1/2 -translate-y-1/2 text-muted-foreground">
                    ₽
                  </span>
                </div>
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        <FormField
          control={form.control}
          name="compare_at_price"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Старая цена</FormLabel>
              <FormControl>
                <div className="relative">
                  <Input
                    type="number"
                    step="0.01"
                    min="0"
                    max="999999999.99"
                    placeholder="0.00"
                    {...field}
                    onChange={(e) => handlePriceChange(e.target.value, field)}
                    className={cn("pl-7", error && "border-red-500")}
                  />
                  <span className="absolute left-2 top-1/2 -translate-y-1/2 text-muted-foreground">
                    ₽
                  </span>
                </div>
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        {comparePrice > 0 && basePrice > 0 && comparePrice > basePrice && (
          <div className="p-3 bg-muted rounded-lg">
            <p className="text-sm text-muted-foreground">
              Скидка: {Math.round(((comparePrice - basePrice) / comparePrice) * 100)}%
              {' '}
              <span className="font-medium">
                (-{formatPrice(comparePrice - basePrice)} ₽)
              </span>
            </p>
          </div>
        )}
      </CardContent>
    </Card>
  );
}