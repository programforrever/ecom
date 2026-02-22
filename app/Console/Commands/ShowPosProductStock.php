<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductStock;

class ShowPosProductStock extends Command
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'pos:show-stock {--keyword= : Filter by product name}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show all admin products stock for POS';

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
        $query = Product::where('added_by', 'admin')
            ->where('digital', 0)
            ->where('auction_product', 0)
            ->where('wholesale_product', 0);

        if ($this->option('keyword')) {
            $query->where('name', 'like', '%' . $this->option('keyword') . '%');
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->error('No products found');
            return 1;
        }

        $this->info('=== POS Admin Products Stock ===');
        $this->info('');

        foreach ($products as $product) {
            $this->line("📦 Product: <fg=green>{$product->name}</>");
            $this->line("   ID: {$product->id}");
            $this->line("   Min Qty: {$product->min_qty}");
            $this->line("   Published: " . ($product->published ? 'Yes' : 'No'));
            $this->line("   Approved: " . ($product->approved ? 'Yes' : 'No'));
            
            $stocks = ProductStock::where('product_id', $product->id)->get();
            
            if ($stocks->isEmpty()) {
                $this->line("   <fg=red>❌ NO STOCK RECORDS</>");
            } else {
                $this->line("   Stock Records:");
                foreach ($stocks as $stock) {
                    $stockStatus = $stock->qty > 0 ? '<fg=green>✓</>' : '<fg=red>✗</>';
                    $this->line("   - Variant: {$stock->variant} | Qty: {$stock->qty} $stockStatus | SKU: {$stock->sku}");
                }
            }
            
            $totalQty = $stocks->sum('qty');
            $this->line("   <fg=cyan>Total Stock: $totalQty</>");
            $this->line('');
        }

        return 0;
    }
}
