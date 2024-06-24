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

    // Create a button element
    var button = document.createElement('button');
    button.innerHTML = 'Add to Cart';

    // Add an event listener to call addToCart function when the button is clicked
    button.addEventListener('click', addToCart);

    // Append the button to the body or any other element where you want the button to appear
    document.body.appendChild(button);
});

function displayProductDetails(product) {
    let imagesHTML = '';

    if (product.images.length > 1) {
        imagesHTML += `
        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
        `;

        for (let i = 0; i < product.images.length; i++) {
            if (i == 0) {
                imagesHTML += `
                <div class="carousel-item active">
                    <img src="${product.images[i].trim()}" class="d-block w-100">
                </div>`;
            } else {
                imagesHTML += `
                <div class="carousel-item">
                    <img src="${product.images[i].trim()}" class="d-block w-100">
                </div>`;
            }
        }

        imagesHTML += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        `;
    } else {
        imagesHTML += `<img src="${product.images[0]}" class="img-fluid border">`;
    }

    const productDetails = `
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    ${imagesHTML}
                </div>
                <div class="col">
                    <div class="row">
                        <h5>${product.title}</h5>
                    </div>
                    <div class="row">
                        <p>${product.description}</p>
                    </div>
                    <div class="row">
                        <p>${product.price}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.getElementById('product-details').innerHTML = productDetails;
}

// Function to be called when the button is clicked
function addToCart() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    console.log("Adding product with ID " + productId + " to cart.");

    // Create a FormData object and append the productId
    let formData = new FormData();
    formData.append('productId', productId);

    fetch('../php/add_to_cart.php', {
        method: 'POST',
        body: formData // Send as form data
    })
    .then(response => response.json()) // Parse the response as JSON
    .then(data => {
        if (data.status === 'error' && data.message === 'Not logged in') {
            window.location.href = '../html/login.html'; // Redirigir a la página de inicio de sesión
        } else {
            console.log(data);
            // Handle the response data here
        }
    })
    .catch(error => console.error('Error adding to cart:', error));
}
