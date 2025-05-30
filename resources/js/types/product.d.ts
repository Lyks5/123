import { UseFormReturn } from 'react-hook-form';

interface Product {
    id?: number;
    name: string;
    description: string;
    sku: string;
    price: number;
    compare_at_price?: number;
    category_id: number;
    status: 'draft' | 'published' | 'archived';
    is_featured: boolean;
    attributes: ProductAttribute[];
    images: ProductImage[];
    eco_features: EcoFeature[];
}

interface ProductAttribute {
    attribute_id: number;
    value: string;
}

interface ProductImage {
    id?: number;
    url: string;
    order: number;
}

interface Category {
    id: number;
    name: string;
    parent_id: number | null;
}

interface Attribute extends ProductAttribute {
    id: number;
    name: string;
    type: 'text' | 'number' | 'select';
    options?: string[];
}

interface EcoFeature {
    id: number;
    name: string;
    description: string;
    icon?: string;
    value?: string;
    category?: string;
    unit?: string;
}

type ProductFormData = Omit<Product, 'id'>;

interface ProductFormProps {
    product?: Product;
    categories: Category[];
    attributes: Attribute[];
    ecoFeatures: EcoFeature[];
    onSubmit: (data: ProductFormData) => Promise<void>;
    isSubmitting?: boolean;
}

interface ImageUploadProps {
    form: UseFormReturn<ProductFormData>;
    maxFiles: number;
    maxSize: number;
}

interface PriceManagerProps {
    form: UseFormReturn<ProductFormData>;
    onPriceChange?: (price: number) => void;
}

interface AttributesManagerProps {
    form: UseFormReturn<ProductFormData>;
    attributes: Attribute[];
    onAttributeChange?: (attribute: ProductAttribute) => void;
}

interface StatusBarProps {
    form: UseFormReturn<ProductFormData>;
    onSaveDraft: () => Promise<void>;
    onPublish: () => Promise<void>;
    isSubmitting?: boolean;
}

// API типы
export interface ApiProduct extends Product {
    id: number;
    created_at: string;
    updated_at: string;
}

export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
}

export interface ApiSuccessResponse<T> {
    status: 'success';
    data: T;
}

export interface ApiErrorResponse {
    status: 'error';
    message: string;
    errors?: Record<string, string[]>;
}

export type ApiResponse<T = any> = ApiSuccessResponse<T> | ApiErrorResponse;

export { 
    Product,
    ProductAttribute,
    ProductImage,
    ProductFormData,
    Category,
    Attribute,
    EcoFeature,
    ProductFormProps,
    ImageUploadProps,
    PriceManagerProps,
    AttributesManagerProps,
    StatusBarProps
};