<?php

namespace App\Actions\Sales;

use App\Enums\SaleStatus;
use App\Enums\StockMovementType;
use App\Models\Sale;
use App\Models\StockTransaction;
use Exception;
use Illuminate\Support\Facades\DB;

class CancelSaleAction
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

    public function execute(Sale $sale)
    {
        return DB::transaction(function () use ($sale) {
            
           $sale->refresh();

           if($sale->status === SaleStatus::Cancelled){
                throw new Exception("La venta ya ha sido cancelada");
           }

           $saleArticles = $sale->articles()->lockForUpdate()->first();

           foreach ($saleArticles as $saleArticle) {
            $article = $saleArticle->article()->lockForUpdate()->first();
            
            StockTransaction::create([
                'article_id' => $article->id,
                'sale_article_id' => $saleArticle->id,
                'user_id' => auth()->id(),
                'movement_type' => StockMovementType::Cancellation,
                'quantity' => $saleArticle->quantity,
                'transaction_date' => now(),
            ]);

            $article->increment('stock', $saleArticle->quantity);

           }

        $sale->update(['status', SaleStatus::Cancelled]);           

        return $sale->fresh('articles');
            
        });
    }
}
