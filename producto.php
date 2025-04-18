<?php
require_once 'includes/db.php';
require_once 'includes/funciones.php';

if (!isset($_GET['id'])) {
    header("Location: /");
    exit();
}

$producto = obtenerProducto($pdo, $_GET['id']);

if (!$producto) {
    header("Location: /");
    exit();
}

$pageTitle = $producto['nombre'] . " - Tienda de Ropa";
include 'includes/header.php';

// Obtener configuracion para WhatsApp
$config_stmt = $pdo->query("SELECT whatsapp_number, whatsapp_message FROM configuracion LIMIT 1");
$config = $config_stmt->fetch(PDO::FETCH_ASSOC);
?>

<section class="detalle-producto">
    <div class="producto-galeria">
        <div class="imagen-principal">
            <img src="/assets/images/productos/<?php echo $producto['imagen_principal']; ?>" 
                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" id="imagen-main">
        </div>
        
        <?php if (!empty($producto['imagenes'])): ?>
            <?php $imagenes_adicionales = json_decode($producto['imagenes'], true); ?>
            <div class="miniaturas">
                <?php foreach ($imagenes_adicionales as $img): ?>
                    <img src="/assets/images/productos/<?php echo $img; ?>" 
                         alt="Vista adicional <?php echo htmlspecialchars($producto['nombre']); ?>"
                         onclick="document.getElementById('imagen-main').src = this.src">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="producto-info">
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <p class="categoria">Categoría: <a href="/categoria.php?id=<?php echo $producto['categoria_id']; ?>">
            <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
        </a></p>
        <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
        
        <?php if (!empty($producto['tallas'])): ?>
            <?php $tallas_disponibles = json_decode($producto['tallas'], true); ?>
            <div class="opciones-talla">
                <label>Talla:</label>
                <div class="tallas">
                    <?php foreach ($tallas_disponibles as $talla): ?>
                        <button type="button" class="talla-btn" 
                                onclick="seleccionarTalla(this, '<?php echo $talla; ?>')">
                            <?php echo $talla; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="talla-seleccionada" value="">
            </div>
        <?php endif; ?>
        
        <?php if (!empty($producto['colores'])): ?>
            <?php $colores_disponibles = json_decode($producto['colores'], true); ?>
            <div class="opciones-color">
                <label>Color:</label>
                <div class="colores">
                    <?php foreach ($colores_disponibles as $color): ?>
                        <button type="button" class="color-btn" 
                                onclick="seleccionarColor(this, '<?php echo $color; ?>')"
                                title="<?php echo $color; ?>">
                            <?php echo substr($color, 0, 1); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="color-seleccionado" value="">
            </div>
        <?php endif; ?>
        
        <div class="descripcion">
            <h3>Descripción</h3>
            <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
        </div>
        
        <button class="whatsapp-reserva-btn" onclick="reservarPorWhatsApp()">
            <i class="fab fa-whatsapp"></i> Reservar por WhatsApp
        </button>
    </div>
</section>

<script>
// Variables globales para la reserva
let tallaSeleccionada = '';
let colorSeleccionado = '';

function seleccionarTalla(elemento, talla) {
    document.querySelectorAll('.talla-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    elemento.classList.add('active');
    tallaSeleccionada = talla;
    document.getElementById('talla-seleccionada').value = talla;
}

function seleccionarColor(elemento, color) {
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    elemento.classList.add('active');
    colorSeleccionado = color;
    document.getElementById('color-seleccionado').value = color;
}

function reservarPorWhatsApp() {
    const producto = "<?php echo $producto['nombre']; ?>";
    const precio = "<?php echo $producto['precio']; ?>";
    const url = window.location.href;
    const whatsappNumber = "<?php echo $config['whatsapp_number'] ?? ''; ?>";
    const defaultMessage = "<?php echo str_replace(["\n", "\r"], '', addslashes($config['whatsapp_message'] ?? 'Hola, quiero reservar este producto')); ?>";
    
    if (!whatsappNumber) {
        alert('Error: No se ha configurado el número de WhatsApp');
        return;
    }
    
    // Validar selecciones si existen
    const tieneTallas = <?php echo !empty($producto['tallas']) ? 'true' : 'false'; ?>;
    const tieneColores = <?php echo !empty($producto['colores']) ? 'true' : 'false'; ?>;
    
    if (tieneTallas && !tallaSeleccionada) {
        alert('Por favor selecciona una talla');
        return;
    }
    
    if (tieneColores && !colorSeleccionado) {
        alert('Por favor selecciona un color');
        return;
    }
    
    // Construir mensaje
    let message = defaultMessage
        .replace('%producto%', producto)
        .replace('%precio%', '$' + precio)
        .replace('%url%', url);
    
    if (tallaSeleccionada) {
        message += `\nTalla: ${tallaSeleccionada}`;
    }
    
    if (colorSeleccionado) {
        message += `\nColor: ${colorSeleccionado}`;
    }
    
    // Abrir WhatsApp
    window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`, '_blank');
}
</script>

<?php include 'includes/footer.php'; ?>
