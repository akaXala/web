document.addEventListener('DOMContentLoaded', () => {
    fetch('products.json')
        .then(response => response.json())
        .then(data => {
            displayProducts(data.products);
        })
        .catch(error => console.error('Error loading JSON:', error));
});

function displayProducts(products) {
    let filas = "";
    let col = 0; // Contador de columnas

    products.forEach((product, indice) => {
        // Iniciar una nueva fila cada 3 productos
        if (indice % 3 == 0) {
            if (indice !== 0) { // Si no es el primer producto, cierra la fila anterior
                filas += '</div></div>';
            }
            filas += '<div class="container text-center"><div class="row align-items-start">';
        }
            
        // Agregar el producto actual a la fila
        filas += `
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <img src="${product.thumbnail}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">${product.title}</h5>
                        <p class="card-text">${product.description}</p>
                        <a href="detallesProducto.html?id=${product.id}" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
        `;
            
        // Incrementar el contador de columnas y reiniciar si es necesario
        col = (col + 1) % 3;
    });

    // Cerrar la última fila si no se cerró previamente
    if (col !== 0) {
        filas += '</div></div>';
    }

    document.getElementById('products-container').innerHTML = filas;

    // Restaurar la posición de desplazamiento después de que los productos se hayan añadido al DOM
    const savedScrollPosition = localStorage.getItem('scrollPosition') || 0;
    window.scrollTo(0, savedScrollPosition);
}

// Guardar la posición de desplazamiento antes de recargar
window.addEventListener('beforeunload', function() {
    localStorage.setItem('scrollPosition', window.scrollY || document.documentElement.scrollTop);
});
