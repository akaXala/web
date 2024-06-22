document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    fetch('products.json')
        .then(response => response.json())
        .then(data => {
            const product = data.products.find(p => p.id == productId);
            if (product) {
                displayProductDetails(product);
            } else {
                document.getElementById('product-details').innerHTML = '<p>Producto no encontrado.</p>';
            }
        })
        .catch(error => console.error('Error loading JSON:', error));
});

function displayProductDetails(product) {
    const productDetails = `
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <img src="${product.images}" class="img-fluid border">
                </div>
                <div class="col">
                    <div class="row">
                        <h5>${product.title}</h5>
                    </div>
                    <div class="row">
                        <p>${product.description}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.getElementById('product-details').innerHTML = productDetails;
}
