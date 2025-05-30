<?php

namespace App\Services;

use App\Models\Attribute;

class AttributeService
{
    public function getAll()
    {
        return Attribute::with('values')->orderBy('name')->get();
    }

    public function getById(int $id)
    {
        return Attribute::findOrFail($id);
    }
}