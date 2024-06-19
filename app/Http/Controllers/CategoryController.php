<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryId = request()->get('Category_id', 1);
        $products = Product::select(
            'id',
            'product_name',
            'product_price',
            'image',
            'discount_percentage',
            'category_id'
        )
            ->where('product_status', 1)
            ->where('category_id', $categoryId)
            ->orderByDesc('id')
            ->get();

        $groupedProducts = $this->groupProductsInThrees($products);


        $categoryName = Category::find($categoryId)->category_name;
        return view('page.Category', [
            'groupedProducts' => $groupedProducts,
            'categoryName' => $categoryName
        ]);
    }
    private function groupProductsInThrees($products)
    {
        $groupedProducts = [];
        $currentGroup = [];

        foreach ($products as $product) {
            $currentGroup[] = $product;

            if (count($currentGroup) === 3) {
                $groupedProducts[] = $currentGroup;
                $currentGroup = [];
            }
        }

        if (!empty($currentGroup)) {
            $groupedProducts[] = $currentGroup;
        }

        return $groupedProducts;
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
