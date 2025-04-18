<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
redirectIfNotLoggedIn();

$pageTitle = "Panel de Administración";
include '../includes/header.php';
?>

<div class="dashboard">
    <aside class="sidebar">
        <nav>
            <ul>
                <li class="active"><a href="index.php">Dashboard</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="categorias.php">Categorías</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="content">
        <h1>Bienvenido al Panel de Administración</h1>
        <div class="stats">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <p><?php echo contarProductos($pdo); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Categorías</h3>
                <p><?php echo contarCategorias($pdo); ?></p>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
