<?php
function obtenerProductosDestacados($pdo, $limite = 8) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE estado = 1 ORDER BY id DESC LIMIT :limite");
    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function contarProductos($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM productos");
    return $stmt->fetchColumn();
}

function contarCategorias($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM categorias");
    return $stmt->fetchColumn();
}

function obtenerCategoria($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerProductosPorCategoria($pdo, $categoria_id) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria_id = :categoria_id AND estado = 1");
    $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProducto($pdo, $id) {
    $stmt = $pdo->prepare("SELECT p.*, c.nombre as categoria_nombre 
                          FROM productos p 
                          JOIN categorias c ON p.categoria_id = c.id 
                          WHERE p.id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
