<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\SaleArticleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'estado' => $this->status->value,
            'estado_label' => $this->status->value,
            'subtotal' => (float) $this->subtotal,
            'descuento' => (float) $this->discount,
            'total' => (float) $this->total,
            'fecha' => $this->sale_date->toDateTimeString(),
            'nota' => $this->note,
            'vendedor' => $this->whenLoaded('user', fn () => $this->user->name),//whenLoaded revisa si el modelo ya trae cargada esta relacion
            // en memoria, si no la tiene entonces la descarta, pero si la tiene la consulta, con esto ahorramos el error n + 1
            'articulos' => SaleArticleResource::collection($this->whenLoaded('articles')), 
            'tiene_devoluciones' => $this->whenLoaded(
                'returns',
                fn () => $this->returns->isNotEmpty(),
                false,
            ),
        ];
    }
}
