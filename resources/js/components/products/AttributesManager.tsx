import React from 'react';
import { UseFormReturn } from 'react-hook-form';
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import {
  FormField,
  FormItem,
  FormLabel,
  FormControl,
  FormMessage,
} from '../ui/form';
import { Input } from '../ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Icons } from '@/lib/icons';
import { cn } from '@/lib/utils';
import { ProductFormData, Attribute, ProductAttribute } from '@/types/product';

export interface AttributesManagerProps {
  form: UseFormReturn<ProductFormData>;
  attributes: Attribute[];
  onAttributeChange?: (attribute: ProductAttribute) => void;
  error?: string;
}

export function AttributesManager({ form, attributes, onAttributeChange, error }: AttributesManagerProps) {
  const formAttributes = form.watch('attributes') || [];
  
  // Сохраняем текущие значения в localStorage при изменении
  React.useEffect(() => {
    const subscription = form.watch((value) => {
      if (value.attributes) {
        localStorage.setItem('product_attributes', JSON.stringify(value.attributes));
      }
    });
    return () => subscription.unsubscribe();
  }, [form.watch]);

  // Загружаем сохраненные значения при монтировании
  React.useEffect(() => {
    const savedAttributes = localStorage.getItem('product_attributes');
    if (savedAttributes) {
      try {
        const parsedAttributes = JSON.parse(savedAttributes);
        form.setValue('attributes', parsedAttributes, {
          shouldValidate: true,
          shouldDirty: true
        });
      } catch (e) {
        console.error('Ошибка при загрузке сохраненных характеристик:', e);
      }
    }
  }, []);

  const addAttribute = () => {
    const currentAttributes = form.getValues('attributes') || [];
    
    const usedAttributeIds = new Set(currentAttributes.map((attr: ProductAttribute) => attr.attribute_id));
    const availableAttributes = attributes.filter((attr: Attribute) => !usedAttributeIds.has(attr.id));
    
    if (availableAttributes.length === 0) {
      alert('Все доступные характеристики уже добавлены');
      return;
    }
    
    const firstAvailableAttribute = availableAttributes[0];
    
    form.setValue('attributes', [
      ...currentAttributes,
      { attribute_id: firstAvailableAttribute.id, value: '' }
    ], { shouldDirty: true, shouldValidate: true });
  };

  const removeAttribute = (index: number) => {
    const currentAttributes = form.getValues('attributes') || [];
    currentAttributes.splice(index, 1);
    form.setValue('attributes', currentAttributes, { 
      shouldDirty: true,
      shouldValidate: true
    });
  };

  const handleAttributeChange = (value: string | number | undefined, index: number) => {
    if (typeof value === 'string' || typeof value === 'number') {
      const stringValue = value.toString();
      const attributeId = parseInt(stringValue, 10);
      if (!isNaN(attributeId)) {
        const currentValue = form.getValues(`attributes.${index}.value`);
        form.setValue(`attributes.${index}.attribute_id`, attributeId, {
          shouldValidate: true
        });
        
        if (onAttributeChange) {
          onAttributeChange({
            attribute_id: attributeId,
            value: currentValue || ''
          });
        }
      }
    }
  };

  return (
    <Card className={cn(error && "border-red-500")}>
      <CardHeader>
        <CardTitle className="flex items-center justify-between">
          <div className="flex items-center">
            <Icons.attributes className={cn(
              "mr-2 h-4 w-4",
              error ? "text-red-500" : "text-foreground"
            )} />
            Характеристики товара
          </div>
          <Button
            type="button"
            variant="outline"
            size="sm"
            onClick={addAttribute}
            className="ml-auto"
          >
            <Icons.add className="mr-2 h-4 w-4" />
            Добавить характеристику
          </Button>
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        {error && (
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-4">
            <p className="text-sm">{error}</p>
          </div>
        )}

        {formAttributes.map((_: ProductAttribute, index: number) => (
          <div key={index} className="flex gap-4 items-start">
            <FormField
              control={form.control}
              name={`attributes.${index}.attribute_id`}
              render={({ field }) => (
                <FormItem className="flex-1">
                  <FormLabel>Характеристика</FormLabel>
                  <Select
                    value={field.value?.toString()}
                    onValueChange={(value) => handleAttributeChange(value, index)}
                  >
                    <FormControl>
                      <SelectTrigger className={cn(error && "border-red-500")}>
                        <SelectValue placeholder="Выберите характеристику" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent>
                      {attributes
                        .filter((attribute: Attribute) => {
                          const otherAttributes = form.getValues('attributes') || [];
                          const usedIds = new Set(
                            otherAttributes
                              .filter((_: ProductAttribute, i: number) => i !== index)
                              .map((attr: ProductAttribute) => attr.attribute_id)
                          );
                          return !usedIds.has(attribute.id) || attribute.id === field.value;
                        })
                        .map((attribute: Attribute) => (
                          <SelectItem
                            key={attribute.id}
                            value={attribute.id.toString()}
                          >
                            {attribute.name}
                          </SelectItem>
                        ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name={`attributes.${index}.value`}
              render={({ field }) => (
                <FormItem className="flex-1">
                  <FormLabel>Значение</FormLabel>
                  <FormControl>
                    <Input
                      {...field}
                      placeholder="Введите значение"
                      className={cn(error && "border-red-500")}
                      onChange={(e) => {
                        field.onChange(e);
                        
                        const attributeId = form.getValues(`attributes.${index}.attribute_id`);
                        if (onAttributeChange && attributeId) {
                          onAttributeChange({
                            attribute_id: attributeId,
                            value: e.target.value
                          });
                        }
                      }}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <Button
              type="button"
              variant="destructive"
              size="icon"
              className="mt-8"
              onClick={() => removeAttribute(index)}
            >
              <Icons.delete className="h-4 w-4" />
            </Button>
          </div>
        ))}

        {formAttributes.length === 0 && (
          <div className="text-center py-8 text-muted-foreground">
            <Icons.attributes className="mx-auto h-12 w-12 opacity-20" />
            <p className="mt-2">Нет добавленных характеристик</p>
          </div>
        )}
      </CardContent>
    </Card>
  );
}