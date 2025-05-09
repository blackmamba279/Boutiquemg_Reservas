<?php
function obtenerProductosDestacados($pdo, $limite = 8) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE estado = 1 ORDER BY id DESC LIMIT :limite");
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerProductosDestacados: " . $e->getMessage());
        return [];
    }
}

function contarProductos($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM productos");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error en contarProductos: " . $e->getMessage());
        return 0;
    }
}

function contarCategorias($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM categorias");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error en contarCategorias: " . $e->getMessage());
        return 0;
    }
}

function obtenerCategoria($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerCategoria: " . $e->getMessage());
        return null;
    }
}

function obtenerProductosPorCategoria($pdo, $categoria_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria_id = :categoria_id AND estado = 1");
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerProductosPorCategoria: " . $e->getMessage());
        return [];
    }
}

function obtenerProducto($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT p.*, c.nombre as categoria_nombre 
                              FROM productos p 
                              JOIN categorias c ON p.categoria_id = c.id 
                              WHERE p.id = :id AND p.estado = 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerProducto: " . $e->getMessage());
        return null;
    }
}

function obtenerConfiguracion($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM configuracion LIMIT 1");
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$config) {
            $stmt = $pdo->exec("INSERT INTO configuracion (whatsapp_number, whatsapp_message) VALUES ('', '')");
            $stmt = $pdo->query("SELECT * FROM configuracion LIMIT 1");
            $config = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $config;
    } catch (PDOException $e) {
        error_log("Error en obtenerConfiguracion: " . $e->getMessage());
        return [
            'whatsapp_number' => '',
            'whatsapp_message' => '',
            'titulo_tienda' => 'BoutiqueMG Reservas',
            'descripcion_tienda' => ''
        ];
    }
}