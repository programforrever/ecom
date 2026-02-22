<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductStock;

class FixPosProductStock extends Command
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'pos:fix-stock';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Fix product stock issues for POS - ensures added_by is set to admin and stocks are properly configured';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== POS Product Stock Fixer ===');
        $this->info('');

        // Fix products without added_by
        $this->info('Step 1: Fixing products without added_by field...');
        $productsWithoutAddedBy = Product::whereNull('added_by')->count();
        if ($productsWithoutAddedBy > 0) {
            Product::whereNull('added_by')->update(['added_by' => 'admin']);
            $this->info("✓ Fixed $productsWithoutAddedBy products");
        } else {
            $this->info('✓ All products have added_by field');
        }

        $this->info('');
        
        // Fix products without stock
        $this->info('Step 2: Checking product stocks...');
        $adminProducts = Product::where('added_by', 'admin')
            ->where('digital', 0)
            ->where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->get();

        $productsWithoutStock = 0;
        $totalProducts = count($adminProducts);

        foreach ($adminProducts as $product) {
            $stockCount = ProductStock::where('product_id', $product->id)->count();
            if ($stockCount == 0) {
                $productsWithoutStock++;
                $this->warn("⚠ Product '{$product->name}' (ID: {$product->id}) has no stock records");
            }
        }

        if ($productsWithoutStock > 0) {
            $this->error("Found $productsWithoutStock products without stock records out of $totalProducts admin products");
            $this->warn('Please create stock records for these products');
        } else {
            $this->info("✓ All $totalProducts admin products have stock records");
        }

        $this->info('');

        // Check for products with zero stock
        $this->info('Step 3: Checking products with zero stock...');
        $zeroStockProducts = ProductStock::where('qty', 0)
            ->whereHas('product', function ($q) {
                $q->where('added_by', 'admin');
            })
            ->count();

        if ($zeroStockProducts > 0) {
            $this->warn("⚠ Found $zeroStockProducts stock records with 0 quantity");
        } else {
            $this->info('✓ No products with zero stock');
        }

        $this->info('');
        $this->info('=== Fix complete! ===');
        $this->info('If you still have issues:');
        $this->info('1. Go to Admin > Products > In House');
        $this->info('2. Check if your products show up');
        $this->info('3. Click on a product and verify it has quantity > 0 in the stock section');
        $this->info('4. Make sure the product is published and approved');

        return 0;
    }
}
