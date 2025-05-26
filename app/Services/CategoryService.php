<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAll()
    {
        return Category::orderBy('name')->get();
    }

    public function getById(int $id)
    {
        return Category::findOrFail($id);
    }
}