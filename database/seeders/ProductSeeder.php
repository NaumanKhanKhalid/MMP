<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductOENumber;
use App\Models\ProductCrossRef;
use App\Models\ProductSupplier;
use App\Models\ProductFitment;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Supplier;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Engine;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::first();
        $categories = Category::where('parent_id', null)->get();
        $subcategories = Category::where('parent_id', '!=', null)->get();
        $supplier = Supplier::first();
        $make = CarMake::first();
        $model = CarModel::first();
        $engine = Engine::first();

        if (!$brand || !$categories || !$subcategories || !$supplier || !$make || !$model || !$engine) {
            $this->command->warn("⚠️ Please seed brands, categories, subcategories, suppliers, makes, models, engines before Products.");
            return;
        }

        // Create Product   
        $product = Product::create([
            'sku' => 'SKU-0001',
            'barcode' => 'BAR-0001',
            'name' => 'Oil Filter',
            'description' => 'High performance oil filter for various vehicles.',
            'brand_id' => $brand->id,
            'category_id' => $categories->first()->id,
            'subcategory_id' => $subcategories->first()->id,
            'supplier_code' => 'SUP-123',
            'unit' => 'PCS',
            'bin_location' => 'A-1',
            'reorder_level' => 10,
            'price_normal' => 1200,
            'price_online' => 1100,
            'price_workshop' => 1000,
            'allow_negative' => false,
            'special_order' => false,
            'notes' => 'Demo product for testing full relations.',
            'status' => 'active',
        ]);



    }
}
