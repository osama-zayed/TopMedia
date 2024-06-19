<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $perPage = request()->get('per_page', 9); // Set the number of products per page
        // $page = request()->get('page', 1); // Get the current page from the request

        // $products = Product::select(
        //     'id',
        //     'product_name',
        //     'product_price',
        //     'image',
        //     'discount_percentage',
        //     'category_id'
        // )
        //     ->where('product_status', 1)
        //     ->with('Category:id,category_name')
        //     // ->orderByDesc('id')
        //     ->paginate($perPage, ['*'], 'page', $page);

        // $groupedProducts = $products->groupBy(function ($product) {
        //     return $product->Category->category_name;
        // });

        // $groupedProducts = $groupedProducts->map(function ($products) {
        //     return $products->take(3);
        // });
        // dd($groupedProducts);
        return view('page.home', [
            // 'groupedProducts' => $groupedProducts
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
    public function store(Request $request)
    {
        //
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
