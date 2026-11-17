
<?php

include "conexion.php";

$resultado = $conexion->query("SELECT * FROM productos");

if ($resultado) {

    echo "<h1>Productos de la base de datos</h1>";

    while ($producto = $resultado->fetch_assoc()) {

        echo "Producto: " . htmlspecialchars($producto['nombre']);
        echo " - Precio: $" . $producto['precio'];
        echo " - Cantidad: " . $producto['cantidad'];

        echo "<br>";
    }

} else {
    echo "Error al consultar productos";
}

$conexion->close();

?>
