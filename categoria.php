<?php
require_once 'includes/db.php';
require_once 'includes/funciones.php';

if (!isset($_GET['id'])) {
    header("Location: /");
    exit();
}

$categoria_id = (int)$_GET['id'];
$categoria = obtenerCategoria($pdo, $categoria_id);

if (!$categoria) {
    header("Location: /");
    exit();
}

$productos = obtenerProductosPorCategoria($pdo, $categoria_id);

$pageTitle = $categoria['nombre'] . " - Tienda de Ropa";
include 'includes/header.php';
?>

<section class="categoria-detalle">
    <h1><?php echo htmlspecialchars($categoria['nombre']); ?></h1>
    
    <?php if (!empty($categoria['descripcion'])): ?>
        <div class="descripcion-categoria">
            <?php echo nl2br(htmlspecialchars($categoria['descripcion'])); ?>
        </div>
    <?php endif; ?>
    
    <?php if (count($productos) > 0): ?>
        <div class="grid-productos">
            <?php foreach ($productos as $producto): ?>
            <div class="producto">
                <a href="/producto.php?id=<?php echo $producto['id']; ?>">
                    <img src="/assets/images/productos/<?php echo $producto['imagen_principal']; ?>" 
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </a>
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
                <a href="/producto.php?id=<?php echo $producto['id']; ?>" class="btn">Ver Detalles</a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="sin-productos">
            <p>Actualmente no hay productos en esta categoría.</p>
            <a href="/" class="btn">Volver al catálogo</a>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
