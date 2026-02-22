<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductStock;
use App\Models\Cart;
use App\Utility\PosUtility;
use App\Utility\CartUtility;

class DebugPosAddCart extends Command
{
    protected $signature = 'debug:pos-add-cart {stock_id} {--user_id=} {--temp_id=test123}';
    protected $description = 'Debug the addToCart function';

    public function handle()
    {
        $stockId = $this->argument('stock_id');
        $userId = $this->option('user_id');
        $tempId = $this->option('temp_id');

        $this->info("Testing addToCart with stock_id=$stockId");
        $this->info("userId=$userId, tempId=$tempId");

        // Test 1: First add
        $this->info("\n--- First Add Test ---");
        Cart::where('product_id', ProductStock::find($stockId)->product_id)
             ->where('temp_user_id', $tempId)
             ->delete();

        $response = PosUtility::addToCart($stockId, $userId, $tempId);
        $this->info("Response: " . json_encode($response));

        $cart = Cart::where('product_id', ProductStock::find($stockId)->product_id)
                    ->where('temp_user_id', $tempId)
                    ->first();
        if ($cart) {
            $this->info("Cart created with quantity: " . $cart->quantity);
        } else {
            $this->error("NO CART CREATED!");
        }

        // Test 2: Second add
        $this->info("\n--- Second Add Test ---");
        $response = PosUtility::addToCart($stockId, $userId, $tempId);
        $this->info("Response: " . json_encode($response));

        $cart = Cart::where('product_id', ProductStock::find($stockId)->product_id)
                    ->where('temp_user_id', $tempId)
                    ->first();
        if ($cart) {
            $this->info("Cart quantity now: " . $cart->quantity);
        }
    }
}
