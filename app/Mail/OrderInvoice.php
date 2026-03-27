<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $orderNumber;
    public $cartItems;
    public $subtotal;
    public $discountAmount;
    public $shipping;
    public $total;

    // Recibimos todos los datos de la compra desde el CheckoutController
    public function __construct($orderNumber, $cartItems, $subtotal, $discountAmount, $shipping, $total)
    {
        $this->orderNumber = $orderNumber;
        $this->cartItems = $cartItems;
        $this->subtotal = $subtotal;
        $this->discountAmount = $discountAmount;
        $this->shipping = $shipping;
        $this->total = $total;
    }

    public function build()
    {
        return $this->subject('Tu Factura de Lectio - Pedido ' . $this->orderNumber)
            ->view('emails.invoice');
    }
}
