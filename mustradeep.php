<?php
// 1. Crear array inicial
$listaCompras = ["Pan", "Leche", "Huevos", "Arroz", "Pollo"];

// 2. Mostrar productos numerados
echo "<h3>Lista de Compras:</h3>";
echo "<ol>";
foreach ($listaCompras as $producto) {
    echo "<li>$producto</li>";
}
echo "</ol>";

// 3. Agregar productos
$listaCompras[] = "Queso";
$listaCompras[] = "Tomate";

// 4. Mostrar total
$total = count($listaCompras);
echo "<p>Total de productos: $total</p>";

// 5. Verificar si "leche" está en la lista
if (in_array("Leche", $listaCompras)) {
    echo "<p>Leche está en la lista de productos</p>";
} else {
    echo "<p>Leche no está en la lista de productos</p>";
}
?>