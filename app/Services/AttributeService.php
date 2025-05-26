<?php

namespace App\Services;

use App\Models\Attribute;

class AttributeService
{
    public function getAll()
    {
        return Attribute::orderBy('name')->get();
    }

    public function getById(int $id)
    {
        return Attribute::findOrFail($id);
    }
}