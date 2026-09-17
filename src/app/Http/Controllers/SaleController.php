<?php

namespace App\Http\Controllers;

use App\Actions\Sales\CancelSaleAction;
use App\Actions\Sales\CreateSaleAction;
use App\DTOs\Sales\CreateSaleData;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use Illuminate\Http\Request;
use App\Models\Sale;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Sales/Index',[
            'sales' =>  SaleResource::collection(
                Sale::query()->with('user')->latest('sale_date')->paginate(20)
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Sales/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request, CreateSaleAction $action)
    {
        $sale = $action->execute(CreateSaleData::fromRequest($request));

        return to_route('sales.show', $sale)
            ->with('success', 'Venta registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return Inertia::render('Sales/Show', [
            'sale' => new SaleResource($sale->load(['user', 'articles.article.productModel', 'returns'])),
        ]);
    }

    public function cancel(Sale $sale, CancelSaleAction $action)
    {
        $action->execute($sale);

        return back()->with('success', 'Venta cancelada correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
