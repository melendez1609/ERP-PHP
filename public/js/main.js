document.addEventListener('DOMContentLoaded', () => {
    // 1. Instanciamos el audio apuntando a la ruta de tu carpeta public
    const hoverSound = new Audio('/sounds/button-hover.mp3');

    // 2. Seleccionamos todas las imágenes con la clase 'icon'
    const icons = document.querySelectorAll('.icon');

    // 3. Añadimos el evento 'mouseenter' a cada icono
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', () => {
            // Reiniciamos el audio al segundo 0 por si se pasa rápido de un icono a otro
            hoverSound.currentTime = 0; 
            
            // Reproducimos el sonido
            hoverSound.play().catch(error => {
                // El navegador podría bloquear el audio si el usuario no ha interactuado antes con la página
                console.log("La reproducción automática fue bloqueada o el archivo no se encontró:", error);
            });
        });
    });
});