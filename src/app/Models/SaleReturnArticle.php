<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $sale_return_id
 * @property int $sale_article_id
 * @property int $quantity
 * @property float|null $refund_amount
 * @property string|null $return_reason
 * @property bool $warranty_accepted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['sale_return_id', 'sale_article_id', 'quantity', 'refund_amount', 'return_reason', 'warranty_accepted'])]
class SaleReturnArticle extends Model
{
    protected function casts(): array
    {
        return [
            'refund_amount' => 'decimal:2',
            'warranty_accepted' => 'boolean',
        ];
    }

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function saleArticle(): BelongsTo
    {
        return $this->belongsTo(SaleArticle::class);
    }
}