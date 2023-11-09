<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Admin\ProductGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductGroupRequest;

class ProductGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_groups = ProductGroup::with('properties')
            ->simplePaginate(15);

        return response()->json($product_groups);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\ProductGroup  $product_group
     * @return \Illuminate\Http\Response
     */
    public function show(ProductGroup $product_group)
    {
        return response()->json([
            'message' => 'Success',
            'data' => $product_group->load('properties')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductGroupRequest $request)
    {
        $product_group = ProductGroup::create([
            'label' => $request->label,
        ]);
        $product_group->save();

        // attach relationships
        $product_group
            ->properties()
            ->attach($request->property_ids);

        return response()->json([
            'message' => 'Product group successfully added.',
            'data' => $product_group->load('properties')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\ProductGroupRequest  $request
     * @param  \App\Models\Admin\ProductGroup  $product_group
     * @return \Illuminate\Http\Response
     */
    public function update(ProductGroupRequest $request, ProductGroup $product_group)
    {
        $product_group->label = $request?->label ?? $product_group->label;
        $product_group->save();

        // sync relationships
        $product_group
            ->properties()
            ->sync($request->property_ids);

        return response()->json([
            'message' => 'Product group successfully updated.',
            'data' => $product_group->load('properties')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\ProductGroup  $product_group
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductGroup $product_group)
    {
        $product_group->delete();

        return response([
            'message' => 'Product group successfully deleted.'
        ]);
    }
}
