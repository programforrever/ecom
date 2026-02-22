<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

class DebugCartSession extends Command
{
    protected $signature = 'debug:cart-session {--temp_id=} {--user_id=}';
    protected $description = 'Debug cart retrieval by simulating session values';

    public function handle()
    {
        $tempId = $this->option('temp_id');
        $userId = $this->option('user_id');
        
        $this->info("Simulating get_pos_user_cart() with user_id={$userId}, temp_user_id={$tempId}");
        
        // Simulate what get_pos_user_cart does
        $authUser = auth()->user() ?? \App\Models\User::find(1);
        $ownerId = $authUser->user_type == 'admin' ? \App\Models\User::where('user_type', 'admin')->first()->id : $authUser->id;
        
        $this->info("Auth user (simulated): {$authUser->name} (ID: {$authUser->id})");
        $this->info("Owner ID: {$ownerId}");
        
        // Query
        $carts = Cart::where('owner_id', $ownerId)
                     ->where('user_id', $userId)
                     ->where('temp_user_id', $tempId)
                     ->get();
        
        $this->info("Result: " . count($carts) . " carts found");
        
        foreach ($carts as $cart) {
            $this->info("  - Cart ID: {$cart->id}, Product: {$cart->product->name}, Qty: {$cart->quantity}");
        }
        
        // Try without user_id constraint
        if (count($carts) == 0 && $tempId) {
            $this->info("\nTrying query without user_id constraint:");
            $carts = Cart::where('owner_id', $ownerId)
                         ->where('temp_user_id', $tempId)
                         ->get();
            
            $this->info("Result: " . count($carts) . " carts found");
            foreach ($carts as $cart) {
                $this->info("  - Cart ID: {$cart->id}, Product: {$cart->product->name}, Qty: {$cart->quantity}");
            }
        }
    }
}
