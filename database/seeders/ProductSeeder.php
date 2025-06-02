<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\EcoFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private function getProductAttributes(string $categoryName): array 
    {
        $attributes = [];
        
        switch($categoryName) {
            case 'Футболки':
                $attributes = [
                    'Размер' => ['M', 'L', 'XL'],
                    'Цвет' => ['Белый', 'Черный', 'Зеленый'],
                    'Материал' => ['Хлопок'],
                    'Пол' => ['Унисекс'],
                    'Сезон' => ['Лето']
                ];
                break;
            case 'Толстовки':
                $attributes = [
                    'Размер' => ['S', 'M', 'L', 'XL'],
                    'Цвет' => ['Серый', 'Черный', 'Синий'],
                    'Материал' => ['Хлопок', 'Полиэстер'],
                    'Пол' => ['Унисекс'],
                    'Сезон' => ['Весна', 'Осень']
                ];
                break;
            case 'Брюки':
                $attributes = [
                    'Размер' => ['S', 'M', 'L', 'XL'],
                    'Цвет' => ['Черный', 'Синий'],
                    'Материал' => ['Нейлон', 'Спандекс'],
                    'Пол' => ['Унисекс'],
                    'Сезон' => ['Всесезонный']
                ];
                break;
            case 'Аксессуары':
                $attributes = [
                    'Цвет' => ['Черный', 'Синий', 'Зеленый'],
                    'Материал' => ['Нейлон', 'Полиэстер']
                ];
                break;
            case 'Обувь':
                $attributes = [
                    'Размер' => ['38', '39', '40', '41', '42', '43'],
                    'Цвет' => ['Черный', 'Белый', 'Серый'],
                    'Материал' => ['Нейлон', 'Хлопок'],
                    'Пол' => ['Унисекс'],
                    'Сезон' => ['Всесезонный']
                ];
                break;
        }
        
        return $attributes;
    }

    public function run(): void
    {
        // Футболки
        $this->createProducts('Футболки', [
            [
                'name' => 'Футболка из органического хлопка',
                'description' => 'Классическая футболка из 100% органического хлопка. Прекрасно подходит для повседневной носки.',
                'price' => 1990.00,
                'eco_score' => 90,
                'sustainability_info' => 'Изготовлено из органического хлопка, выращенного без пестицидов',
                'carbon_footprint' => 2.5,
            ],
            [
                'name' => 'Спортивная футболка ECO-DRY',
                'description' => 'Технологичная футболка из переработанного полиэстера с влагоотводящими свойствами',
                'price' => 2490.00,
                'eco_score' => 85,
                'sustainability_info' => 'Создано из переработанных пластиковых бутылок',
                'carbon_footprint' => 1.8,
            ],
            [
                'name' => 'Футболка с принтом "Save Nature"',
                'description' => 'Стильная футболка с экологическим принтом, выполненным экологически чистыми красками',
                'price' => 2290.00,
                'eco_score' => 88,
                'sustainability_info' => 'Экологически чистые краски на водной основе',
                'carbon_footprint' => 2.0,
            ],
        ]);

        // Толстовки
        $this->createProducts('Толстовки', [
            [
                'name' => 'Толстовка из переработанного флиса',
                'description' => 'Теплая толстовка из переработанного флиса с капюшоном',
                'price' => 4990.00,
                'eco_score' => 92,
                'sustainability_info' => 'Изготовлено из переработанных пластиковых бутылок',
                'carbon_footprint' => 3.2,
            ],
            [
                'name' => 'Худи из смесового эко-хлопка',
                'description' => 'Стильное худи из смеси органического хлопка и переработанного полиэстера',
                'price' => 5490.00,
                'eco_score' => 87,
                'sustainability_info' => 'Смесовой материал из органического хлопка и переработанного полиэстера',
                'carbon_footprint' => 2.9,
            ],
            [
                'name' => 'Спортивная толстовка Zero Impact',
                'description' => 'Легкая спортивная толстовка с минимальным воздействием на окружающую среду',
                'price' => 4490.00,
                'eco_score' => 95,
                'sustainability_info' => 'Производство с нулевым выбросом углерода',
                'carbon_footprint' => 1.5,
            ],
        ]);

        // Брюки
        $this->createProducts('Брюки', [
            [
                'name' => 'Эко-джоггеры',
                'description' => 'Удобные джоггеры из органического хлопка с добавлением переработанного полиэстера',
                'price' => 3990.00,
                'eco_score' => 88,
                'sustainability_info' => 'Сертифицированный органический хлопок',
                'carbon_footprint' => 2.8,
            ],
            [
                'name' => 'Спортивные брюки Pro-Eco',
                'description' => 'Профессиональные спортивные брюки из экологичных материалов',
                'price' => 4990.00,
                'eco_score' => 86,
                'sustainability_info' => 'Использование экологически чистых технологий производства',
                'carbon_footprint' => 2.4,
            ],
            [
                'name' => 'Треккинговые брюки Green Trail',
                'description' => 'Прочные треккинговые брюки из переработанного нейлона',
                'price' => 5990.00,
                'eco_score' => 90,
                'sustainability_info' => 'Переработанный нейлон из рыболовных сетей',
                'carbon_footprint' => 2.1,
            ],
        ]);

        // Аксессуары
        $this->createProducts('Аксессуары', [
            [
                'name' => 'Эко-рюкзак',
                'description' => 'Рюкзак из переработанных материалов с водоотталкивающим покрытием',
                'price' => 3490.00,
                'eco_score' => 94,
                'sustainability_info' => '100% переработанные материалы',
                'carbon_footprint' => 1.2,
            ],
            [
                'name' => 'Спортивная сумка Bio-Tech',
                'description' => 'Функциональная спортивная сумка из биоразлагаемых материалов',
                'price' => 2990.00,
                'eco_score' => 96,
                'sustainability_info' => 'Биоразлагаемые материалы',
                'carbon_footprint' => 0.9,
            ],
            [
                'name' => 'Эко-бутылка для воды',
                'description' => 'Многоразовая бутылка для воды из переработанной нержавеющей стали',
                'price' => 1490.00,
                'eco_score' => 98,
                'sustainability_info' => 'Переработанная нержавеющая сталь',
                'carbon_footprint' => 0.5,
            ],
        ]);

        // Обувь
        $this->createProducts('Обувь', [
            [
                'name' => 'Кроссовки из переработанных материалов',
                'description' => 'Легкие кроссовки из переработанных материалов с амортизирующей подошвой',
                'price' => 7990.00,
                'eco_score' => 89,
                'sustainability_info' => 'Верх из переработанных материалов',
                'carbon_footprint' => 3.5,
            ],
            [
                'name' => 'Эко-кеды Natural Step',
                'description' => 'Повседневные кеды из органического хлопка и натурального каучука',
                'price' => 5990.00,
                'eco_score' => 92,
                'sustainability_info' => 'Натуральные и органические материалы',
                'carbon_footprint' => 2.8,
            ],
            [
                'name' => 'Беговые кроссовки Green Runner',
                'description' => 'Профессиональные беговые кроссовки из экологичных материалов',
                'price' => 8990.00,
                'eco_score' => 91,
                'sustainability_info' => 'Инновационные эко-материалы',
                'carbon_footprint' => 3.0,
            ],
        ]);
    }

    private function createProducts(string $categoryName, array $products): void
    {
        $category = Category::where('name', $categoryName)->first();
        $attributes = Attribute::with('values')->get();
        $productAttributes = $this->getProductAttributes($categoryName);

        foreach ($products as $product) {
            $newProduct = Product::create([
                'category_id' => $category->id,
                'name' => $product['name'],
                'description' => $product['description'],
                'sku' => Str::uuid(),
                'price' => $product['price'],
                'stock_quantity' => rand(10, 100),
                'status' => 'published',
                'is_featured' => rand(0, 1),
                'eco_score' => $product['eco_score'],
                'sustainability_info' => $product['sustainability_info'],
                'carbon_footprint' => $product['carbon_footprint'],
            ]);

            // Добавляем атрибуты
            foreach ($attributes as $attribute) {
                if (isset($productAttributes[$attribute->name])) {
                    $valueNames = $productAttributes[$attribute->name];
                    $value = $attribute->values()
                        ->whereIn('value', $valueNames)
                        ->inRandomOrder()
                        ->first();
                        
                    if ($value) {
                        $newProduct->attributeValues()->attach($value->id);
                    }
                }
            }

            // Добавляем случайное количество эко-характеристик (1-2)
            $ecoFeatures = EcoFeature::inRandomOrder()->take(rand(1, 2))->get();
            $ecoFeaturesData = [];

            foreach ($ecoFeatures as $feature) {
                $ecoFeaturesData[$feature->id] = ['value' => rand(1, 10)];
            }

            if (!empty($ecoFeaturesData)) {
                $newProduct->ecoFeatures()->attach($ecoFeaturesData);
            }

            // Добавляем изображение для продукта
            $newProduct->images()->create([
                'image_path' => 'products/футболка.png',
                'original_name' => 'футболка.png',
                'mime_type' => 'image/png',
                'size' => 0,
                'order' => 1
            ]);
        }
    }
}