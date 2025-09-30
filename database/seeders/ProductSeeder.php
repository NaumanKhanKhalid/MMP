<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Dummy brand and category bana lo agar nai hain
        $brand = Brand::firstOrCreate(['name' => 'Example Brand']);
        $category = Category::firstOrCreate(['name' => 'Example Category']);
        $subcategory = Category::firstOrCreate(['name' => 'Example Subcategory']);

        $supplier1 = Supplier::firstOrCreate(['name' => 'Supplier One']);
        $supplier2 = Supplier::firstOrCreate(['name' => 'Supplier Two']);

        // 1st Product with full details
        $product1 = Product::create([
            'name' => 'Full Details Product',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'sku' => 'SKU12345',
            'barcode_primary' => 'BAR12345',
            'unit' => 'PCS',
            'price_normal' => 100.00,
            'price_online' => 90.00,
            'price_workshop' => 85.00,
            'reorder_level' => 10,
            'allow_negative' => true,
            'special_order' => false,
            'status' => 'active',
            'description' => 'A fully detailed product.',
            'notes' => 'Some internal notes',
        ]);

        // OE Numbers
        foreach (['OE123', 'OE456'] as $oe) {
            $product1->oeNumbers()->create(['oe_number' => $oe]);
        }

        // Cross References
        foreach (['CR789', 'CR101'] as $ref) {
            $product1->crossRefs()->create(['cross_ref' => $ref]);
        }

        // Suppliers
        $product1->suppliers()->sync([$supplier1->id, $supplier2->id]);

        // Fitments
        $product1->fitments()->create([
            'make_id' => 1,
            'model_id' => 1,
            'engine_id' => 1,
            'year_start' => 2015,
            'year_end' => 2020,
        ]);

        // Fake image upload (optional)
        // $fakeImagePath = 'products/sample.jpg';
        // if (!Storage::disk('public')->exists($fakeImagePath)) {
        //     Storage::disk('public')->put($fakeImagePath, file_get_contents(public_path('dummy/sample.jpg')));
        // }
        // $product1->images()->create(['path' => $fakeImagePath]);

        // 2nd Product with minimal details
        $product2 = Product::create([
            'name' => 'Basic Product',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'status' => 'inactive',
        ]);
    }
}
