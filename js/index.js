document.addEventListener("DOMContentLoaded", () => {
  console.log("Pagina cargada"); // Debug: Log when the page is loaded
  console.log("Cargando productos..."); // Debug: Log when products are being loaded
  let products = [];

  fetch("products.json")
    .then((response) => response.json())
    .then((data) => {
      products = data.products; // Store fetched products
      displayTopRatedProducts(products); // Display top 6 rated products initially
    })
    .catch((error) => console.error("Error loading JSON:", error));

  // Event listener for the search input
  document
    .getElementById("productSearch")
    .addEventListener("input", (event) => {
      const searchTerm = event.target.value.toLowerCase();
      console.log(`Searching for: ${searchTerm}`); // Debug: Log the search term
      displayProducts(products, searchTerm); // Filter and display products
    });
});

function displayTopRatedProducts(products) {
  // Sort products by rating in descending order
  const topRatedProducts = products
    .sort((a, b) => b.rating - a.rating)
    .slice(0, 6);

  displayProducts(topRatedProducts);
}

function displayProducts(products, filter = "") {
  let filas = "";
  let col = 0;

  // Filter products if a filter is provided
  const filteredProducts = filter
    ? products.filter((product) => product.title.toLowerCase().includes(filter))
    : products;

  filteredProducts.forEach((product, indice) => {
    if (indice % 3 == 0) {
      filas += `<div class="container text-center"><div class="row align-items-start">`;
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

    if (col == 2) {
      filas += `</div></div>`;
      col = 0;
    } else {
      col++;
    }
  });

  // If there's an open row, close it
  if (col !== 0) {
    filas += `</div></div>`;
  }

  document.getElementById("products-container").innerHTML = filas;
}
