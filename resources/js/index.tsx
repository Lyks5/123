import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { CreateProduct } from './pages/products/CreateProduct';

console.log('React initialization started');

document.addEventListener('DOMContentLoaded', () => {
    const createProductElement = document.getElementById('create-product-root');
    console.log('Found element:', createProductElement);
    
    if (createProductElement) {
        const root = createRoot(createProductElement);
        root.render(
            <React.StrictMode>
                <CreateProduct />
            </React.StrictMode>
        );
        console.log('React component mounted');
    }
});