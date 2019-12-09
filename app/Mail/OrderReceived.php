<?php

namespace App\Mail;

use App\User;
use App\Order;
use App\OrderSummary;
use App\SpecialOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $items;
    public $charges;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, OrderSummary $order, Order $items, Charges $charges)
    {
        $this->user = $user;
        $this->order = $order;
        $this->items = $items;
        $this->charges = $charges;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('Blessing@blemadeliveries.com')
                    -> view('emails.order_received');
    }
}
