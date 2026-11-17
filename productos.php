<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
require_once "conexion.php";
$resultado = $conexion->query("SELECT id, nombre, precio, cantidad FROM productos ORDER BY id");
$productos = $resultado->fetch_all(MYSQLI_ASSOC);
$mensaje = $_SESSION['mensaje'] ?? "";
unset($_SESSION['mensaje']);
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
        <a href="carrito.php">Mi carrito (<?= array_sum($_SESSION['carrito'] ?? []) ?>)</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>
<main class="contenedor">
    <h2>Productos disponibles</h2>
    <?php if ($mensaje): ?><p class="mensaje"><?= htmlspecialchars($mensaje) ?></p><?php endif; ?>
    <?php if (count($productos) === 0): ?>
        <p>No hay productos registrados en la base de datos.</p>
    <?php else: ?>
    <div class="productos">
        <?php foreach ($productos as $producto): ?>
        <article class="producto">
            <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
            <p class="precio">$<?= number_format((float)$producto['precio'], 2) ?></p>
            <p>Existencias: <?= (int)$producto['cantidad'] ?></p>
            <?php if ((int)$producto['cantidad'] > 0): ?>
            <form action="agregar.php" method="POST">
                <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
                <label for="cantidad-<?= (int)$producto['id'] ?>">Cantidad:</label>
                <input type="number" id="cantidad-<?= (int)$producto['id'] ?>" name="cantidad" value="1" min="1" max="<?= (int)$producto['cantidad'] ?>" required>
                <button type="submit">Agregar al carrito</button>
            </form>
            <?php else: ?><p class="agotado">Producto agotado</p><?php endif; ?>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>
</body>
</html>
