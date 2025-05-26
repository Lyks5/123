import React from 'react';
import { UseFormReturn } from 'react-hook-form';
import { Icons } from '@/lib/icons';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { ProductFormData } from '@/types/product';

export interface StatusBarProps {
  form: UseFormReturn<ProductFormData>;
  onSaveDraft?: () => Promise<void>;
  onPublish?: () => Promise<void>;
  isSubmitting?: boolean;
  error?: string | null;
}

export function StatusBar({ 
  form, 
  onSaveDraft, 
  onPublish, 
  isSubmitting,
  error 
}: StatusBarProps) {
  const [isSaving, setIsSaving] = React.useState(false);
  const [showValidationSummary, setShowValidationSummary] = React.useState(false);
  
  // Очищаем сообщение об ошибках при изменении формы
  React.useEffect(() => {
    const subscription = form.watch(() => {
      setShowValidationSummary(false);
    });
    return () => subscription.unsubscribe();
  }, [form.watch]);

  const handleSaveDraft = async () => {
    if (!onSaveDraft) return;
    setIsSaving(true);
    try {
      await onSaveDraft();
    } finally {
      setIsSaving(false);
    }
  };

  const handlePublish = async () => {
    if (!onPublish) return;
    
    // Показываем все ошибки валидации при попытке публикации
    const isValid = await form.trigger();
    if (!isValid) {
      setShowValidationSummary(true);
      return;
    }
    
    await onPublish();
  };

  const errorCount = Object.keys(form.formState.errors).length;

  return (
    <div className="fixed bottom-0 left-0 right-0 border-t bg-white shadow-lg">
      {/* Панель ошибок */}
      {(showValidationSummary || error) && (
        <div className="border-b border-red-200 bg-red-50 px-4 py-3">
          <div className="mx-auto max-w-7xl">
            {error && (
              <div className="mb-2 text-sm text-red-800">
                <Icons.error className="inline-block w-4 h-4 mr-1" />
                {error}
              </div>
            )}
            {showValidationSummary && errorCount > 0 && (
              <div>
                <p className="text-sm font-medium text-red-800 mb-2">
                  Пожалуйста, исправьте следующие ошибки:
                </p>
                <ul className="list-disc list-inside text-sm text-red-700">
                  {Object.entries(form.formState.errors).map(([field, error]) => (
                    <li key={field}>
                      {error.message}
                    </li>
                  ))}
                </ul>
              </div>
            )}
          </div>
        </div>
      )}

      {/* Панель действий */}
      <div className="p-4">
        <div className="mx-auto max-w-7xl flex justify-between items-center">
          <div className="flex items-center space-x-4">
            {form.formState.isDirty && (
              <span className="text-sm text-yellow-600">
                <Icons.error className="inline-block w-4 h-4 mr-1" />
                Есть несохраненные изменения
              </span>
            )}
            {errorCount > 0 && (
              <button
                onClick={() => setShowValidationSummary(!showValidationSummary)}
                className="text-sm text-red-600 hover:text-red-800 focus:outline-none"
              >
                <Icons.error className="inline-block w-4 h-4 mr-1" />
                {errorCount} {errorCount === 1 ? 'ошибка' : 'ошибки'}
              </button>
            )}
          </div>
          <div className="flex space-x-4">
            <Button
              variant="outline"
              onClick={handleSaveDraft}
              disabled={isSaving || !form.formState.isDirty || isSubmitting}
              className={cn(
                "min-w-[160px]",
                (isSaving || isSubmitting) && "opacity-50"
              )}
            >
              {(isSaving || isSubmitting) ? (
                <>
                  <Icons.spinner className="mr-2 h-4 w-4 animate-spin" />
                  Сохранение...
                </>
              ) : (
                <>
                  <Icons.save className="mr-2 h-4 w-4" />
                  Сохранить черновик
                </>
              )}
            </Button>
            <Button
              onClick={handlePublish}
              disabled={!form.formState.isValid || isSaving || isSubmitting}
              className={cn(
                "min-w-[160px]",
                !form.formState.isValid && "opacity-50"
              )}
            >
              {isSubmitting ? (
                <>
                  <Icons.spinner className="mr-2 h-4 w-4 animate-spin" />
                  Публикация...
                </>
              ) : (
                <>
                  <Icons.success className="mr-2 h-4 w-4" />
                  Опубликовать
                </>
              )}
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}