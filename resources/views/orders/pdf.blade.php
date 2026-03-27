<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $orderNumber }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }

        .header {
            background-color: #0a0a0a;
            color: #D4AF37;
            padding: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: normal;
            font-family: Georgia, serif;
        }

        .details {
            margin-top: 40px;
            width: 100%;
            border-collapse: collapse;
        }

        .details td {
            padding: 5px 0;
        }

        .text-right {
            text-align: right;
        }

        .gray {
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .items-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .items-table th {
            border-bottom: 2px solid #0a0a0a;
            padding: 10px 0;
            text-align: left;
            text-transform: uppercase;
            font-size: 12px;
            color: #666;
            letter-spacing: 1px;
        }

        .items-table td {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .total-row td {
            border-top: 2px solid #0a0a0a;
            border-bottom: none;
            padding-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .gold {
            color: #D4AF37;
        }

        .footer {
            margin-top: 80px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Factura Comercial</h1>
    <p style="margin-top: 10px; font-size: 12px; color: #fff;">Lectio - Boutique Literaria</p>
</div>

<table class="details">
    <tr>
        <td width="50%">
            <div class="gray">Nº de Pedido</div>
            <strong>{{ $orderNumber }}</strong>
        </td>
        <td width="50%" class="text-right">
            <div class="gray">Fecha de Emisión</div>
            <strong>{{ $fechaCompra->format('d/m/Y') }}</strong>
        </td>
    </tr>
    <tr>
        <td width="50%" style="padding-top: 20px;">
            <div class="gray">Cliente</div>
            <strong>{{ $user->name }}</strong><br>
            {{ $user->email }}
        </td>
        <td width="50%" class="text-right" style="padding-top: 20px;">
            <div class="gray">Dirección de Entrega</div>
            <strong>{{ $book->pivot->address ?? 'No especificada' }}</strong><br>
            {{ $book->pivot->city ?? '' }}
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
    <tr>
        <th>Descripción del Artículo</th>
        <th>Formato</th>
        <th class="text-right">Importe Base</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><strong>{{ $book->title }}</strong></td>
        <td>{{ $book->pivot->format ?? 'Físico' }}</td>
        <td class="text-right">{{ number_format($book->pivot->price ?? 0, 2, ',', '.') }}€</td>
    </tr>

    @if(($book->pivot->discount ?? 0) > 0)
        <tr>
            <td colspan="2" class="text-right gray" style="padding-top: 15px;">Descuento Promocional:</td>
            <td class="text-right gold" style="padding-top: 15px;">
                -{{ number_format($book->pivot->discount, 2, ',', '.') }}€
            </td>
        </tr>
    @endif

    @if(($book->pivot->shipping ?? 0) > 0)
        <tr>
            <td colspan="2" class="text-right gray" style="padding-top: 15px;">Gastos de Envío:</td>
            <td class="text-right" style="padding-top: 15px;">+{{ number_format($book->pivot->shipping, 2, ',', '.') }}
                €
            </td>
        </tr>
    @endif

    <tr class="total-row">
        <td colspan="2" class="text-right" style="padding-right: 20px;">TOTAL PAGADO:</td>
        <td class="text-right gold">
            {{ number_format(($book->pivot->price ?? 0) - ($book->pivot->discount ?? 0) + ($book->pivot->shipping ?? 0), 2, ',', '.') }}
            €
        </td>
    </tr>
    </tbody>
</table>

<div class="footer">
    Este documento es un comprobante de compra generado electrónicamente.<br>
    Lectio Inc. - Todos los derechos reservados.
</div>

</body>
</html>
