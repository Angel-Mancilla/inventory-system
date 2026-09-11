<?php

namespace App\Actions\Sales;

use App\DTOs\Sales\CreateSaleData;
use App\Exceptions\InsufficientStockException;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\StockTransaction;

class CreateSaleAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(): void
    {
        //
    }

    public function execute(CreateSaleData $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $sale = Sale::create([
                'user_id'   =>  $data->userId,
                'status'    =>  'completed',
                'subtotal'  =>  0,
                'discount'  =>  $data->discount,
                'total'     =>  0,
                'sale_date' =>  now(),
                'note'      =>  $data->note,
            ]);

            $subtotal = 0;

            foreach ($data->items as $item)
            {
                $article = Article::lockForUpdate()->findOrFail($item['article_id']);

                // if($article->stock < $item['quantity'])
                if($article->hasAvailableStock($item['quantity']))
                {
                    throw new InsufficientStockException(
                        "Stock insuficiente para el articulo #{$article->id}"
                    );
                }

                $unitePrice = $article->currentPrice();
                $lineSubtotal = $unitePrice * $item['quantity'];
                $subtotal += $lineSubtotal;

                $saleArticle = $sale->articles()->create([
                    'article_id'    =>  $article->id,
                    'quantity'      =>  $item['quantity'],
                    'unite_price'   =>  $unitePrice,
                    'subtotal'      =>  $subtotal,
                    'warranty_days' =>  $article->condition->warranty_days,
                ]);

                StockTransaction::create([
                    'article_id'        =>  $article->id,
                    'sale_article_id'   =>  $saleArticle->id,
                    'user_id'           =>  $data->userId,
                    'movement_type'     =>  'sale',
                    'quantity'          =>  $item['quantity'],
                    'transaction_date'  =>  now(),
                ]);

                $article->decrement('stock', $item['quantity']);
            }

            $sale->update([
                'subtotal'  =>  $subtotal,
                'total'     =>  $subtotal - $data->discount,
            ]);

            return $sale->fresh('articles');
        });
    }
}
