<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

MercadoPagoConfig::setAccessToken("TEST-2423581401859647-103013-dddfd709e20dae525bac135f797bc06c-1182385534");

$client = new PreferenceClient();

try {
    $preference = $client->create([
        "items" => [
            [
                "id" => "1234",
                "title" => "Nombre del producto",  // Cambiado de "name" a "title"
                "quantity" => 1,
                "unit_price" => 100.00,
                "currency_id" => "ARS",  // Especificar la moneda
            ]
        ],
        "statement_descriptor" => "Tienda Hists",
        "auto_return" => "approved",
        "payment_methods" => [
            "excluded_payment_types" => [
                ["id" => "ticket"], // Excluir tipos de pago si es necesario
            ],
            "installments" => 12 // Ejemplo de cuotas
        ],
    ]);

    if ($preference === null) {
        throw new Exception("La preferencia de Mercado Pago no se pudo crear.");
    }

    echo "Preferencia creada: " . $preference->id;

} catch (Exception $e) {
    echo "Error al crear la preferencia: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <div id="wallet_container"></div>
    <script>
        const mp= new MercadoPago('TEST-d381a9f3-b438-4d24-87f8-1a00307b4078',{
            locale: 'es-AR'
        });

        mp.bricks().create("wallet", "wallet_container",{
            initialization:{
                preferenceId: '<?php echo $preference->id;  ?>'
            }
        });
    </script>
</body>
</html>