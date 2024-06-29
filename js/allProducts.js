document.addEventListener("DOMContentLoaded", () => {
  console.log("Pagina cargada"); // Debug: Log when the page is loaded
  console.log("Cargando productos..."); // Debug: Log when products are being loaded

  const urlParams = new URLSearchParams(window.location.search);
  let productSearch = urlParams.get("productSearch");
  let category = urlParams.get("category");
  let products = [];
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

  console.log(`Address: ${address}`);

  fetch("products.json")
    .then((response) => response.json())
    .then((data) => {
      console.log(productSearch); // Debug: Log the initial search term
      console.log(category); // Debug: Log the initial category
      products = data.products; // Store fetched products
      if (category) {
        displayProductsByCategory(products, category.toLowerCase());
      } else if (productSearch !== null) {
        displayProducts(products, productSearch.toLowerCase());
      } else {
        displayProducts(products); // Display all products if no initial search term
      }
    })
    .catch((error) => console.error("Error loading JSON:", error));

  // Event listener for the search input
  document
    .getElementById("productSearch")
    .addEventListener("input", (event) => {
      const searchTerm = event.target.value.toLowerCase();
      console.log(`Searching for: ${searchTerm}`); // Debug: Log the search term
      displayProducts(products, searchTerm); // Filter and display products based on the search term
    });

  // Prevent form submission on Enter key press
  document.querySelector("form").addEventListener("submit", (event) => {
    event.preventDefault();
  });
});

function displayProducts(products, filter = "") {
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

  console.log(`Address: ${address}`);
  let filas = "";
  let col = 0;

  // Filter products if a filter is provided
  const filteredProducts = filter
    ? products.filter((product) => product.title.toLowerCase().includes(filter))
    : products;

  filteredProducts.forEach((product, indice) => {
    if (indice % 3 === 0) {
      filas += `<div class="container text-center"><div class="row align-items-start">`;
    }

    filas += `
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <img src="${product.thumbnail}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">${product.title}</h5>
                        <p class="card-text">${product.description}</p>
                        <a href="${address}?id=${product.id}" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
        `;

    if (col === 2) {
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

function displayProductsByCategory(products, category) {
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

  console.log(`Address: ${address}`);

  let filas = "";
  let col = 0;

  // Filter products by category
  const filteredProducts = products.filter(
    (product) => product.category.toLowerCase() === category
  );

  filteredProducts.forEach((product, indice) => {
    if (indice % 3 === 0) {
      filas += `<div class="container text-center"><div class="row align-items-start">`;
    }

    filas += `
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <img src="${product.thumbnail}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">${product.title}</h5>
                        <p class="card-text">${product.description}</p>
                        <a href="${address}?id=${product.id}" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
        `;

    if (col === 2) {
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
