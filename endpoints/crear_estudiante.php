<?php
require_once __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST, OPTIONS');
    responder(405, ['ok' => false, 'mensaje' => 'Método no permitido. Utiliza POST.']);
}

$datos = leer_json();
$nombres = trim((string)($datos['nombres'] ?? ''));
$apellidos = trim((string)($datos['apellidos'] ?? ''));
$email = trim((string)($datos['email'] ?? ''));
$telefono = trim((string)($datos['telefono'] ?? ''));
$fechaNacimiento = trim((string)($datos['fecha_nacimiento'] ?? ''));
$carreraId = filter_var($datos['carrera_id'] ?? null, FILTER_VALIDATE_INT);

$errores = [];
if (!Validaciones::verificar_solo_letras($nombres)) $errores['nombres'] = 'Es obligatorio y debe contener hasta 50 letras.';
if (!Validaciones::verificar_solo_letras($apellidos)) $errores['apellidos'] = 'Es obligatorio y debe contener hasta 50 letras.';
if (!Validaciones::validar_email($email)) $errores['email'] = 'Introduce un correo electrónico válido.';
if ($telefono !== '' && !Validaciones::validar_telefono($telefono)) $errores['telefono'] = 'El teléfono debe contener 8 dígitos, por ejemplo 22223333.';
if ($fechaNacimiento !== '' && !Validaciones::verificar_fecha($fechaNacimiento, 'Y-m-d')) $errores['fecha_nacimiento'] = 'Usa una fecha real con formato AAAA-MM-DD.';
if ($carreraId === false || $carreraId === null || $carreraId < 1) $errores['carrera_id'] = 'Debe ser un ID de carrera entero positivo.';

if ($errores) responder(422, ['ok' => false, 'mensaje' => 'Hay datos inválidos.', 'errores' => $errores]);

$db = conectar();
try {
    $verificar = $db->run('SELECT id FROM carreras WHERE id = ?', [$carreraId])->fetch();
    if (!$verificar) responder(422, ['ok' => false, 'mensaje' => 'La carrera indicada no existe.']);

    $sql = 'INSERT INTO estudiantes (nombres, apellidos, email, telefono, fecha_nacimiento, carrera_id)
            VALUES (:nombres, :apellidos, :email, :telefono, :fecha_nacimiento, :carrera_id)';
    $db->run($sql, [
        ':nombres' => $nombres,
        ':apellidos' => $apellidos,
        ':email' => $email,
        ':telefono' => $telefono !== '' ? $telefono : null,
        ':fecha_nacimiento' => $fechaNacimiento !== '' ? $fechaNacimiento : null,
        ':carrera_id' => $carreraId,
    ]);

    responder(201, [
        'ok' => true,
        'mensaje' => 'Estudiante creado correctamente.',
        'datos' => [
            'id' => (int)$db->lastInsertId(),
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'email' => $email,
            'telefono' => $telefono !== '' ? $telefono : null,
            'fecha_nacimiento' => $fechaNacimiento !== '' ? $fechaNacimiento : null,
            'carrera_id' => (int)$carreraId,
        ],
    ]);
} catch (PDOException $e) {
    error_log('Error al crear estudiante: ' . $e->getMessage());
    if ($e->getCode() === '23000') responder(409, ['ok' => false, 'mensaje' => 'El correo ya existe o hay una referencia inválida.']);
    responder(500, ['ok' => false, 'mensaje' => 'Ocurrió un error al guardar el estudiante.']);
}
?>
