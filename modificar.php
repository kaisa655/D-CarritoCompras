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

    if ($id && isset($productos[$id]) && isset($_SESSION['carrito'][$id])
        && $cantidad && $cantidad > 0 && $cantidad <= 99) {
        $_SESSION['carrito'][$id] = $cantidad;
    }
}

header("Location: carrito.php");
exit();
