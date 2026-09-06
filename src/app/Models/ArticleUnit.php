<?php

namespace App\Models;

use App\Enums\ArticleUnitStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $article_id
 * @property int|null $sale_article_id
 * @property string|null $serial_number
 * @property float $purchase_cost
 * @property ArticleUnitStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['article_id', 'sale_article_id', 'serial_number', 'purchase_cost', 'status'])]
class ArticleUnit extends Model
{
    protected function casts(): array
    {
        return [
            'purchase_cost' => 'decimal:2',
            'status' => ArticleUnitStatus::class,
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function saleArticle(): BelongsTo
    {
        return $this->belongsTo(SaleArticle::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }
}