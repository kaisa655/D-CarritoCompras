<?php
// Conexion a MySQL mediante XAMPP.
$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "carritocompras";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli($servidor, $usuario, $password, $baseDatos);
    $conexion->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    die("No se pudo conectar con la base de datos. Verifica que MySQL esté iniciado en XAMPP y que exista la base de datos 'carritocompras'. Detalle: " . htmlspecialchars($e->getMessage()));
}
