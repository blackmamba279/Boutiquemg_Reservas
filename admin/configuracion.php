<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/funciones.php';

redirectIfNotLoggedIn();

$pageTitle = "Configuración General";
include '../includes/header.php';

// Obtener configuración actual
$config = obtenerConfiguracion($pdo);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $whatsapp_number = trim($_POST['whatsapp_number']);
    $whatsapp_message = trim($_POST['whatsapp_message']);
    $titulo_tienda = trim($_POST['titulo_tienda']);
    $descripcion_tienda = trim($_POST['descripcion_tienda']);
    
    // Procesar logo si se subió
    $logo_nombre = $config['logo'];
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['logo'];
        $extension = pathinfo($logo['name'], PATHINFO_EXTENSION);
        $logo_nombre = 'logo-' . time() . '.' . $extension;
        $destino = '../assets/images/' . $logo_nombre;
        
        // Validar tipo de archivo
        $permitidos = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($extension), $permitidos)) {
            if (move_uploaded_file($logo['tmp_name'], $destino)) {
                // Eliminar logo anterior si existe
                if ($config['logo'] && file_exists('../assets/images/' . $config['logo'])) {
                    unlink('../assets/images/' . $config['logo']);
                }
            } else {
                $error = "Error al subir el archivo del logo";
            }
        } else {
            $error = "Formato de archivo no permitido. Use JPG, PNG o GIF";
        }
    }
    
    if (!isset($error)) {
        try {
            // Actualizar configuración
            $stmt = $pdo->prepare("UPDATE configuracion SET 
                                  logo = ?,
                                  whatsapp_number = ?,
                                  whatsapp_message = ?,
                                  titulo_tienda = ?,
                                  descripcion_tienda = ?
                                  WHERE id = 1");
            $stmt->execute([
                $logo_nombre,
                $whatsapp_number,
                $whatsapp_message,
                $titulo_tienda,
                $descripcion_tienda
            ]);
            
            $success = "Configuración actualizada correctamente";
            // Actualizar variable $config para mostrar los cambios
            $config = obtenerConfiguracion($pdo);
        } catch (PDOException $e) {
            $error = "Error al guardar la configuración: " . $e->getMessage();
        }
    }
}

function obtenerConfiguracion($pdo) {
    $stmt = $pdo->query("SELECT * FROM configuracion LIMIT 1");
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si no existe configuración, crear registro inicial
    if (!$config) {
        $pdo->query("INSERT INTO configuracion (logo, whatsapp_number) VALUES ('', '')");
        $stmt = $pdo->query("SELECT * FROM configuracion LIMIT 1");
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return $config;
}
?>

<div class="dashboard">
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="categorias.php">Categorías</a></li>
                <li class="active"><a href="configuracion.php">Configuración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="content">
        <h1>Configuración General</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="form-configuracion">
            <div class="form-group">
                <label for="titulo_tienda">BoutiqueMG Reservas</label>
                <input type="text" id="titulo_tienda" name="titulo_tienda" 
                       value="<?php echo htmlspecialchars($config['titulo_tienda'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="descripcion_tienda">Descripción de la Tienda</label>
                <textarea id="descripcion_tienda" name="descripcion_tienda" rows="3"><?php 
                    echo htmlspecialchars($config['descripcion_tienda'] ?? ''); 
                ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Logo Actual</label>
                <?php if (!empty($config['logo'])): ?>
                    <div class="logo-preview">
                        <img src="../assets/images/<?php echo htmlspecialchars($config['logo']); ?>" 
                             alt="Logo actual" style="max-width: 200px; display: block; margin: 10px 0;">
                    </div>
                <?php else: ?>
                    <p>No hay logo subido</p>
                <?php endif; ?>
                
                <label for="logo">Cambiar Logo</label>
                <input type="file" id="logo" name="logo" accept="image/*">
                <small>Formatos aceptados: JPG, PNG, GIF. Tamaño recomendado: 200x100px</small>
            </div>
            
            <div class="form-group">
                <label for="whatsapp_number">50587010851*</label>
                <input type="text" id="whatsapp_number" name="whatsapp_number" required
                       placeholder="Ej: 5215512345678 (sin +, espacios o guiones)"
                       value="<?php echo htmlspecialchars($config['whatsapp_number'] ?? ''); ?>">
                <small>Incluir código de país. Ejemplo para México: 5215512345678</small>
            </div>
            
            <div class="form-group">
                <label for="whatsapp_message">Mensaje Predeterminado para WhatsApp</label>
                <textarea id="whatsapp_message" name="whatsapp_message" rows="3" 
                          placeholder="Mensaje que aparecerá automáticamente cuando los clientes hagan clic en el botón de WhatsApp"><?php 
                    echo htmlspecialchars($config['whatsapp_message'] ?? 'Hola, estoy interesado en un producto de su tienda. Por favor envíeme más información.'); 
                ?></textarea>
                <small>Puedes usar variables como %producto%, %precio%, %url% que se reemplazarán automáticamente</small>
            </div>
            
            <button type="submit" class="btn">Guardar Configuración</button>
        </form>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
