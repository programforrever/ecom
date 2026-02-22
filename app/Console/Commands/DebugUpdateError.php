<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugUpdateError extends Command
{
    protected $signature = 'debug:update-error';
    protected $description = 'Debug product update error';

    public function handle()
    {
        try {
            $this->info('=== Testing Product Update ===');
            
            // Check tables
            $this->info('Checking tables...');
            $tables = \DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ecommerce'");
            $tableNames = array_map(function($t) { return $t->TABLE_NAME; }, $tables);
            
            $requiredTables = ['products', 'product_stocks', 'product_taxes', 'shops'];
            foreach ($requiredTables as $table) {
                if (in_array($table, $tableNames)) {
                    $this->line("✓ Table '$table' exists");
                } else {
                    $this->error("✗ Table '$table' MISSING");
                }
            }
            
            // Try to load a product
            $this->info('');
            $this->info('Testing product loading...');
            $product = \App\Models\Product::find(22);
            if ($product) {
                $this->line("✓ Product ID 22 loaded: {$product->name}");
                $this->line("  - ID: {$product->id}");
                $this->line("  - added_by: {$product->added_by}");
                
                // Check relationships
                $stocks = $product->stocks()->count();
                $this->line("  - Stocks: {$stocks}");
                
                $taxes = $product->taxes()->count();
                $this->line("  - Taxes: {$taxes}");
                
            } else {
                $this->error("✗ Product ID 22 not found");
            }
            
            // Test ProductStockService
            $this->info('');
            $this->info('Testing ProductStockService...');
            try {
                $service = new \App\Services\ProductStockService();
                $this->line("✓ ProductStockService loads correctly");
            } catch (\Exception $e) {
                $this->error("✗ Error loading ProductStockService: {$e->getMessage()}");
            }
            
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            $this->error($e->getTraceAsString());
        }
    }
}
