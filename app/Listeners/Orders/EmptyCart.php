<?php

namespace App\Listeners\Orders;

use App\Cart\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmptyCart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(public Cart $cart)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->cart->empty();
    }
}
