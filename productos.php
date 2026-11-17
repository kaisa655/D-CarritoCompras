<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include "datos.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos disponibles</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
    <h1>Tienda Virtual</h1>
    <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>
    <nav>
        <a href="productos.php">Productos</a>
        <a href="carrito.php">Mi carrito</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>

<main class="contenedor">
    <h2>Productos disponibles</h2>
    <div class="productos">
        <?php foreach ($productos as $id => $producto): ?>
            <article class="producto">
                <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
                <form action="agregar.php" method="POST">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <label for="cantidad-<?= $id ?>">Cantidad:</label>
                    <input type="number" id="cantidad-<?= $id ?>"
                           name="cantidad" value="1" min="1" max="99" required>
                    <button type="submit">Agregar al carrito</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
