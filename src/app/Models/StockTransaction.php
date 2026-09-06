<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Database\Factories\StockTransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $article_id
 * @property int|null $article_unit_id
 * @property int|null $purchase_order_item_id
 * @property int|null $sale_article_id
 * @property int $user_id
 * @property StockMovementType $movement_type
 * @property int $quantity
 * @property float|null $unit_cost
 * @property float|null $total_cost
 * @property \Illuminate\Support\Carbon $transaction_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'article_id', 'article_unit_id', 'purchase_order_item_id', 'sale_article_id',
    'user_id', 'movement_type', 'quantity', 'unit_cost', 'total_cost',
    'transaction_date', 'notes',
])]
class StockTransaction extends Model
{
    /** @use HasFactory<StockTransactionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'movement_type' => StockMovementType::class,
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'transaction_date' => 'datetime',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function articleUnit(): BelongsTo
    {
        return $this->belongsTo(ArticleUnit::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function saleArticle(): BelongsTo
    {
        return $this->belongsTo(SaleArticle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}