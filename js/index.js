document.addEventListener("DOMContentLoaded", () => {
  console.log("Página cargada"); // Debug: Log when the page is loaded
  console.log("Cargando productos..."); // Debug: Log when products are being loaded
  let products = [];

  fetch("products.json")
    .then((response) => response.json())
    .then((data) => {
      products = data.products; // Store fetched products
      displayTopRatedProducts(products); // Display top 10 rated products initially
    })
    .catch((error) => console.error("Error loading JSON:", error));

  // Event listener for the search input
  document
    .getElementById("productSearch")
    .addEventListener("input", (event) => {
      const searchTerm = event.target.value.toLowerCase();
      console.log(`Buscando: ${searchTerm}`); // Debug: Log the search term
      displayProducts(products, searchTerm); // Filter and display products
    });

  window.addEventListener("resize", () => {
    displayTopRatedProducts(products);
  });
});

function displayTopRatedProducts(products) {
  // Sort products by rating in descending order
  const topRatedProducts = products
    .sort((a, b) => b.rating - a.rating)
    .slice(0, 10);

  displayProducts(topRatedProducts);
}

function displayProducts(products, filter = "") {
  let filas = "";
  // Get the current page path
  const path = window.location.pathname;
  // Extract the file extension
  const fileExtension = path.split(".").pop();
  console.log(`File extension: ${fileExtension}`);
  // Declare the address variable
  let address;
  // Assign a value to address based on the file extension
  if (fileExtension === "html") {
    address = "./detallesProducto.html";
  } else {
    address = "./dpIniciado.php";
  }

  // Filter products if a filter is provided
  const filteredProducts = filter
    ? products.filter((product) => product.title.toLowerCase().includes(filter))
    : products;

  filas += `<div id="productCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
              <div class="carousel-inner ">`;

  const itemsPerSlide = getItemsPerSlide();
  filteredProducts.forEach((product, indice) => {
    let newPrice = parseFloat(
      parseInt(product.price * (100 - product.discountPercentage)) / 100
    );

    if (indice % itemsPerSlide === 0) {
      filas += `<div class="carousel-item ${indice === 0 ? "active" : ""} ">
                  <div class="d-flex justify-content-center ">
                    <div class="row justify-content-center align-items-center">`;
    }

    filas += `
      <div class="col mb-3 d-flex justify-content-center" style="flex: 0 0 ${
        100 / itemsPerSlide
      }%;">
        <div class="card" style="width: 13rem; min-height: 450px;">
          <img src="${
            product.thumbnail
          }" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${
      product.title
    }">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title d-flex justify-content-start" style="min-height: 3em;">${
              product.title
            }</h5>
            <p class="card-text d-flex justify-content-start text-decoration-line-through fw-light">$ ${
              product.price
            }</p>
            <div class="">
              <p class="d-flex justify-content-between card-text">$ ${newPrice} <p class="card-text">% ${
      product.discountPercentage
    }</p></p>
              
            </div>
            <a href="${address}?id=${
      product.id
    }" class="btn btn-primary mt-auto">Ver</a>
          </div>
        </div>
      </div>`;

    if (
      (indice + 1) % itemsPerSlide === 0 ||
      indice === filteredProducts.length - 1
    ) {
      filas += `</div></div></div>`;
    }
  });

  filas += `</div>
            <button class="carousel-control-prev justify-content-start d-flex" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next justify-content-end   d-flex" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Siguiente</span>
            </button>
          </div>`;

  document.getElementById("products-container").innerHTML = filas;
}

function getItemsPerSlide() {
  const width = window.innerWidth;
  if (width >= 1400) {
    return 5; // xl
  } else if (width >= 995) {
    return 4; // lg
  } else if (width >= 768) {
    return 3; // md
  } else if (width >= 576) {
    return 2; // sm
  } else {
    return 1; // xs
  }
}
