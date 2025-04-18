<?php
require_once 'includes/db.php';
require_once 'includes/funciones.php';

// Obtener configuración de contacto
$config_stmt = $pdo->query("SELECT titulo_tienda, descripcion_tienda, whatsapp_number FROM configuracion LIMIT 1");
$config = $config_stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = "Contacto - BoutiqueMG Reservas";
include 'includes/header.php';
?>

<section class="contacto">
    <h1>Contacto</h1>
    
    <div class="contacto-info">
        <h2><?php echo htmlspecialchars($config['titulo_tienda'] ?? 'BoutiqueMG Reservas'); ?></h2>
        
        <?php if (!empty($config['descripcion_tienda'])): ?>
            <div class="descripcion-tienda">
                <?php echo nl2br(htmlspecialchars($config['descripcion_tienda'])); ?>
            </div>
        <?php endif; ?>
        
        <div class="metodos-contacto">
            <?php if (!empty($config['whatsapp_number'])): ?>
                <div class="contacto-whatsapp">
                    <h3><i class="fab fa-whatsapp"></i> WhatsApp</h3>
                    <p>Contáctanos directamente por WhatsApp para reservas o consultas:</p>
                    <a href="https://wa.me/<?php echo htmlspecialchars($config['whatsapp_number']); ?>" 
                       class="whatsapp-btn" target="_blank">
                        <i class="fab fa-whatsapp"></i> Enviar mensaje
                    </a>
                </div>
            <?php endif; ?>
            
            <div class="contacto-form">
                <h3><i class="fas fa-envelope"></i> Formulario de Contacto</h3>
                <form method="POST" action="/enviar-contacto.php">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="mapa">
        <h3><i class="fas fa-map-marker-alt"></i> Ubicación</h3>
        <!-- Puedes reemplazar esto con un iframe de Google Maps -->
        <div class="mapa-placeholder">
            <p>Aquí iría el mapa de ubicación de la tienda física (si aplica)</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
