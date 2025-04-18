// Función para el botón de WhatsApp
document.addEventListener('DOMContentLoaded', function() {
    // Configuración del botón de reserva
    const whatsappButtons = document.querySelectorAll('.whatsapp-reserva');
    
    whatsappButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const productName = this.getAttribute('data-product');
            const whatsappNumber = this.getAttribute('data-whatsapp');
            const message = `Hola, estoy interesado en reservar ${productName}. Por favor contáctenme.`;
            window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`, '_blank');
        });
    });
    
    // Menú móvil
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('header nav ul');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('show');
        });
    }
});
