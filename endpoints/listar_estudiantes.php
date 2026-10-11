<?php
require_once __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET, OPTIONS');
    responder(405, ['ok' => false, 'mensaje' => 'Método no permitido. Utiliza GET.']);
}

$db = conectar();
try {
    $sql = 'SELECT e.id, e.nombres, e.apellidos, e.email, e.telefono,
                   e.fecha_nacimiento, e.carrera_id, c.nombre AS carrera
            FROM estudiantes e
            INNER JOIN carreras c ON c.id = e.carrera_id
            ORDER BY e.id DESC';
    $estudiantes = $db->run($sql)->fetchAll();
    responder(200, ['ok' => true, 'total' => count($estudiantes), 'datos' => $estudiantes]);
} catch (PDOException $e) {
    error_log('Error al listar estudiantes: ' . $e->getMessage());
    responder(500, ['ok' => false, 'mensaje' => 'Ocurrió un error al consultar los estudiantes.']);
}
?>
