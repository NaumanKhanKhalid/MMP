<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'description',
        'brand_id',
        'category_id',
        'primary_supplier_id',
        'supplier_code',
        'unit',
        'images',
        'bin_location',
        'reorder_level',
        'price_normal',
        'price_online',
        'price_workshop',
        'allow_negative',
        'special_order',
        'notes',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
        'allow_negative' => 'boolean',
        'special_order' => 'boolean',
    ];
    public function fitments()
    {
        return $this->hasMany(ProductFitment::class);
    }
    // relations
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function primarySupplier()
    {
        return $this->belongsTo(Supplier::class, 'primary_supplier_id');
    }

    public function oeNumbers()
    {
        return $this->hasMany(ProductOeNumber::class);
    }
    public function crossRefs()
    {
        return $this->hasMany(ProductCrossRef::class);
    }

    // pivot
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot('purchase_price', 'currency', 'lead_time', 'supplier_sku')
            ->withTimestamps();
    }

    public function stockBatches()
    {
        return $this->hasMany(StockBatch::class);
    }
    public function stockLedger()
    {
        return $this->hasMany(StockLedger::class);
    }

    // helpers
    public static function generateSku()
    {
        $last = self::select('sku')->orderByDesc('id')->first();
        if (!$last) {
            $next = 1;
        } else {
            $digits = preg_replace('/\D/', '', $last->sku);
            $next = ($digits ? intval($digits) : $last->id) + 1;
        }
        return str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public static function generateBarcode($sku)
    {
        return 'MMP-' . $sku;
    }

    // on-hand computed from batches
    public function onHand()
    {
        return $this->stockBatches()->sum('qty_left');
    }

    // eager-friendly accessor using withSum in queries: use ->withSum('stockBatches','qty_left')
}
