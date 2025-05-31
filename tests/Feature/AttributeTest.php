<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    public function test_only_admin_can_access_attributes_management()
    {
        $this->actingAs($this->user)
            ->get(route('admin.attributes.index'))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->get(route('admin.attributes.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.attributes.index');
    }

    public function test_can_create_different_attribute_types()
    {
        $attributeTypes = [
            [
                'name' => 'Размер',
                'type' => 'select',
                'is_required' => true,
                'description' => 'Размер товара'
            ],
            [
                'name' => 'Цвет',
                'type' => 'color',
                'is_required' => true,
                'description' => 'Цвет товара'
            ],
            [
                'name' => 'Материал',
                'type' => 'radio',
                'is_required' => false,
                'description' => 'Материал изготовления'
            ],
            [
                'name' => 'Особенности',
                'type' => 'checkbox',
                'is_required' => false,
                'description' => 'Дополнительные характеристики'
            ]
        ];

        foreach ($attributeTypes as $attributeData) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.attributes.store'), $attributeData);

            $response->assertRedirect(route('admin.attributes.index'))
                ->assertSessionHas('success');

            $this->assertDatabaseHas('attributes', $attributeData);
        }
    }

    public function test_validates_attribute_type()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.attributes.store'), [
                'name' => 'Тест',
                'type' => 'invalid_type'
            ]);

        $response->assertSessionHasErrors(['type']);
    }

    public function test_can_manage_color_attribute_values_with_validation()
    {
        $attribute = Attribute::factory()->create([
            'name' => 'Цвет',
            'type' => 'color',
            'is_required' => true
        ]);

        // Проверка валидного hex-цвета
        $validColors = [
            ['value' => 'Красный', 'hex_color' => '#FF0000'],
            ['value' => 'Зеленый', 'hex_color' => '#00FF00'],
            ['value' => 'Синий', 'hex_color' => '#0000FF'],
            ['value' => 'Черный', 'hex_color' => '#000000']
        ];

        foreach ($validColors as $colorData) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.attributes.values.store', $attribute), $colorData);

            $response->assertRedirect()
                ->assertSessionHas('success');

            $this->assertDatabaseHas('attribute_values', array_merge(
                ['attribute_id' => $attribute->id],
                $colorData
            ));
        }

        // Проверка невалидных hex-цветов
        $invalidColors = [
            'invalid-color',
            '#GG0000',
            'FF0000',
            '#FF00',
            '#FF000000'
        ];

        foreach ($invalidColors as $invalidColor) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.attributes.values.store', $attribute), [
                    'value' => 'Тест',
                    'hex_color' => $invalidColor
                ]);

            $response->assertSessionHasErrors(['hex_color']);
        }
    }

    public function test_can_manage_select_attribute_values()
    {
        $attribute = Attribute::factory()->create([
            'name' => 'Размер',
            'type' => 'select',
            'is_required' => true
        ]);

        // Добавление значений
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        foreach ($sizes as $size) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.attributes.values.store', $attribute), [
                    'value' => $size
                ]);

            $response->assertRedirect()
                ->assertSessionHas('success');

            $this->assertDatabaseHas('attribute_values', [
                'attribute_id' => $attribute->id,
                'value' => $size
            ]);
        }

        // Проверка сортировки значений
        $values = $attribute->values()->pluck('value')->toArray();
        $this->assertEquals($sizes, $values);

        // Обновление значения
        $value = $attribute->values()->where('value', 'M')->first();
        $response = $this->actingAs($this->admin)
            ->put(route('admin.attributes.values.update', [
                'attribute' => $attribute->id,
                'value' => $value->id
            ]), [
                'value' => 'Medium'
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attribute_values', [
            'id' => $value->id,
            'value' => 'Medium'
        ]);
    }

    public function test_attribute_values_validation_by_type()
    {
        // Тест для числового атрибута
        $numericAttribute = Attribute::factory()->create([
            'name' => 'Вес',
            'type' => 'number',
            'is_required' => true
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.attributes.values.store', $numericAttribute), [
                'value' => 'не число'
            ]);

        $response->assertSessionHasErrors(['value']);

        // Тест для checkbox атрибута
        $checkboxAttribute = Attribute::factory()->create([
            'name' => 'Особенности',
            'type' => 'checkbox',
            'is_required' => false
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.attributes.values.store', $checkboxAttribute), [
                'value' => ''
            ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_can_use_attributes_in_product_form()
    {
        $colorAttribute = Attribute::factory()->create([
            'name' => 'Цвет',
            'type' => 'color'
        ]);

        $sizeAttribute = Attribute::factory()->create([
            'name' => 'Размер',
            'type' => 'select'
        ]);

        // Добавляем значения атрибутов
        $this->actingAs($this->admin)
            ->post(route('admin.attributes.values.store', $colorAttribute), [
                'value' => 'Красный',
                'hex_color' => '#FF0000'
            ]);

        $this->actingAs($this->admin)
            ->post(route('admin.attributes.values.store', $sizeAttribute), [
                'value' => 'L'
            ]);

        // Проверяем отображение атрибутов в форме товара
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.create'));

        $response->assertSuccessful()
            ->assertSee($colorAttribute->name)
            ->assertSee($sizeAttribute->name)
            ->assertSee('#FF0000')
            ->assertSee('L');
    }

    public function test_attribute_form_validation()
    {
        $invalidData = [
            ['name' => '', 'type' => 'select'],
            ['name' => 'Test', 'type' => ''],
            ['name' => 'Test', 'type' => 'invalid'],
            ['name' => str_repeat('a', 256), 'type' => 'select']
        ];

        foreach ($invalidData as $data) {
            $response = $this->actingAs($this->admin)
                ->post(route('admin.attributes.store'), $data);

            $response->assertSessionHasErrors();
        }
    }
}