<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class TestGetPosUserCart extends Command
{
    protected $signature = 'test:get-pos-cart {--temp_id=} {--user_id=}';
    protected $description = 'Test get_pos_user_cart function';

    public function handle()
    {
        $tempId = $this->option('temp_id');
        $userId = $this->option('user_id');

        $this->info("Testing get_pos_user_cart with user_id={$userId}, temp_user_id={$tempId}");

        // Simulate the function
        $authUser = User::find(13); // Assuming user 13 is admin
        $ownerId = $authUser->user_type == 'admin' ? User::where('user_type', 'admin')->first()->id : $authUser->id;

        $this->info("Owner ID: {$ownerId}");
        
        // Build query with proper NULL handling
        $query = Cart::where('owner_id', $ownerId);
        
        if ($userId === null || $userId === '') {
            $query->whereNull('user_id');
            $this->info("Using whereNull for user_id");
        } else {
            $query->where('user_id', $userId);
            $this->info("Using where user_id = {$userId}");
        }
        
        if ($tempId === null || $tempId === '') {
            $query->whereNull('temp_user_id');
            $this->info("Using whereNull for temp_user_id");
        } else {
            $query->where('temp_user_id', $tempId);
            $this->info("Using where temp_user_id = {$tempId}");
        }
        
        $carts = $query->get();
        
        $this->info("Found " . count($carts) . " carts:");
        foreach ($carts as $cart) {
            $this->line("  - ID: {$cart->id}, Product: {$cart->product->name}, Qty: {$cart->quantity}");
        }
    }
}
