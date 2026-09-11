<?php

namespace App\Http\Controllers;

use App\Http\Requests\Condition\StoreConditionRequest;
use App\Http\Requests\Condition\UpdateConditionRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Condition;
use Illuminate\Http\RedirectResponse;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index(): Response
    {
        return Inertia::render('Conditions/Index', [
            'conditions' => Condition::query()->orderBy('name')->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConditionRequest $request): RedirectResponse
    {
        Condition::create($request->validated());

        return back()->with('success', 'Condición creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(UpdateConditionRequest $request, Condition $condition): RedirectResponse
    {
        $condition->update($request->validated());

        return back()->with('success', 'Condición actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condition $condition): RedirectResponse
    {
        $condition->delete();

        return back()->with('success', 'Condición eliminada correctamente.');
    }
}
