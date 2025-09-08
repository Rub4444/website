<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cancellationComment;

    public function __construct(Order $order, $cancellationComment)
    {
        $this->order = $order;
        $this->cancellationComment = $cancellationComment;
    }

    public function build()
    {
        return $this->subject('Ձեր պատվերը չեղարկվել է')
                    ->view('mail.order_cancelled')
                    ->with([
                        'order' => $this->order,
                        'cancellationComment' => $this->cancellationComment,
                    ]);
    }
}
