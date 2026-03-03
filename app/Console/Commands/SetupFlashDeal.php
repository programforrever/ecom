<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;

class SetupFlashDeal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:flash-deal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup a featured flash deal with products for testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get some active products (approved and published)
        $products = Product::where('approved', 1)
            ->where('published', 1)
            ->limit(3)
            ->get();

        if ($products->count() === 0) {
            $this->error("No active products found.");
            return Command::FAILURE;
        }

        $this->info("Found " . $products->count() . " products");

        // Check if a featured flash deal exists and is current
        $existing_deal = FlashDeal::where('featured', 1)
            ->where('status', 1)
            ->where('start_date', '<=', strtotime(date('Y-m-d H:i:s')))
            ->where('end_date', '>=', strtotime(date('Y-m-d H:i:s')))
            ->first();

        if ($existing_deal) {
            $this->info("Featured flash deal already exists: ID {$existing_deal->id}");
            $flash_deal = $existing_deal;
        } else {
            // Create a new flash deal
            $flash_deal = new FlashDeal();
            $flash_deal->title = 'Ofertas del Día';
            $flash_deal->status = 1;
            $flash_deal->featured = 1;
            $flash_deal->text_color = '#222';
            $flash_deal->background_color = '#fff';
            $flash_deal->slug = 'ofertas-del-dia-' . \Str::random(5);
            
            // Set dates (start now, end in 7 days from now)
            $start_time = date('Y-m-d H:i:s');
            $end_time = date('Y-m-d H:i:s', strtotime('+7 days'));
            
            $flash_deal->start_date = strtotime($start_time);
            $flash_deal->end_date = strtotime($end_time);
            
            $flash_deal->save();
            $this->info("Created new flash deal with ID: {$flash_deal->id}");
        }

        // Check if products are already linked
        $product_count = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->count();
        $this->info("Flash deal already has {$product_count} products");

        if ($product_count === 0) {
            // Link products to the flash deal
            foreach ($products as $product) {
                $flash_deal_product = new FlashDealProduct();
                $flash_deal_product->flash_deal_id = $flash_deal->id;
                $flash_deal_product->product_id = $product->id;
                $flash_deal_product->discount = 10; // 10% discount
                $flash_deal_product->discount_type = 'percent';
                $flash_deal_product->save();
                $this->info("Added product ID {$product->id} to flash deal");
            }
        }

        $this->info("\n✓ Flash deal setup complete!");
        $this->info("Flash Deal ID: {$flash_deal->id}");
        $this->info("Products in deal: " . FlashDealProduct::where('flash_deal_id', $flash_deal->id)->count());
        
        return Command::SUCCESS;
    }
}
