<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include "datos.php";

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi carrito</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
    <h1>Mi carrito de compras</h1>
    <p>Usuario: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>
    <nav>
        <a href="productos.php">Productos</a>
        <a href="carrito.php">Mi carrito</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>

<main class="contenedor">
    <h2>Productos agregados</h2>

    <?php if (empty($carrito)): ?>
        <p>Tu carrito está vacío.</p>
        <a class="boton-enlace" href="productos.php">Ir a comprar</a>
    <?php else: ?>
        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($carrito as $id => $cantidad): ?>
                    <?php
                    if (!isset($productos[$id])) {
                        continue;
                    }
                    $producto = $productos[$id];
                    $subtotal = $producto['precio'] * $cantidad;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td>
                            <form class="form-inline" action="modificar.php" method="POST">
                                <input type="hidden" name="id" value="<?= (int)$id ?>">
                                <input type="number" name="cantidad"
                                       value="<?= (int)$cantidad ?>" min="1" max="99" required>
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <form action="eliminar.php" method="POST">
                                <input type="hidden" name="id" value="<?= (int)$id ?>">
                                <button type="submit" class="eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h2 class="total">Total: $<?= number_format($total, 2) ?></h2>
        <a class="boton-enlace" href="productos.php">Seguir comprando</a>
    <?php endif; ?>
</main>
</body>
</html>
