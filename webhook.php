<?php
require __DIR__ .  '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

MercadoPago\SDK::setAccessToken($_ENV['ACCESS_TOKEN']);

if (!isset($_GET['topic'])) {
    http_response_code(500);
    exit;
}

try {
    $payment = MercadoPago\Payment::find_by_id($_GET['id']);

    switch ($_GET['topic']) {
        case 'payment':
            switch ($payment->status) {
                // Persistiria el webhook y actualizaría mi sistema
                case 'approved':
                    $description = "El pago ha sido aprobado.";
                    break;
                case 'in_process':
                    $description = "El pago está siendo revisado.";
                    break;
                case 'pending':
                    $description = "El pago está pendiente.";
                    break;
                case 'rejected':
                    $description = "El pago ha sido rechazado.";
                    break;
                case 'cancelled':
                    $description = "El pago ha sido cancelado por una acción del usuario o por expiración del tiempo de pago.";
                    break;
                case 'refunded':
                    $description = "El pago ha sido reembolsado al usuario.";
                    break;
                case 'charged_back':
                    $description = "Se ha realizado un contracargo en la tarjeta de crédito del comprador.";
                    break;
                default:
                    $description = "Estado de pago desconocido.";
                    error_log('Estado de pago desconocido: ' . $payment->status);
                    break;
            }

        default:
            error_log('Notificación no manejada o desconocida, Topic: ' . $_GET['topic']);
    }
    error_log($description);
    // Confirmamos la recepción de la notificación
    http_response_code(201);
    exit;
} catch (\Exception $e) {
    error_log($e->getMessage());
}
