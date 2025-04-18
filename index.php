<?php
require_once 'includes/db.php';
require_once 'includes/funciones.php';

$pageTitle = "Catálogo de Productos";
include 'includes/header.php';

$productos = obtenerProductosDestacados($pdo);
?>

<section class="productos-destacados">
    <h2>Nuestros Productos</h2>
    <div class="grid-productos">
        <?php foreach ($productos as $producto): ?>
        <div class="producto">
            <img src="/assets/images/productos/<?php echo $producto['imagen_principal']; ?>" alt="<?php echo $producto['nombre']; ?>">
            <h3><?php echo $producto['nombre']; ?></h3>
            <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
            <a href="/producto.php?id=<?php echo $producto['id']; ?>" class="btn">Ver Detalles</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
