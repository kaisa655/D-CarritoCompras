<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include "datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);

    if ($id && isset($productos[$id]) && $cantidad && $cantidad > 0 && $cantidad <= 99) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $cantidadActual = $_SESSION['carrito'][$id] ?? 0;
        $_SESSION['carrito'][$id] = min(99, $cantidadActual + $cantidad);
    }
}

header("Location: carrito.php");
exit();
