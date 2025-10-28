<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'barcode_primary',
        'barcode_alternate',
        'name',
        'supplier_id',
        'supplier_code',
        'brand_id',
        'category_id',
        'subcategory_id',
        'unit',
        'bin_location',
        'price_normal',
        'price_online',
        'price_workshop',
        'cost_price',
        'last_cost',
        'reorder_level',
        'on_hand',
        'on_order',
        'reserved',
        'allow_negative',
        'special_order',
        'status',
        'car_model_id',
        'engine_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'allow_negative' => 'boolean',
        'special_order' => 'boolean',
        'price_normal' => 'decimal:2',
        'price_online' => 'decimal:2',
        'price_workshop' => 'decimal:2',
        'cost_price' => 'decimal:4',
        'last_cost' => 'decimal:4',
        'on_hand' => 'decimal:4',
        'on_order' => 'decimal:4',
        'reserved' => 'decimal:4',
    ];

    // Auto-generate SKU & Barcode
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = self::generateSku();
            }
            if (empty($product->barcode_primary)) {
                $product->barcode_primary = self::generateBarcode($product->sku);
            }
        });
    }

    public static function generateSku()
    {
        $lastProduct = self::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastProduct ? (int) filter_var($lastProduct->sku, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;

        return str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // 0001, 0002...
    }

    public static function generateBarcode($sku)
    {
        return 'MMP-'.$sku; // MMP-0001, MMP-0002...
    }

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function suppliers()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stockBatches()
    {
        return $this->hasMany(StockBatch::class);
    }

    public function stockLedger()
    {
        return $this->hasMany(StockLedger::class);
    }

    public function oeNumbers()
    {
        return $this->hasMany(ProductOeNumber::class);
    }

    public function crossRefs()
    {
        return $this->hasMany(ProductCrossRef::class);
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function engine()
    {
        return $this->belongsTo(Engine::class);
    }

    // Calculate on-hand stock
    public function getOnHandAttribute()
    {
        return $this->stockBatches()->sum('qty_left');
    }

    // Calculate actual stock including negative (from stock ledger)
    public function getActualStockAttribute()
    {
        // Sum all stock movements from ledger (includes negative stock)
        return $this->stockLedger()->sum('qty');
    }

    // Check if stock is negative
    public function isNegativeStock()
    {
        return $this->actual_stock < 0;
    }

    // Check if stock is low
    public function isLowStock()
    {
        return $this->on_hand <= $this->reorder_level && $this->on_hand > 0;
    }

    // FIFO Costing Methods
    public function getFifoCost()
    {
        $batches = $this->stockBatches()
            ->where('qty_left', '>', 0)
            ->orderBy('received_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($batches->isEmpty()) {
            return 0;
        }

        return $batches->first()->landed_unit_cost;
    }

    public function getAverageCost()
    {
        $totalValue = $this->stockBatches()
            ->where('qty_left', '>', 0)
            ->sum(DB::raw('qty_left * landed_unit_cost'));
        
        $totalQty = $this->stockBatches()
            ->where('qty_left', '>', 0)
            ->sum('qty_left');

        return $totalQty > 0 ? $totalValue / $totalQty : 0;
    }

    public function getTotalCostValue()
    {
        return $this->stockBatches()
            ->where('qty_left', '>', 0)
            ->sum(DB::raw('qty_left * landed_unit_cost'));
    }

    // Profit Calculations
    public function getProfitMargin()
    {
        $cost = $this->getFifoCost();
        $sellingPrice = $this->price_normal;
        
        if ($cost <= 0 || $sellingPrice <= 0) {
            return 0;
        }

        return (($sellingPrice - $cost) / $sellingPrice) * 100;
    }

    public function getProfitAmount()
    {
        $cost = $this->getFifoCost();
        $sellingPrice = $this->price_normal;
        
        return $sellingPrice - $cost;
    }

    /**
     * Get primary image URL
     */
    public function getPrimaryImageUrlAttribute()
    {
        $firstImage = $this->images->first();
        if ($firstImage && $firstImage->path) {
            return asset('storage/app/public/' . $firstImage->path);
        }
        return asset('public/assets/images/pos-system/1.jpg');
    }
}
