import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { CreateProduct } from './pages/products/CreateProduct';
import { EditProduct } from './pages/products/EditProduct';

// Инициализация страницы создания продукта
const createProductElement = document.getElementById('create-product-root');
if (createProductElement) {
    const root = createRoot(createProductElement);
    root.render(
        <React.StrictMode>
            <CreateProduct />
        </React.StrictMode>
    );
}

// Инициализация страницы редактирования продукта
const editProductElement = document.getElementById('edit-product-root');
if (editProductElement) {
    const productId = editProductElement.dataset.productId;
    const root = createRoot(editProductElement);
    root.render(
        <React.StrictMode>
            <EditProduct productId={parseInt(productId || '0')} />
        </React.StrictMode>
    );
}