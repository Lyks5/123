<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем админа для тестов
        $this->admin = User::factory()->create([
            'is_admin' => true
        ]);
    }

    public function test_can_list_attributes()
    {
        // Создаем тестовые атрибуты
        Attribute::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.attributes.index'));

        $response->assertStatus(200)
            ->assertViewIs('admin.attributes.index')
            ->assertViewHas('attributes');
    }

    public function test_can_create_attribute()
    {
        $attributeData = [
            'name' => 'Размер',
            'type' => 'select',
            'display_name' => 'Размер одежды',
            'is_required' => true
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.attributes.store'), $attributeData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Атрибут успешно создан'
            ]);

        $this->assertDatabaseHas('attributes', [
            'name' => 'Размер',
            'type' => 'select'
        ]);
    }

    public function test_can_update_attribute()
    {
        $attribute = Attribute::factory()->create();
        
        $updateData = [
            'name' => 'Новый размер',
            'type' => 'select',
            'display_name' => 'Обновленное название',
            'is_required' => false
        ];

        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.attributes.update', $attribute), $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Атрибут успешно обновлен'
            ]);

        $this->assertDatabaseHas('attributes', [
            'id' => $attribute->id,
            'name' => 'Новый размер',
            'display_name' => 'Обновленное название',
            'is_required' => false
        ]);
    }

    public function test_can_delete_attribute()
    {
        $attribute = Attribute::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.attributes.destroy', $attribute));

        $response->assertRedirect(route('admin.attributes.index'))
            ->assertSessionHas('success', 'Атрибут успешно удален.');

        $this->assertDatabaseMissing('attributes', [
            'id' => $attribute->id
        ]);
    }

    public function test_validates_unique_name()
    {
        $existingAttribute = Attribute::factory()->create([
            'name' => 'Существующий атрибут'
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.attributes.store'), [
                'name' => 'Существующий атрибут',
                'type' => 'select'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJson([
                'errors' => [
                    'name' => ['Атрибут с таким названием уже существует']
                ]
            ]);
    }

    public function test_can_manage_attribute_values()
    {
        $attribute = Attribute::factory()->create([
            'type' => 'select'
        ]);

        // Добавление значения
        $response = $this->actingAs($this->admin)
            ->post(route('admin.attributes.values.store', $attribute), [
                'value' => 'M'
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attribute_values', [
            'attribute_id' => $attribute->id,
            'value' => 'M'
        ]);

        // Получение формы редактирования
        $value = $attribute->values()->first();
        $response = $this->actingAs($this->admin)
            ->get(route('admin.attributes.values.edit', [
                'attribute' => $attribute->id,
                'valueId' => $value->id
            ]));

        $response->assertStatus(200)
            ->assertViewIs('admin.attributes.values.edit')
            ->assertViewHas(['attribute', 'value']);

        // Обновление значения
        $response = $this->actingAs($this->admin)
            ->put(route('admin.attributes.values.update', [
                'attribute' => $attribute->id,
                'valueId' => $value->id
            ]), [
                'value' => 'L',
                'display_order' => 2
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attribute_values', [
            'id' => $value->id,
            'value' => 'L',
            'display_order' => 2
        ]);

        // Удаление значения
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.attributes.values.delete', [
                'attribute' => $attribute->id,
                'valueId' => $value->id
            ]));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('attribute_values', [
            'id' => $value->id
        ]);
    }

    public function test_can_manage_color_attribute_values()
    {
        $attribute = Attribute::factory()->create([
            'type' => 'color'
        ]);

        // Добавление цветового значения
        $response = $this->actingAs($this->admin)
            ->post(route('admin.attributes.values.store', $attribute), [
                'value' => 'Красный',
                'hex_color' => '#FF0000'
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attribute_values', [
            'attribute_id' => $attribute->id,
            'value' => 'Красный',
            'hex_color' => '#FF0000'
        ]);

        // Получение формы редактирования
        $value = $attribute->values()->first();
        $response = $this->actingAs($this->admin)
            ->get(route('admin.attributes.values.edit', [
                'attribute' => $attribute->id,
                'valueId' => $value->id
            ]));

        $response->assertStatus(200)
            ->assertViewIs('admin.attributes.values.edit')
            ->assertSee('hex_color');

        // Обновление цветового значения
        $response = $this->actingAs($this->admin)
            ->put(route('admin.attributes.values.update', [
                'attribute' => $attribute,
                'valueId' => $value->id
            ]), [
                'value' => 'Темно-красный',
                'hex_color' => '#800000'
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attribute_values', [
            'id' => $value->id,
            'value' => 'Темно-красный',
            'hex_color' => '#800000'
        ]);
    }
}