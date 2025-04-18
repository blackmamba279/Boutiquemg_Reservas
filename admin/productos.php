<?php
require_once 'includes/db.php';
require_once 'includes/funciones.php';

if (!isset($_GET['id']) {
    header("Location: /");
    exit();
}

$producto = obtenerProducto($pdo, $_GET['id']);

if (!$producto) {
    header("Location: /");
    exit();
}

$pageTitle = $producto['nombre'];
include 'includes/header.php';
?>

<section class="detalle-producto">
    <div class="producto-imagenes">
        <img src="/assets/images/productos/<?php echo $producto['imagen_principal']; ?>" alt="<?php echo $producto['nombre']; ?>">
    </div>
    
    <div class="producto-info">
        <h1><?php echo $producto['nombre']; ?></h1>
        <p class="categoria">Categoría: <?php echo $producto['categoria_nombre']; ?></p>
        <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
        <div class="descripcion">
            <?php echo nl2br($producto['descripcion']); ?>
        </div>
        
        <button class="whatsapp-reserva" 
                data-product="<?php echo $producto['nombre']; ?>"
                data-whatsapp="5215512345678">
            Reservar por WhatsApp
        </button>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
