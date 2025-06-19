export interface Product {
  id: number;
  name: string;
  price: number;
  category: string;
  inStock: boolean;
}

export interface FilterValues {
  priceMin: number;
  priceMax: number;
  category: string[];
  inStock: boolean;
}
