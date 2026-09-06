<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_model_id
 * @property int $warehouse_id
 * @property int $condition_id
 * @property string $sku
 * @property string|null $barcode
 * @property string|null $description
 * @property int $stock
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['product_model_id', 'warehouse_id', 'condition_id', 'sku', 'barcode', 'description', 'stock', 'is_active'])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function productModel(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(PriceArticle::class);
    }

    public function articleUnits(): HasMany
    {
        return $this->hasMany(ArticleUnit::class);
    }

    public function saleArticles(): HasMany
    {
        return $this->hasMany(SaleArticle::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    /**
     * Precio vigente del artículo (el más reciente sin fecha de fin, o con
     * fecha de fin todavía no cumplida).
     */
    public function precioActual(): ?float
    {
        return $this->prices()
            ->where(fn ($query) => $query->whereNull('end_date')->orWhere('end_date', '>=', now()))
            ->latest('start_date')
            ->value('price');
    }

    public function tieneStockDisponible(int $cantidad): bool
    {
        return $this->stock >= $cantidad;
    }
}