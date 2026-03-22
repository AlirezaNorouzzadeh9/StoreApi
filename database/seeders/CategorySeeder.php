<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'کالای دیجیتال',
                'slug' => 'digital-goods',
                'children' => [
                    ['name' => 'موبایل', 'slug' => 'mobile'],
                    ['name' => 'لپ تاپ', 'slug' => 'laptop'],
                    ['name' => 'تبلت', 'slug' => 'tablet'],
                    ['name' => 'لوازم جانبی موبایل', 'slug' => 'mobile-accessories'],
                    ['name' => 'هدفون و هندزفری', 'slug' => 'audio-accessories'],
                ],
            ],
            [
                'name' => 'خانه و آشپزخانه',
                'slug' => 'home-and-kitchen',
                'children' => [
                    ['name' => 'لوازم پخت و پز', 'slug' => 'cookware'],
                    ['name' => 'لوازم برقی خانه', 'slug' => 'home-appliances'],
                    ['name' => 'دکوراسیون', 'slug' => 'decoration'],
                    ['name' => 'سرو و پذیرایی', 'slug' => 'serving'],
                ],
            ],
            [
                'name' => 'مد و پوشاک',
                'slug' => 'fashion',
                'children' => [
                    ['name' => 'پوشاک مردانه', 'slug' => 'mens-fashion'],
                    ['name' => 'پوشاک زنانه', 'slug' => 'womens-fashion'],
                    ['name' => 'پوشاک بچگانه', 'slug' => 'kids-fashion'],
                    ['name' => 'کیف و کفش', 'slug' => 'bags-and-shoes'],
                ],
            ],
            [
                'name' => 'زیبایی و سلامت',
                'slug' => 'beauty-and-health',
                'children' => [
                    ['name' => 'آرایشی', 'slug' => 'cosmetics'],
                    ['name' => 'بهداشتی', 'slug' => 'personal-care'],
                    ['name' => 'عطر و ادکلن', 'slug' => 'perfume'],
                    ['name' => 'مراقبت پوست و مو', 'slug' => 'skin-and-hair-care'],
                ],
            ],
            [
                'name' => 'سوپرمارکت',
                'slug' => 'supermarket',
                'children' => [
                    ['name' => 'مواد غذایی', 'slug' => 'groceries'],
                    ['name' => 'نوشیدنی', 'slug' => 'beverages'],
                    ['name' => 'تنقلات', 'slug' => 'snacks'],
                    ['name' => 'شوینده و نظافت', 'slug' => 'cleaning-supplies'],
                ],
            ],
            [
                'name' => 'کتاب و لوازم تحریر',
                'slug' => 'books-and-stationery',
                'children' => [
                    ['name' => 'کتاب', 'slug' => 'books'],
                    ['name' => 'لوازم تحریر', 'slug' => 'stationery'],
                    ['name' => 'دفتر و کاغذ', 'slug' => 'notebooks-and-paper'],
                    ['name' => 'هنر و طراحی', 'slug' => 'art-supplies'],
                ],
            ],
            [
                'name' => 'اسباب بازی و کودک',
                'slug' => 'toys-and-baby',
                'children' => [
                    ['name' => 'اسباب بازی', 'slug' => 'toys'],
                    ['name' => 'نوزاد و مادر', 'slug' => 'baby-and-mother'],
                    ['name' => 'کالسکه و لوازم حمل', 'slug' => 'baby-travel'],
                ],
            ],
            [
                'name' => 'ورزش و سفر',
                'slug' => 'sports-and-travel',
                'children' => [
                    ['name' => 'پوشاک ورزشی', 'slug' => 'sportswear'],
                    ['name' => 'تجهیزات ورزشی', 'slug' => 'sports-equipment'],
                    ['name' => 'کمپینگ و کوهنوردی', 'slug' => 'camping-and-hiking'],
                    ['name' => 'چمدان و کیف سفر', 'slug' => 'travel-bags'],
                ],
            ],
        ];

        foreach ($categories as $category) {
            $parentId = $this->upsertCategory($category['name'], $category['slug']);

            foreach ($category['children'] as $child) {
                $this->upsertCategory($child['name'], $child['slug'], $parentId);
            }
        }
    }

    private function upsertCategory(string $name, string $slug, ?int $parentId = null): int
    {
        $existing = DB::table('categories')->where('slug', $slug)->first();
        $payload = [
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId,
            'image_path' => null,
            'is_active' => true,
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('categories')->where('id', $existing->id)->update($payload);

            return (int) $existing->id;
        }

        return (int) DB::table('categories')->insertGetId([
            ...$payload,
            'created_at' => now(),
        ]);
    }
}
