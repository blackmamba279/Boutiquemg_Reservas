// admin.js - Funcionalidades JavaScript para el panel de administración

document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // Funcionalidades Generales del Panel Admin
    // =============================================
    
    // Toggle del menú lateral en móviles
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    }

    // =============================================
    // Funcionalidades para la Gestión de Productos
    // =============================================
    
    // Previsualización de imágenes antes de subir
    const imageUploads = document.querySelectorAll('.image-upload');
    imageUploads.forEach(upload => {
        upload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewId = this.getAttribute('data-preview');
            const preview = document.getElementById(previewId);
            
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // Gestión de imágenes múltiples
    const addImageBtn = document.getElementById('add-more-images');
    if (addImageBtn) {
        addImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const container = document.querySelector('.image-uploads-container');
            const newInput = document.createElement('div');
            newInput.className = 'form-group';
            newInput.innerHTML = `
                <input type="file" name="imagenes_adicionales[]" class="image-upload" accept="image/*">
                <button class="btn-remove-image" type="button">×</button>
            `;
            container.appendChild(newInput);
        });
    }

    // Eliminar imágenes
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-image')) {
            e.preventDefault();
            e.target.parentElement.remove();
        }
    });

    // =============================================
    // Funcionalidades para la Gestión de Categorías
    // =============================================
    
    // Confirmación antes de eliminar
    const deleteButtons = document.querySelectorAll('.btn-eliminar');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    // =============================================
    // Funcionalidades para la Configuración
    // =============================================
    
    // Previsualización del logo
    const logoUpload = document.getElementById('logo');
    if (logoUpload) {
        logoUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logo-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Validación del número de WhatsApp
    const whatsappInput = document.getElementById('whatsapp_number');
    if (whatsappInput) {
        whatsappInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // =============================================
    // Funcionalidades para Tallas y Colores
    // =============================================
    
    // Gestión dinámica de tallas
    const tallasContainer = document.querySelector('.tallas-container');
    if (tallasContainer) {
        const addTallaBtn = document.getElementById('add-talla');
        
        addTallaBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const newInput = document.createElement('div');
            newInput.className = 'talla-input';
            newInput.innerHTML = `
                <input type="text" name="tallas[]" placeholder="Ej: M" maxlength="10">
                <button type="button" class="btn-remove-talla">×</button>
            `;
            tallasContainer.appendChild(newInput);
        });

        tallasContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-talla')) {
                e.preventDefault();
                e.target.parentElement.remove();
            }
        });
    }

    // Gestión dinámica de colores
    const coloresContainer = document.querySelector('.colores-container');
    if (coloresContainer) {
        const addColorBtn = document.getElementById('add-color');
        
        addColorBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const newInput = document.createElement('div');
            newInput.className = 'color-input';
            newInput.innerHTML = `
                <input type="text" name="colores[]" placeholder="Ej: Rojo" maxlength="20">
                <button type="button" class="btn-remove-color">×</button>
            `;
            coloresContainer.appendChild(newInput);
        });

        coloresContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-color')) {
                e.preventDefault();
                e.target.parentElement.remove();
            }
        });
    }

    // =============================================
    // Funciones Auxiliares
    // =============================================
    
    // Mostrar/ocultar contraseña
    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Mensajes de alerta con timeout
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 600);
        }, 5000);
    });
});
