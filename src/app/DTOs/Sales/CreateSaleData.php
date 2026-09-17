<?php

namespace App\DTOs\Sales;

use App\Http\Requests\Sale\StoreSaleRequest;

class CreateSaleData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $userId,
        public readonly array $items,
        public readonly float $discount = 0,
        public readonly ?string $note = null,
        )
    {}

    public static function fromRequest (StoreSaleRequest $request)
    {
        $requestValidated = $request->validated();

        return new self(
            userId: $requestValidated->user()->id,
            items: $requestValidated['items'],
            discount: $requestValidated['discount'],
            note: $requestValidated['note'],
        );
    }
}
