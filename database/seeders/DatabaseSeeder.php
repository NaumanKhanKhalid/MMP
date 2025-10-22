<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "🌱 Starting database seeding...\n\n";

        // Step 1: Users and Roles
        echo "📌 Step 1: Users & Roles\n";
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SettingsSeeder::class,
        ]);

        // Step 2: Product Categories and Suppliers
        echo "\n📌 Step 2: Categories & Suppliers\n";
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            SupplierSeeder::class,
        ]);

        // Step 3: Vehicle Data
        echo "\n📌 Step 3: Vehicle Data\n";
        $this->call([
            CarMakesSeeder::class,
            CarModelsSeeder::class,
            EnginesSeeder::class,
        ]);

        // Step 4: Products and Inventory
        echo "\n📌 Step 4: Products & Inventory\n";
        $this->call([
            ProductSeeder::class,
            ProductFitmentsSeeder::class,
            ProductOENumbersSeeder::class,
            ProductCrossRefsSeeder::class,
            // ProductSuppliersSeeder::class, // Commented out - using direct supplier_id on products table
            ProductImagesSeeder::class,
        ]);

        // Step 5: Customers
        echo "\n📌 Step 5: Customers\n";
        $this->call([
            CustomerSeeder::class,
        ]);

        // Step 6: Sales Transactions
        echo "\n📌 Step 6: Sales & Transactions\n";
        $this->call([
            QuoteSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            ReturnSeeder::class,
        ]);

        // Step 7: Workshop
        echo "\n📌 Step 7: Workshop\n";
        $this->call([
            JobCardSeeder::class,
        ]);

        // Step 8: Purchasing & Stock
        echo "\n📌 Step 8: Purchasing & Stock\n";
        $this->call([
            PurchaseOrderSeeder::class,
            GoodsReceiptSeeder::class,
            StockBatchSeeder::class,
            StockLedgerSeeder::class,
            StockCountSeeder::class,
        ]);

        echo "\n✅ Database seeding completed successfully!\n";
        echo "📊 Demo data is ready for client presentation.\n\n";
    }
}

