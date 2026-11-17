<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: productos.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? "");

    if ($nombre !== "") {
        $_SESSION['usuario'] = $nombre;
        $_SESSION['carrito'] = [];
        header("Location: productos.php");
        exit();
    } else {
        $error = "Escribe tu nombre para continuar.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="contenedor login">
        <h1>Carrito de Compras</h1>
        <p>Bienvenido a nuestra tienda virtual</p>

        <?php if ($error): ?>
            <p class="mensaje error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <label for="nombre">Nombre del usuario:</label>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Escribe tu nombre" maxlength="80" required>
            <button type="submit">Ingresar</button>
        </form>
    </main>
</body>
</html>
