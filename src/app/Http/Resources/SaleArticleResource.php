<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'articulo_id' => $this->article_id,
            'nombre' => $this->whenLoaded('article', fn () => $this->article->productModel->name),
            'sku' => $this->whenLoaded('article', fn () => $this->article->sku),
            'cantidad' => $this->quantity,
            'precio_unitario' => (float) $this->unit_price,
            'subtotal' => (float) $this->subtotal,
            'garantia_dias' => $this->warranty_days,
        ];
    }
}
