<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Product;

class ImportController extends Controller
{
    public function index()
    {
        $start = microtime(true);
        // $data = \Storage::get('public/demo-data-small.csv');

        $filename = storage_path('app/public/demo-data-small.csv');
        
        $data = new \SpreadsheetReader($filename);

        $insert_data = new Collection();

        foreach ($data as $key => $attr) {
            if($key == 0) continue;

            // $features = array_reduce(explode('; ', $attr[13]), function($carry, $item) {
            //     $carry[explode(": ", $item)[0]] = explode(": ", $item)[1];
            //     return $carry;
            // });

            $insert_data->push([
                'product_id' => $attr[0],
                'product_code' => $attr[1],
                'language' => $attr[2],
                'category' => $attr[3],
                'last_price' => $attr[4],
                'price' => $attr[5],
                'quantity' => $attr[6],
                'product_name' => $attr[7],
                'description' => $attr[8],
                'seo_name' => $attr[9],
                'short_description' => $attr[10],
                'status' => $attr[11],
                'vendor' => $attr[12],
                'features' => $attr[13],
            ]);
        }

        // $content = array_filter($content, function($row, $i) use($data) {
        //     if($i == 0 || $i == count($data)-1) return false;
        //     return true;
        // }, ARRAY_FILTER_USE_BOTH);

        // $data = array_map(function($attr) {
            // $attr = explode('";"', $row);
            // return $attr;

            // $i = 0;
            // $features = array_reduce($attr, function($carry, $item) use(&$i, $attr) {
            //     if($i > 12) {
            //         try {
            //             $carry[explode(": ", $item)[0]] = explode(": ", $item)[1];
            //         } catch (\Throwable $th) {
            //             dd($attr);
            //             // return;
            //         }
            //     }
            //     $i++;
            //     return $carry;
            // });

        //     return [
        //         'product_id' => $attr[0],
        //         'product_code' => $attr[1],
        //         'language' => $attr[2],
        //         'category' => $attr[3],
        //         'last_price' => $attr[4],
        //         'price' => $attr[5],
        //         'quantity' => $attr[6],
        //         'product_name' => $attr[7],
        //         'description' => $attr[8],
        //         'seo_name' => $attr[9],
        //         'short_description' => $attr[10],
        //         'status' => $attr[11],
        //         'vendor' => $attr[12],
        //         'features' => json_encode($attr[13]),
        //     ];
        // }, $data);  

        $chunks = $insert_data->chunk(100);
        // Product::upsert($content, ['product_id']);

        foreach ($chunks as $key => $chunk) {
            Product::upsert($chunk->toArray(), ['product_id']);
            // Product::insert($chunk->toArray());
        }

        $end = microtime(true) - $start;
        echo $end;

        // dd($chunks[0]);
    }

    public function getProduct($productId)
    {
        $product = Product::where('product_id', $productId)->first();
        // ->only(
        //     'id', 

        //     'features'
        // );
        
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
