import type { ProductFormData } from './validations/product';

export interface ApiResponse<T = any> {
  status: 'success' | 'error';
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export class ApiError extends Error {
  status?: number;
  errors?: Record<string, string[]>;

  constructor(message: string, status?: number, errors?: Record<string, string[]>) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.errors = errors;
  }
}

const getCsrfToken = () =>
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const getApiUrl = () =>
  document.querySelector('meta[name="api-url"]')?.getAttribute('content') || '/api';

const getBaseUrl = () => {
  const baseUrlMeta = document.querySelector('meta[name="app-url"]');
  return baseUrlMeta ? baseUrlMeta.getAttribute('content') : '';
};

const TIMEOUT = 30000; // 30 секунд

async function apiRequest<T>(
  url: string,
  method: string = 'GET',
  data?: any
): Promise<ApiResponse<T>> {
  const apiUrl = getApiUrl();
  const fullUrl = `${apiUrl}${url.startsWith('/') ? url : '/' + url}`;
  
  const headers: Record<string, string> = {
    'X-CSRF-TOKEN': getCsrfToken(),
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
  };

  if (!(data instanceof FormData)) {
    headers['Content-Type'] = 'application/json';
  }

  try {
    console.log('API Request:', { url: fullUrl, method, data });

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), TIMEOUT);

    const response = await fetch(fullUrl, {
      method,
      headers,
      credentials: 'include',
      body: data instanceof FormData ? data : JSON.stringify(data),
      signal: controller.signal
    });

    clearTimeout(timeoutId);

    const responseData = await response.json();
    console.log('API Response:', responseData);

    if (!response.ok) {
      throw new ApiError(
        responseData.message || `Ошибка сервера: ${response.status}`,
        response.status,
        responseData.errors
      );
    }

    return responseData;
  } catch (error) {
    console.error('API Error:', error);

    if (error instanceof Error) {
      if (error.name === 'AbortError') {
        throw new ApiError('Превышено время ожидания ответа от сервера');
      }
      
      if (!navigator.onLine) {
        throw new ApiError('Отсутствует подключение к интернету');
      }

      if (error instanceof TypeError && error.message === 'Failed to fetch') {
        throw new ApiError('Не удалось подключиться к серверу. Проверьте подключение к интернету');
      }

      if (error instanceof ApiError) {
        throw error;
      }

      throw new ApiError(error.message);
    }

    throw new ApiError('Произошла неизвестная ошибка');
  }
}

export async function createProduct(data: ProductFormData): Promise<ApiResponse> {
  return apiRequest('/admin/products', 'POST', data);
}

export async function updateProduct(
  id: number,
  data: ProductFormData
): Promise<ApiResponse> {
  return apiRequest(`/admin/products/${id}`, 'PUT', data);
}

export async function getProduct(id: number): Promise<ApiResponse> {
  return apiRequest(`/admin/products/${id}`);
}

export async function uploadProductImage(file: File): Promise<ApiResponse> {
  const formData = new FormData();
  formData.append('image', file);
  return apiRequest('/admin/products/upload-image', 'POST', formData);
}

export async function deleteProductImage(imageId: number): Promise<ApiResponse> {
  return apiRequest(`/admin/products/images/${imageId}`, 'DELETE');
}

export async function saveProductDraft(
  id: number | null,
  data: ProductFormData
): Promise<ApiResponse> {
  const url = id
    ? `/admin/products/${id}/draft`
    : '/admin/products/draft';
  return apiRequest(url, id ? 'PUT' : 'POST', data);
}

export async function publishProduct(id: number): Promise<ApiResponse> {
  return apiRequest(`/admin/products/${id}/publish`, 'POST');
}