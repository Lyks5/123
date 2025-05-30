import React from 'react'
import { UseFormReturn } from 'react-hook-form'
import { FormField, FormItem, FormLabel, FormControl, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { EcoFeature, ProductFormData } from '@/types/product'

interface EcoFeaturesManagerProps {
  form: UseFormReturn<ProductFormData>;
  ecoFeatures: EcoFeature[];
  onEcoFeatureChange?: (feature: EcoFeature) => void;
  error?: string;
}

export function EcoFeaturesManager({ form, ecoFeatures, onEcoFeatureChange, error }: EcoFeaturesManagerProps) {
  // Группируем характеристики по категориям
  const groupedFeatures = ecoFeatures.reduce((acc, feature) => {
    const category = feature.category || 'Общие';
    if (!acc[category]) {
      acc[category] = [];
    }
    acc[category].push(feature);
    return acc;
  }, {} as Record<string, EcoFeature[]>);

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h3 className="text-lg font-medium">Эко-характеристики</h3>
        <div className="text-sm text-green-600">
          Заполнено: {ecoFeatures.filter(f => f.value).length}/{ecoFeatures.length}
        </div>
      </div>
      
      {error && (
        <div className="text-sm text-red-500 bg-red-50 p-3 rounded-lg">{error}</div>
      )}

      <div className="space-y-6">
        {Object.entries(groupedFeatures).map(([category, features]) => (
          <div key={category} className="space-y-4">
            <h4 className="text-sm font-semibold text-gray-700">{category}</h4>
            <div className="grid gap-4 border border-gray-100 rounded-lg p-4 bg-gray-50">
              {features.map((feature) => (
                <FormField
                  key={feature.id}
                  control={form.control}
                  name={`eco_features.${feature.id}`}
                  render={({ field }) => (
                    <FormItem className="relative">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center">
                          <FormLabel>
                            {feature.icon && (
                              <span className="mr-2 text-green-600">{feature.icon}</span>
                            )}
                            {feature.name}
                          </FormLabel>
                        </div>
                        {feature.unit && (
                          <span className="text-xs text-gray-500">{feature.unit}</span>
                        )}
                      </div>
                      <FormControl>
                        <div className="flex gap-2">
                          <Input
                            placeholder="Введите значение"
                            {...field}
                            onChange={(e) => {
                              field.onChange(e.target.value);
                              onEcoFeatureChange?.({
                                ...feature,
                                value: e.target.value
                              });
                            }}
                            className="flex-1"
                          />
                          {field.value && Number(field.value) > 0 && (
                            <div className="absolute right-2 top-8 flex items-center">
                              <div className="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div
                                  className="h-full bg-green-500 transition-all duration-300"
                                  style={{ width: `${Math.min(Number(field.value), 100)}%` }}
                                />
                              </div>
                            </div>
                          )}
                        </div>
                      </FormControl>
                      {feature.description && (
                        <p className="text-sm text-gray-500 mt-1">{feature.description}</p>
                      )}
                      <FormMessage />
                    </FormItem>
                  )}
                />
              ))}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}