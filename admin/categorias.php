<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/funciones.php';

redirectIfNotLoggedIn();

$pageTitle = "Gestión de Categorías";
include '../includes/header.php';

// Procesar formulario de creación/edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    // Validaciones
    if (empty($nombre)) {
        $error = "El nombre de la categoría es obligatorio";
    } else {
        try {
            if ($id > 0) {
                // Editar categoría existente
                $stmt = $pdo->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?");
                $stmt->execute([$nombre, $descripcion, $id]);
                $success = "Categoría actualizada correctamente";
            } else {
                // Crear nueva categoría
                $stmt = $pdo->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
                $stmt->execute([$nombre, $descripcion]);
                $success = "Categoría creada correctamente";
            }
        } catch (PDOException $e) {
            $error = "Error al guardar la categoría: " . $e->getMessage();
        }
    }
}

// Procesar eliminación
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Verificar si la categoría tiene productos asociados
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE categoria_id = ?");
        $stmt->execute([$id]);
        $productos_count = $stmt->fetchColumn();
        
        if ($productos_count > 0) {
            $error = "No se puede eliminar la categoría porque tiene productos asociados";
        } else {
            $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Categoría eliminada correctamente";
        }
    } catch (PDOException $e) {
        $error = "Error al eliminar la categoría: " . $e->getMessage();
    }
}

// Obtener categoría para editar
$categoria_editar = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $categoria_editar = obtenerCategoria($pdo, $id);
    if (!$categoria_editar) {
        $error = "Categoría no encontrada";
    }
}

// Obtener todas las categorías
$stmt = $pdo->query("SELECT * FROM categorias ORDER BY nombre");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard">
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li class="active"><a href="categorias.php">Categorías</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="content">
        <h1><?php echo isset($categoria_editar) ? 'Editar Categoría' : 'Agregar Nueva Categoría'; ?></h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-categoria">
            <?php if (isset($categoria_editar)): ?>
                <input type="hidden" name="id" value="<?php echo $categoria_editar['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nombre">Nombre de la categoría*</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo isset($categoria_editar) ? htmlspecialchars($categoria_editar['nombre']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"><?php 
                    echo isset($categoria_editar) ? htmlspecialchars($categoria_editar['descripcion']) : ''; 
                ?></textarea>
            </div>
            
            <button type="submit" class="btn">
                <?php echo isset($categoria_editar) ? 'Actualizar Categoría' : 'Crear Categoría'; ?>
            </button>
            
            <?php if (isset($categoria_editar)): ?>
                <a href="categorias.php" class="btn cancelar">Cancelar</a>
            <?php endif; ?>
        </form>
        
        <h2>Listado de Categorías</h2>
        <table class="tabla-categorias">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?php echo $categoria['id']; ?></td>
                    <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                    <td class="acciones">
                        <a href="categorias.php?edit=<?php echo $categoria['id']; ?>" class="btn-editar">Editar</a>
                        <a href="categorias.php?delete=<?php echo $categoria['id']; ?>" 
                           class="btn-eliminar" 
                           onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
