<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoritController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
        // $this->middleware("Permission:view products");
    }
    /**
     * Display a listing of the resource.
     */
    public function showAll(Request $request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');
        $user = auth('sanctum')->user();
    
        $favorites = Favorit::where('user_id', $user->id)
            ->where('status', 1)
            ->pluck('product_id');
    
        $products = Product::select(
            'products.id',
            'products.product_name',
            'products.product_price',
            'products.image',
            'products.discount_percentage',
            DB::raw('CASE WHEN EXISTS(SELECT 1 FROM favorits WHERE favorits.product_id = products.id AND favorits.user_id = ' . $user->id . ' AND favorits.status = 1) THEN 1 ELSE 0 END AS is_favorite')
        )
        ->whereIn('id', $favorites)
        ->orderByDesc('id')
        ->paginate($perPage, ['*'], "current_page", $page);
    
        return self::responseSuccess(self::formatPaginatedResponse($products, ProductController::formatProductDataForDisplay($products->items())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'status' => 'required',
        ]);

        Favorit::updateOrCreate([
            'product_id' => $request->get('product_id'),
            'user_id' => auth('sanctum')->user()->id,
        ], [
            'status' => $request->get('status'),
        ]);

        return self::responseSuccess([], 'تمت العملية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorit $favorit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorit $favorit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorit $favorit)
    {
        //
    }
}
