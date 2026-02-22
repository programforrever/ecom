<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductStock;

class CheckVariantStock extends Command
{
    protected $signature = 'stock:check-variants {--product-id=22}';
    protected $description = 'Check variant stock for a product';

    public function handle()
    {
        $productId = $this->option('product-id');
        $product = Product::find($productId);
        
        if (!$product) {
            $this->error("Product not found");
            return;
        }
        
        $this->info("=== Product: {$product->name} ===");
        $this->line("ID: {$product->id}");
        $this->line("Unit Price: {$product->unit_price}");
        $this->line("Variant Product: " . ($product->variant_product ? 'Yes' : 'No'));
        $this->line("");
        
        $stocks = ProductStock::where('product_id', $productId)->get();
        
        if ($stocks->isEmpty()) {
            $this->error("No stock records found");
            return;
        }
        
        $this->info("Stocks (" . count($stocks) . "):");
        foreach ($stocks as $stock) {
            $this->line("  - Variant: {$stock->variant}");
            $this->line("    SKU: {$stock->sku}");
            $this->line("    Price: {$stock->price}");
            $this->line("    Qty: {$stock->qty}");
            $this->line("    Image: {$stock->image}");
            $this->line("");
        }
    }
}
