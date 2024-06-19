<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public static function showAll(Request $request)
    {
        try {
            return self::responseSuccess(self::getProductsPaginated($request));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }
    public static function showById(Request $request)
    {
        try {
            return self::responseSuccess(self::getProductById($request->get('id', null)));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public static function getProductsPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');
        $category_id = $request->get('category_id');

        $user = auth('sanctum')->user();

        $query = Product::select(
            'products.id',
            'products.product_name',
            'products.product_price',
            'products.image',
            'products.discount_percentage',
            DB::raw('CASE WHEN EXISTS(SELECT 1 FROM favorits WHERE favorits.product_id = products.id AND favorits.user_id = ' . ($user ? $user->id : 'NULL') . ' AND favorits.status = 1) THEN 1 ELSE 0 END AS is_favorite')
        )
            ->leftJoin('favorits', function ($join) use ($user) {
                if ($user) {
                    $join->on('products.id', '=', 'favorits.product_id')
                        ->where('favorits.user_id', '=', $user->id);
                } else {
                    $join->on('products.id', '=', 'favorits.product_id')
                        ->whereNull('favorits.user_id');
                }
            })
            ->where('products.product_status', 1)
            ->distinct('products.id'); // Add this line to ensure unique product IDs

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        $products = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($products, self::formatProductDataForDisplay($products->items()));
    }
    public static function getProductById($id)
    {
        $user = auth('sanctum')->user();
        $product = Product::select(
            'id',
            'product_name',
            'product_description',
            'category_id',
            'product_status',
            'product_price',
            'image',
            'discount_percentage',
            DB::raw('CASE WHEN EXISTS(SELECT 1 FROM favorits WHERE favorits.product_id = products.id AND favorits.user_id = ' . ($user ? $user->id : 'NULL') . ' AND favorits.status = 1) THEN 1 ELSE 0 END AS is_favorite')
        )
            ->where('products.id', $id)
            ->where('products.product_status', 1)
            ->first();

        return self::formatProductData($product);
    }
    private static function formatProductData($product)
    {
        return [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'categorie_name' => $product->Category->category_name,
            'product_description' => $product->product_description,
            'old_product_price' => number_format($product->product_price, 2),
            'discount_percentage' => $product->discount_percentage,
            'new_product_price' => number_format($product->product_price - ($product->product_price * ($product->discount_percentage / 100)), 2),
            'is_favorite' => $product->is_favorite,
            'image' => is_array($product->image) ? array_map(function ($image) {
                return url('storage', $image);
            }, $product->image) : [url('storage', $product->image)],
        ];
    }
    public static function formatProductDataForDisplay($products)
    {
        return array_map(function ($product) {
            $images = is_array($product->image) ? $product->image : [$product->image];
            // $firstImage = url('storage/', $images[0]);
            // $firstImage = $images[0];
            $firstImage = str_starts_with($images[0], 'Product/') ? url('storage', $images[0]) : $images[0];
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_price' => number_format($product->product_price, 2),
                'is_favorite' => $product->is_favorite,
                'discount_percentage' => $product->discount_percentage,
                'new_product_price' => number_format($product->product_price - ($product->product_price * ($product->discount_percentage / 100)), 2),
                'image' => $firstImage,
            ];
        }, $products);
    }
}
