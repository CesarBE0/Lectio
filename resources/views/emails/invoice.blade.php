<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura de Lectio</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 40px 0; color: #000000;">

<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb;">
    <tr>
        <td style="background-color: #0a0a0a; padding: 40px 30px; text-align: center;">
            <img src="{{ $message->embed(public_path('img/logo.png')) }}" alt="Lectio Logo" width="80" style="display: block; margin: 0 auto;">
            <h1 style="color: #D4AF37; font-family: Georgia, serif; letter-spacing: 4px; text-transform: uppercase; margin-top: 20px; font-size: 24px;">Factura Comercial</h1>
        </td>
    </tr>

    <tr>
        <td style="padding: 30px;">
            <p style="font-size: 16px; margin-bottom: 5px;">Hola,</p>
            <p style="font-size: 16px; color: #4b5563; margin-top: 0;">Gracias por tu compra en Lectio. Aquí tienes los detalles de tu pedido.</p>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px; margin-bottom: 30px; border-top: 2px solid #0a0a0a; border-bottom: 2px solid #0a0a0a; padding: 15px 0;">
                <tr>
                    <td style="width: 50%;">
                        <strong style="font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px;">Nº de Pedido</strong><br>
                        <span style="font-size: 16px; font-weight: bold;">{{ $orderNumber }}</span>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <strong style="font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px;">Fecha</strong><br>
                        <span style="font-size: 16px;">{{ date('d/m/Y') }}</span>
                    </td>
                </tr>
            </table>

            <h3 style="font-family: Georgia, serif; font-size: 18px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">Artículos Adquiridos</h3>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                @foreach($cartItems as $item)
                    <tr>
                        <td style="padding: 15px 0; border-bottom: 1px solid #f3f4f6;">
                            <strong style="font-size: 15px;">{{ $item['title'] ?? 'Libro Exclusivo' }}</strong><br>
                            <span style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px;">{{ $item['format'] ?? 'Formato Físico' }}</span>
                        </td>
                        <td style="padding: 15px 0; border-bottom: 1px solid #f3f4f6; text-align: right; font-weight: bold; font-size: 15px;">
                            {{ isset($item['price']) ? number_format($item['price'], 2, ',', '.') : '0,00' }}€
                        </td>
                    </tr>
                @endforeach
            </table>

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align: right; padding-bottom: 10px; color: #6b7280; font-size: 14px;">Subtotal:</td>
                    <td style="text-align: right; padding-bottom: 10px; font-size: 14px; width: 100px;">
                        {{ number_format($subtotal, 2, ',', '.') }}€
                    </td>
                </tr>

                @if($discountAmount > 0)
                    <tr>
                        <td style="text-align: right; padding-bottom: 10px; color: #D4AF37; font-size: 14px; font-weight: bold;">Descuento Aplicado:</td>
                        <td style="text-align: right; padding-bottom: 10px; font-size: 14px; width: 100px; color: #D4AF37; font-weight: bold;">
                            -{{ number_format($discountAmount, 2, ',', '.') }}€
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="text-align: right; padding-bottom: 15px; color: #6b7280; font-size: 14px;">Envío Premium:</td>
                    <td style="text-align: right; padding-bottom: 15px; font-size: 14px; width: 100px;">
                        {{ $shipping == 0 ? '0,00€' : number_format($shipping, 2, ',', '.') . '€' }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-top: 15px; border-top: 1px solid #e5e7eb; font-weight: bold; font-size: 20px;">Total:</td>
                    <td style="text-align: right; padding-top: 15px; border-top: 1px solid #e5e7eb; font-weight: bold; font-size: 20px; color: #D4AF37;">
                        {{ number_format($total, 2, ',', '.') }}€
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af;">
            Lectio - Tu Boutique Literaria<br>
            Este es un recibo automático generado tras el cobro exitoso mediante Stripe.
        </td>
    </tr>
</table>

</body>
</html>
