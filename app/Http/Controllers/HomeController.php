<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController
{
    public function index()
    {
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'Экологичный коврик для йоги',
                'price' => 68.99,
                'image' => 'https://images.unsplash.com/photo-1554284126-aa88f22d8b74?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'category' => 'Йога',
                'eco_feature' => 'Натуральный каучук',
                'isNew' => true,
            ],
            // Добавьте другие товары
        ];
    
        return view('pages.home', compact('featuredProducts'));
    }
}
