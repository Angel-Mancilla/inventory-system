<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;


class ArticleRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
        
    }

    public function availableForSale(): Builder
    {
        return Article::query()
            ->where('is_active', true)
            ->where('stock','>', 0)
            ->with(['productModel.brand', 'productModel.category', 'condition']);
    }

    public function withCurrentPrice(): Builder
    {
        return Article::query()->with(['prices' =>  function($query) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', now());
        }]);
    }


}
