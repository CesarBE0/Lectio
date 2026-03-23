<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Lectio</title>
    <style>
        /* Reset básico para correos */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; background-color: #0a0a0a; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }

        /* Clases principales */
        .wrapper { width: 100%; background-color: #0a0a0a; padding-top: 40px; padding-bottom: 40px; }
        .main-table { background-color: #000000; max-width: 600px; margin: 0 auto; width: 100%; border: 1px solid #D4AF37; border-radius: 8px; overflow: hidden; }

        /* Cabecera */
        .header { padding: 40px 20px; text-align: center; border-bottom: 1px solid #222222; }
        .header h1 { color: #D4AF37; font-family: 'Georgia', Times, serif; font-size: 36px; margin: 0; letter-spacing: 6px; text-transform: uppercase; font-weight: normal; }
        .header .logo { width: 80px; height: auto; margin-bottom: 15px; display: inline-block; }

        /* Contenido */
        .content { padding: 50px 40px; text-align: center; }
        .content h2 { color: #ffffff; font-family: 'Georgia', Times, serif; font-size: 24px; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
        .content h2 span { color: #D4AF37; font-style: italic; }
        .content p { color: #b3b3b3; font-size: 15px; line-height: 1.8; margin-bottom: 30px; }

        /* Botón */
        .button-container { margin: 40px 0; }
        .button { background-color: #D4AF37; color: #000000 !important; text-decoration: none; padding: 16px 36px; font-weight: 900; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; border-radius: 4px; display: inline-block; }

        /* Cita final */
        .quote { font-family: 'Georgia', Times, serif; font-style: italic; color: #666666; font-size: 16px; margin-top: 30px; border-top: 1px solid #222222; padding-top: 30px; }

        /* Footer */
        .footer { padding: 30px 20px; text-align: center; background-color: #050505; border-top: 1px solid #D4AF37; }
        .footer p { color: #555555; font-size: 12px; margin: 5px 0; line-height: 1.5; }
        .footer a { color: #D4AF37; text-decoration: none; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0a0a0a;">
<center class="wrapper">
    <table class="main-table" width="100%" cellpadding="0" cellspacing="0" role="presentation">

        <tr>
            <td class="header">
                <img src="{{ $message->embed(public_path('img/opcion1.png')) }}" alt="Lectio Logo" class="logo" style="width: 80px; margin-bottom: 15px; display: inline-block;">

                <h1>LECTIO</h1>
            </td>
        </tr>

        <tr>
            <td class="content">
                <h2>Bienvenido/a al club, <br><span>{{ $user->name }}</span></h2>

                <p>Nos emociona abrirte las puertas de nuestra exclusiva librería. En <strong>Lectio</strong>, creemos que cada libro es una joya y cada lector merece una experiencia de primera clase.</p>

                <p>Tu cuenta ha sido creada con éxito. A partir de ahora podrás guardar tus formatos favoritos, acceder a ediciones especiales y disfrutar de nuestro envío premium para pedidos superiores a 50€.</p>

                <div class="button-container">
                    <a href="{{ route('catalogo') }}" class="button">Explorar la Colección</a>
                </div>

                <p class="quote">"Un lector vive mil vidas antes de morir. El que nunca lee, solo vive una."</p>
            </td>
        </tr>

        <tr>
            <td class="footer">
                <p>&copy; {{ date('Y') }} Lectio. Todos los derechos reservados.</p>
                <p>Has recibido este correo porque te has registrado en nuestra plataforma.</p>
                <p><a href="{{ route('home') }}">Visitar la tienda</a></p>
            </td>
        </tr>

    </table>
</center>
</body>
</html>
