<?php

namespace Database\Seeders;

use UserSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder as UserSeederAlias;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users and Roles
        $this->call([
            RoleSeeder::class,
            UserSeederAlias::class,
        ]);

        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            SupplierSeeder::class,
            CarMakesSeeder::class,
            CarModelsSeeder::class,
            EnginesSeeder::class,
            ProductSeeder::class,
            ProductFitmentsSeeder::class,
            ProductOENumbersSeeder::class,
            ProductCrossRefsSeeder::class,
            ProductSuppliersSeeder::class,
            ProductImagesSeeder::class,
            GoodsReceiptSeeder::class,
            StockBatchSeeder::class,
            StockLedgerSeeder::class,
        ]);
    }
}
