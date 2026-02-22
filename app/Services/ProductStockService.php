<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;

class ProductStockService
{
    public function store(array $data, $product)
    {
        $collection = collect($data);

        // Ensure unit_price exists
        if (!isset($collection['unit_price']) || $collection['unit_price'] === null) {
            $collection['unit_price'] = $product->unit_price ?? 0;
        }

        $options = ProductUtility::get_attribute_options($collection);
        
        //Generates the combinations of customer choice options
        $combinations = (new CombinationService())->generate_combination($options);
        
        $variant = '';
        if (count($combinations) > 0) {
            $product->variant_product = 1;
            $product->save();
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                $qty_key = 'qty_' . str_replace('.', '_', $str);
                $price_key = 'price_' . str_replace('.', '_', $str);
                $sku_key = 'sku_' . str_replace('.', '_', $str);
                $img_key = 'img_' . str_replace('.', '_', $str);
                
                $product_stock = new ProductStock();
                $product_stock->product_id = $product->id;
                $product_stock->variant = $str;
                
                // Try to get from collection first, then request, then fallback to defaults
                $product_stock->price = $collection[$price_key] ?? request()[$price_key] ?? $collection['unit_price'];
                $product_stock->sku = $collection[$sku_key] ?? request()[$sku_key] ?? '';
                $product_stock->qty = $collection[$qty_key] ?? request()[$qty_key] ?? 0;
                $product_stock->image = $collection[$img_key] ?? request()[$img_key] ?? '';
                
                $product_stock->save();
            }
        } else {
            unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
            $qty = $collection['current_stock'] ?? 0;
            $price = $collection['unit_price'];
            unset($collection['current_stock']);

            $data = $collection->merge(compact('variant', 'qty', 'price'))->toArray();
            
            ProductStock::create($data);
        }
    }

    public function product_duplicate_store($product_stocks , $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product_new->id;
            $product_stock->variant     = $stock->variant;
            $product_stock->price       = $stock->price;
            $product_stock->sku         = $stock->sku;
            $product_stock->qty         = $stock->qty;
            $product_stock->save();
        }
    }
}
