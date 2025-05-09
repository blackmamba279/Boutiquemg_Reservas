<?php
require_once '../includes/db.php';
require_once '../includes/funciones.php';

if (!isset($_GET['id'])) {
    header("Location: /");
    exit();
}

$producto = obtenerProducto($pdo, $_GET['id']);

if (!$producto) {
    header("Location: /");
    exit();
}

$pageTitle = $producto['nombre'];
include '../includes/header.php';
?>

<section class="detalle-producto">
    <div class="producto-imagenes">
        <img src="/assets/images/productos/<?php echo htmlspecialchars($producto['imagen_principal']); ?>" 
             alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
    </div>
    
    <div class="producto-info">
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <p class="categoria">Categoría: <?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
        <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
        <div class="descripcion">
            <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
        </div>
        
        <button class="whatsapp-reserva" 
                data-product="<?php echo htmlspecialchars($producto['nombre']); ?>"
                data-whatsapp="<?php echo htmlspecialchars($config['whatsapp_number'] ?? '5215512345678'); ?>">
            Reservar por WhatsApp
        </button>
    </div>
</section>

<?php include '../includes/footer.php'; ?>