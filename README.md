# Boutiquemg_Reservas
Tipo de Sitio:
Tienda de ropa virtual con sistema de reservas por WhatsApp (no incluye carrito de compras tradicional).

Funcionalidades Principales:

1. Frontend (Público)
Catálogo de Productos:

Visualización organizada por categorías.

Vista detallada de cada producto (imágenes, tallas, colores, precio).

Reservas por WhatsApp:

Botón inteligente que genera un mensaje predefinido con:

Nombre del producto

Talla/color seleccionados

Precio

Enlace directo al producto

Diseño Responsive:

Adaptable a móviles, tablets y desktop.

Interfaz intuitiva y enfocada en la conversión (reservas).

2. Panel de Administración (Privado)
Gestión de Productos:

CRUD completo (Crear, Leer, Actualizar, Eliminar).

Subida múltiple de imágenes.

Gestión de variantes (tallas/colores mediante campos JSON).

Gestión de Categorías:

Organización jerárquica de productos.

Configuración Global:

Cambio de logo.

Actualización del número de WhatsApp vinculado.

Personalización del mensaje predeterminado para reservas.

Seguridad:

Autenticación requerida.

Protección contra inyecciones SQL/XSS.

3. Tecnologías y Estructura
Backend: PHP puro (sin frameworks) + MySQL.

Frontend: HTML5, CSS3, JavaScript vanilla (sin jQuery).

Hosting: Configurado para Netlify (con soporte para PHP mediante redirecciones y ajustes personalizados).

Estructura de Archivos:

Copy
/boutiquemg_reservas
├── index.php            # Página principal
├── categoria.php        # Vista por categoría
├── producto.php         # Detalle de producto
├── contacto.php         # Página de contacto
├── /admin/              # Panel de control
├── /includes/           # Lógica PHP (BD, autenticación)
└── /assets/             # Estilos, scripts e imágenes
4. Experiencia del Usuario
Clientes:

Navegación sencilla con enfoque en reservas instantáneas vía WhatsApp.

No requiere registro ni pasos complejos.

Administradores:

Panel intuitivo para gestionar inventario y configuración.

Acceso restringido y seguro.
