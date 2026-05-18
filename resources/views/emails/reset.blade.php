<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperar Contraseña - Lectio</title>
</head>
<body style="background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 40px 20px;">

<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">

    <tr>
        <td style="background-color: #D4AF37; height: 6px;"></td>
    </tr>

    <tr>
        <td style="padding: 40px 30px; text-align: center; background-color: #000000;">
            <img src="{{ $message->embed(public_path('img/logo.png')) }}" alt="Lectio Logo" style="width: 80px; height: auto; margin-bottom: 15px; display: inline-block;">
            <h1 style="color: #D4AF37; margin: 0; font-size: 32px; letter-spacing: 6px; text-transform: uppercase; font-weight: 900;">LECTIO</h1>
            <p style="color: #888888; font-size: 12px; margin-top: 10px; letter-spacing: 2px; text-transform: uppercase;">Seguridad de la Cuenta</p>
        </td>
    </tr>

    <tr>
        <td style="padding: 40px 30px; color: #333333; line-height: 1.6;">
            <h2 style="margin-top: 0; font-size: 22px; color: #000000;">¡Hola, {{ $user->name ?? 'usuario' }}!</h2>

            <p style="font-size: 16px; color: #555555; margin-bottom: 30px;">
                Has recibido este correo electrónico porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Lectio</strong>.
            </p>

            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ $url }}" style="background-color: #000000; color: #D4AF37; text-decoration: none; padding: 18px 36px; border-radius: 8px; font-weight: bold; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: inline-block; border: 1px solid #D4AF37;">
                    Restablecer Contraseña
                </a>
            </div>

            <p style="font-size: 14px; color: #777777; margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 20px;">
                Este enlace para restablecer la contraseña caducará en 60 minutos.<br><br>
                Si no has solicitado un cambio de contraseña, no es necesario que realices ninguna acción y tu cuenta seguirá estando segura.
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color: #f9fafb; padding: 20px 30px; text-align: center; font-size: 12px; color: #9ca3af;">
            <p style="margin: 0; font-weight: bold;">&copy; {{ date('Y') }} Lectio. Todos los derechos reservados.</p>
            <p style="margin: 5px 0 0 0;">Este es un mensaje automático, por favor no respondas a este correo.</p>
        </td>
    </tr>
</table>

</body>
</html>
