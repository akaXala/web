document.addEventListener("DOMContentLoaded", () => {
  console.log("Pagina cargada");

  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");

  fetch("products.json")
    .then((response) => response.json())
    .then((data) => {
      const product = data.products.find((p) => p.id == productId);
      if (product) {
        console.log(`Product found: ${product.title}`);
        fetch(`../php/get_product_stock.php?id=${productId}`)
          .then((response) => {
            console.log("Response from PHP:", response);
            return response.json();
          })
          .then((stockData) => {
            console.log("Stock data:", stockData);
            if (stockData.error) {
              console.error(stockData.error);
            } else {
              displayProductDetails(product, stockData.stock);
            }
          })
          .catch((error) => console.error("Error fetching stock:", error));
      } else {
        document.getElementById("product-details").innerHTML =
          "<p>Producto no encontrado.</p>";
      }
    })
    .catch((error) => console.error("Error loading JSON:", error));

  // Actualiza el contador de artículos del carrito al cargar la página
  updateCartItemCount();
});

function displayProductDetails(product, stock) {
  let imagesHTML = "";
  let stockPercentage = parseInt((stock / product.stock) * 100);
  let newPrice =
    parseFloat(parseInt(product.price * (100 - product.discountPercentage))) /
    100;

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
                  <div class="w-100 text-start py-1">
                      <h1 style="font-size:2rem">${product.title}</h1>
                      <hr class="custom-hr border border-3 opacity-50 border-primary">
                  </div>
                  <div class="w-100 text-start py-0">
                    <p class="py-0 my-0"><span class="pe-2" style="font-size:25px;color:#0b5ed7; font-weight:bold">-${product.discountPercentage}%</span><span style="font-size:26px">$${newPrice}</span></p>
                    <p class="py-0 my-0" style="font-size:13px">Precio en lista: <span class="fw-light text-decoration-line-through">$${product.price}</span></p>
                    <hr class="custom-hr border border-3 opacity-50 border-primary">
                  </div>
                  <div class="w-100 text-start">
                    <p style="font-size:1.25rem; font-weight:bold;">Description:</p>
                    <p>${product.description}</p>
                    <hr class="custom-hr border border-3 opacity-50 border-primary">
                    <p style="font-size:1.25rem; font-weight:bold;">Characteristics:</p>
                    <ul>
                      <li>Brand: ${product.brand}</li>
                      <li>Weight: ${product.weight} oz</li>
                      <li>Width: ${product.dimensions.width} in</li>
                      <li>Height: ${product.dimensions.height} in</li>
                      <li>Depth: ${product.dimensions.depth}</li>
                      <li>Warranty Information: ${product.warrantyInformation}</li>
                    </ul>
                    <hr class="custom-hr border border-3 opacity-50 border-primary">
                  </div>
                  <div class="w-100 text-start">
                    <div class="d-flex justify-content-between align-items-center">
                      <p class="m-0 p-0" style="font-size:26px">$${newPrice}</p>
                      <div id="add-to-cart-container" class="mt-0"></div> <!-- Container for Add to Cart button -->
                    </div>
                    <p class="m-0 p-0">${product.shippingInformation}</p>
                    <p class="m-0 p-0">Stock</p>
                    <div class="progress" role="progressbar" aria-label="Info example" aria-valuenow="${stock}" aria-valuemin="0" aria-valuemax="${product.stock}">
                      <div class="progress-bar bg-primary text-white" style="width: ${stockPercentage}%">${stock}</div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  `;
  document.getElementById("product-details").innerHTML = productDetails;

  // Create and append the Add to Cart button
  createAddToCartButton();
}

function createAddToCartButton() {
  // Create a button element
  var button = document.createElement("button");
  button.innerHTML = "Add to Cart";
  button.classList.add("btn", "btn-primary", "mt-3");

  // Add an event listener to call addToCart function when the button is clicked
  button.addEventListener("click", addToCart);

  // Append the button to the add-to-cart-container
  document.getElementById("add-to-cart-container").appendChild(button);
}

function addToCart() {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");
  console.log("Adding product with ID " + productId + " to cart.");

  // Create a FormData object and append the productId
  let formData = new FormData();
  formData.append("productId", productId);

  fetch("../php/add_to_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Parse the response as JSON
    .then((data) => {
      if (data.status === "error" && data.message === "Not logged in") {
        window.location.href = "../html/login.html"; // Redirigir a la página de inicio de sesión
      } else {
        console.log(data);
        updateCartItemCount(); // Actualiza el número de artículos en el carrito
      }
    })
    .catch((error) => console.error("Error adding to cart:", error));
}

function updateCartItemCount() {
  fetch("../php/get_cart_count.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        document.getElementById("cart-item-count").innerText = data.count;
      } else {
        console.error("Error fetching cart count:", data.message);
      }
    })
    .catch((error) => console.error("Error fetching cart count:", error));
}
