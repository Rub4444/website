<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Illuminate\Mail\Mailables\Envelope;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $order;

    /**
     * Create a new message instance.
     */
    public function __construct($name, Order $order)
    {
        $this->name = $name;
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $fullSum = $this->order->getFullSum();
        return $this->view('mail.order_created')->with([
            'name' => $this->name,
            'fullSum' => $fullSum,
            'order' => $this->order
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Պատվերը հաստատված է',
        );
    }
}
