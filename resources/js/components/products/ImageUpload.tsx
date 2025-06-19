import React from 'react';
import { UseFormReturn } from 'react-hook-form';
import { useDropzone } from 'react-dropzone';
import { Icons } from '@/lib/icons';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { cn } from '@/lib/utils';

export interface ImageUploadProps {
  form: UseFormReturn<any>;
  maxFiles?: number;
  maxSize?: number;
  error?: string;
}

interface UploadedImage {
  id: number;
  url: string;
  order: number;
}

export function ImageUpload({ form, maxFiles = 8, maxSize = 5242880, error }: ImageUploadProps) {
  const [isUploading, setIsUploading] = React.useState(false);
  const images = form.watch('images') as UploadedImage[] || [];

  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    accept: {
      'image/*': []
    },
    maxFiles,
    maxSize,
    onDrop: async (acceptedFiles) => {
      setIsUploading(true);
      try {
        const formData = new FormData();
        acceptedFiles.forEach((file, index) => {
          formData.append(`images[]`, file);
        });

        const response = await fetch('/api/products/upload-images', {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        });

        if (!response.ok) {
          throw new Error('Ошибка загрузки изображений');
        }

        const uploadedImages = await response.json();
        form.setValue('images', [...images, ...uploadedImages], {
          shouldDirty: true,
          shouldValidate: true
        });
      } finally {
        setIsUploading(false);
      }
    }
  });

  const removeImage = (index: number) => {
    const newImages = [...images];
    newImages.splice(index, 1);
    form.setValue('images', newImages, { 
      shouldDirty: true,
      shouldValidate: true 
    });
  };

  return (
    <Card className={cn(error && "border-red-500")}>
      <CardContent className="p-6 space-y-4">
        <div
          {...getRootProps()}
          className={cn(
            "border-2 border-dashed rounded-lg p-12 text-center transition-colors",
            isDragActive ? "border-primary bg-primary/5" : error ? "border-red-500" : "border-border",
            isUploading && "opacity-50 cursor-not-allowed"
          )}
        >
          <input {...getInputProps()} />
          <div className="flex flex-col items-center justify-center">
            {isUploading ? (
              <Icons.spinner className="h-12 w-12 text-muted-foreground animate-spin" />
            ) : (
              <Icons.upload className={cn(
                "h-12 w-12",
                error ? "text-red-500" : "text-muted-foreground"
              )} />
            )}
            <p className={cn(
              "mt-2 text-sm",
              error ? "text-red-500" : "text-muted-foreground"
            )}>
              {isDragActive
                ? "Перетащите файлы сюда"
                : "Перетащите изображения сюда или кликните для выбора"}
            </p>
          </div>
        </div>

        {error && (
          <p className="text-sm text-red-500 mt-2">{error}</p>
        )}

        {images.length > 0 && (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            {images.map((image: UploadedImage, index: number) => (
              <div key={image.id || index} className="relative group">
                <img
                  src={image.url}
                  alt={`Изображение ${index + 1}`}
                  className="w-full aspect-square object-cover rounded-lg"
                />
                <Button
                  variant="destructive"
                  size="icon"
                  className="absolute top-2 right-2 hidden group-hover:flex"
                  onClick={() => removeImage(index)}
                >
                  <Icons.close className="h-4 w-4" />
                </Button>
                <div className="absolute inset-x-0 bottom-0 bg-black/50 text-white text-xs py-1 px-2 rounded-b-lg">
                  {index + 1} из {images.length}
                </div>
              </div>
            ))}
          </div>
        )}
      </CardContent>
    </Card>
  );
}