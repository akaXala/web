document.addEventListener("DOMContentLoaded", () => {
  console.log("Pagina cargada"); // Debug: Log when the page is loaded
  console.log("Cargando productos..."); // Debug: Log when products are being loaded

  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");

  fetch("products.json")
    .then((response) => response.json())
    .then((data) => {
      const product = data.products.find((p) => p.id == productId);
      if (product) {
        displayProductDetails(product);
        createAddToCartButton(); // Create and append the button after displaying product details
      } else {
        document.getElementById("product-details").innerHTML =
          "<p>Producto no encontrado.</p>";
      }
    })
    .catch((error) => console.error("Error loading JSON:", error));
});

function displayProductDetails(product) {
  let imagesHTML = "";

  if (product.images.length > 1) {
    imagesHTML += `
        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
        `;

    for (let i = 0; i < product.images.length; i++) {
      imagesHTML += `
        <div class="carousel-item ${i === 0 ? "active" : ""}">
            <img src="${product.images[
              i
            ].trim()}" class="img-fixed-size d-flex img-thumbnail">
        </div>`;
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
    imagesHTML += `<img src="${product.images[0]}" class="img-fixed-size img-fluid border img-thumbnail">`;
  }

  const productDetails = `
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 col-12">
                    ${imagesHTML}
                </div>
                <div class="col-md-6 col-12 d-flex flex-column justify-content-start align-items-center border border-2">
                    <div class="w-100 text-start py=1">
                        <h1 style="font-size:5rem">${product.title}</h1>
                        <hr class="border  border-3 opacity-50">
                    </div>
                    <div class="w-100 text-start">
                      <p style="font-size:1.5rem; font-weight:bold;">Description:</p>
                      <p>${product.description}</p>
                      <p style="font-size:1.5rem; font-weight:bold;">Characteristics:</p>
                      <hr class="border  border-3 opacity-50">
                      <ul>
                        <li>Brand: ${product.brand}</li>
                        <li>Weight: ${product.weight} oz</li>
                        <li>Width: ${product.dimensions.width} in</li>
                        <li>Height: ${product.dimensions.height} in</li>
                        <li>Depth: ${product.dimensions.depth}</li>
                        <li>Warrinty Information: ${product.warrantyInformation} in</li>
                      </ul>
                      <hr class="border  border-3 opacity-50">
                    </div>
                    <div class="w-100 text-start">
                        <p>${product.di}</p>
                    </div>
                    <div class="w-100 text-start">
                        <p>${product.price}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
  document.getElementById("product-details").innerHTML = productDetails;
}

function createAddToCartButton() {
  // Create a button element
  var button = document.createElement("button");
  button.innerHTML = "Add to Cart";
  button.classList.add("btn", "btn-primary", "mt-3");

  // Add an event listener to call addToCart function when the button is clicked
  button.addEventListener("click", addToCart);

  // Append the button to the product details container
  document.getElementById("AddCar").appendChild(button);
}

// Function to be called when the button is clicked
function addToCart() {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");
  console.log("Adding product with ID " + productId + " to cart.");

  // Create a FormData object and append the productId
  let formData = new FormData();
  formData.append("productId", productId);

  fetch("../php/add_to_cart.php", {
    method: "POST",
    body: formData, // Send as form data
  })
    .then((response) => response.json()) // Parse the response as JSON
    .then((data) => {
      if (data.status === "error" && data.message === "Not logged in") {
        window.location.href = "../html/login.html"; // Redirigir a la página de inicio de sesión
      } else {
        console.log(data);
        // Handle the response data here
      }
    })
    .catch((error) => console.error("Error adding to cart:", error));
}
