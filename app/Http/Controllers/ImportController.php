<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Product;

class ImportController extends Controller
{
    public function getProduct($productId)
    {
        $product = Product::where('product_id', $productId)->first()
        ->only(
            'product_id',
            'product_code',
            'language',
            'category',
            'last_price',
            'price',
            'quantity',
            'product_name',
            'description',
            'seo_name',
            'short_description',
            'status',
            'vendor',
            'features',
        );
        
        if(!$product) return 'Not found';

        if($product['features']) {
            $product['features'] = array_reduce(explode('; ', $product['features']), function($carry, $item) {
                $carry[explode(": ", $item)[0]] = explode(": ", $item)[1];
                return $carry;
            });
        }

        return response()->json($product);
    }
}
