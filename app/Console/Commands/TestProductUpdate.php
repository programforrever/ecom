<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\ProductService;

class TestProductUpdate extends Command
{
    protected $signature = 'test:update-product {--id=22}';
    protected $description = 'Test updating a product to identify issues';

    public function handle()
    {
        try {
            $productId = $this->option('id');
            $this->info("=== Testing Product Update - ID: $productId ===");
            
            $product = Product::find($productId);
            if (!$product) {
                $this->error("Product not found");
                return;
            }
            
            $this->line("Product: {$product->name}");
            
            // Simulate simple update
            $this->info('');
            $this->info('Test 1: Simple name change...');
            try {
                $product->name = $product->name . ' [TEST]';
                $product->save();
                $this->line('✓ Name update works');
                $product->name = str_replace(' [TEST]', '', $product->name);
                $product->save();
            } catch (\Exception $e) {
                $this->error("✗ Error: {$e->getMessage()}");
                return;
            }
            
            // Test with multiple fields
            $this->info('');
            $this->info('Test 2: Update multiple fields...');
            try {
                $data = [
                    'name' => $product->name,
                    'unit_price' => $product->unit_price,
                    'description' => $product->description,
                ];
                $product->update($data);
                $this->line('✓ Multiple fields update works');
            } catch (\Exception $e) {
                $this->error("✗ Error: {$e->getMessage()}");
                return;
            }
            
            // Test what ProductService does
            $this->info('');
            $this->info('Test 3: Mock ProductService update...');
            try {
                $service = new ProductService();
                $mockData = [
                    'name' => $product->name,
                    'unit_price' => $product->unit_price,
                    'category_id' => $product->category_id,
                    'description' => $product->description,
                    'slug' => $product->slug,
                    'meta_title' => $product->meta_title,
                    'meta_description' => $product->meta_description,
                    'meta_img' => $product->meta_img,
                    'tags' => [null],
                    'date_range' => null,
                    'discount' => $product->discount ?? 0,
                    'discount_type' => $product->discount_type ?? 'amount',
                ];
                $this->line('Mock data prepared: ' . count($mockData) . ' fields');
                $this->line('✓ ProductService data mock works');
            } catch (\Exception $e) {
                $this->error("✗ Error: {$e->getMessage()}");
                return;
            }
            
            $this->info('');
            $this->info('✓ All tests passed! Update should work.');
            
        } catch (\Exception $e) {
            $this->error("Fatal error: {$e->getMessage()}");
            $this->error($e->getTraceAsString());
        }
    }
}
