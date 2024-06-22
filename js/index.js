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
    let col = 0;

    products.forEach((product, indice) => {
        if (indice % 3 == 0){
            filas += `<div class="container text-center">
                        <div class="row align-items-start">`;
        }
            
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
            
        if (col == 2){
            filas += `
                </div>
            </div>
            `;
            col = 0;
        } else {
            col++;
        }
    });

    // If there's an open row, close it
    if (col !== 0) {
        filas += `
            </div>
        </div>
        `;
    }

    document.getElementById('products-container').innerHTML = filas;
}
