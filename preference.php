<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$dotenv->load();
// Agrega credenciales
MercadoPago\SDK::setAccessToken($_ENV['ACCESS_TOKEN']);

$installment = 6;

// Creamos un objeto de preferencia
$preference = new MercadoPago\Preference();

// Creamos el ítem en la preferencia
$item = new MercadoPago\Item();
$item->id = '1234';
$item->title = $_POST['title'];
$item->description = 'Dispositivo móvil de Tienda e-commerce';
$item->picture_url = $_POST['img'];
$item->quantity = $_POST['unit'];
$item->unit_price = floatval($_POST['price']);
$item->currency_id = 'ARS';
$preference->items = array($item);

// Creamos un payer
$payer = new MercadoPago\Payer();
$payer->email = 'test_user_36961754@testuser.com';
$payer->name = 'Lalo';
$payer->surname = 'Landa';
$payer->phone = [
    'area_code' => '264',
    'number' => '5247697'
];
$payer->address = [
    'zip_code' => '5400',
    'street_name' => 'Calle Falsa',
    'street_number' => '123'
];

$preference->payer = $payer;

//Setup payment methods
$preference->payment_methods = [
    "excluded_payment_methods" => [
        ["id" => "visa"]
    ],
    "installments" => $installment,
];

// setup urls
$preference->notification_url = 'https://certificacion-mp.ngrok.io/webhook.php';
$preference->back_urls = [
    "success" => "https://certificacion-mp.ngrok.io/success.php",
    "failure" => "https://certificacion-mp.ngrok.io/failure.php",
    "pending" => "https://certificacion-mp.ngrok.io/pending.php"
];
$preference->auto_return = "approved";

// setup additional info
$preference->external_reference= 'matiasgallastegui89@gmail.com';
$preference->statement_descriptor = 'Tienda-Azul';
$preference->additional_info = 'dev_24c65fb163bf11ea96500242ac130004';

$preference->save();
echo $preference->id;
exit;
