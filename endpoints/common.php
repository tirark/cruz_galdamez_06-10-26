<?php
require_once dirname(__DIR__) . '/conn.class.php';
require_once dirname(__DIR__) . '/validaciones.inc.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function responder(int $codigo, array $contenido): void {
    http_response_code($codigo);
    echo json_encode($contenido, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function leer_json(): array {
    $contenido = file_get_contents('php://input');
    $datos = json_decode($contenido, true);
    if (!is_array($datos)) {
        responder(400, ['ok' => false, 'mensaje' => 'El cuerpo debe ser un objeto JSON válido.']);
    }
    return $datos;
}

function conectar(): DB {
    try {
        return new DB();
    } catch (Throwable $e) {
        error_log('Error de conexión a la base de datos: ' . $e->getMessage());
        responder(500, ['ok' => false, 'mensaje' => 'No fue posible conectar con la base de datos. Verifica config.php y que MySQL esté activo.']);
    }
}
?>
