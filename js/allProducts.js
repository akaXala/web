document.addEventListener("DOMContentLoaded", () => {
  console.log("Página cargada"); // Debug: Log when the page is loaded
  console.log("Cargando productos..."); // Debug: Log when products are being loaded

  const urlParams = new URLSearchParams(window.location.search);
  let productSearch = urlParams.get("productSearch");
  let category = urlParams.get("category");
  let products = [];
  let currentPage = 1;
  const itemsPerPage = 20; // 4 filas con 5 productos cada una

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
      setupPagination(products, category, productSearch);
    })
    .catch((error) => console.error("Error loading JSON:", error));

  // Event listener for the search input
  document
    .getElementById("productSearch")
    .addEventListener("input", (event) => {
      const searchTerm = event.target.value.toLowerCase();
      console.log(`Buscando: ${searchTerm}`); // Debug: Log the search term
      currentPage = 1; // Reset to the first page on new search
      displayProducts(products, searchTerm, category); // Filter and display products based on the search term
      setupPagination(products, category, searchTerm); // Adjust pagination based on search
    });

  // Event listener for the form submission
  document.querySelector("input").addEventListener("submit", (event) => {
    event.preventDefault();
    const searchTerm = document
      .getElementById("productSearch")
      .value.toLowerCase();
    console.log(`Buscando: ${searchTerm}`); // Debug: Log the search term
    currentPage = 1; // Reset to the first page on new search
    displayProducts(products, searchTerm, category); // Filter and display products based on the search term
    setupPagination(products, category, searchTerm); // Adjust pagination based on search
  });

  function setupPagination(products, category = "", filter = "") {
    const paginationContainer = document.getElementById("pagination-container");
    if (!paginationContainer) {
      console.error("No se encontró el contenedor de paginación.");
      return;
    }
    const filteredProducts = filter
      ? products.filter((product) =>
          product.title.toLowerCase().includes(filter)
        )
      : category
      ? products.filter(
          (product) => product.category.toLowerCase() === category
        )
      : products;
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    let paginationHTML = "";

    paginationHTML += `<li class="page-item ${
      currentPage === 1 ? "disabled" : ""
    }">
      <a class="page-link" href="#" onclick="changePage(${
        currentPage - 1
      })">Anterior</a>
    </li>`;

    for (let i = 1; i <= totalPages; i++) {
      paginationHTML += `<li class="page-item ${
        i === currentPage ? "active" : ""
      }">
        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
      </li>`;
    }

    paginationHTML += `<li class="page-item ${
      currentPage === totalPages ? "disabled" : ""
    }">
      <a class="page-link" href="#" onclick="changePage(${
        currentPage + 1
      })">Siguiente</a>
    </li>`;

    paginationContainer.innerHTML = `<ul class="pagination">${paginationHTML}</ul>`;
  }

  window.changePage = (page) => {
    const totalPages = Math.ceil(products.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
      currentPage = page;
      const searchTerm = document
        .getElementById("productSearch")
        .value.toLowerCase();
      const urlParams = new URLSearchParams(window.location.search);
      let category = urlParams.get("category");
      displayProducts(products, searchTerm, category);
      setupPagination(products, category, searchTerm); // Update pagination based on current page
    }
  };

  function displayProducts(products, filter = "", category = "") {
    let filas = "";
    let col = 0;

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

    // Filter products if a filter or category is provided
    const filteredProducts = filter
      ? products.filter((product) =>
          product.title.toLowerCase().includes(filter)
        )
      : category
      ? products.filter(
          (product) => product.category.toLowerCase() === category
        )
      : products;

    if (filteredProducts.length === 0) {
      document.getElementById(
        "products-container"
      ).innerHTML = `<div class="d-flex justify-content-center" style="padding-top:7rem; padding-bottom:8rem"><p class="text-center" style="font-size:50px; font-weight:bold">No hay resultados para "${filter}".</p></div>`;
      document.getElementById("pagination-container").innerHTML = ""; // Clear pagination if no results
      return;
    }

    // Paginate the filtered products
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

    paginatedProducts.forEach((product, indice) => {
      let newPrice = parseFloat(
        parseInt(product.price * (100 - product.discountPercentage)) / 100
      );

      if (indice % 5 === 0) {
        if (col !== 0) {
          filas += `</div></div>`; // Close previous row if it's open
        }
        filas += `<div class="container text-center"><div class="row align-items-start">`;
      }

      filas += `
        <div class="col mb-3 d-flex justify-content-center" style="flex: 0 0 ${
          100 / 5
        }%;">
          <div class="card" style="width: 13rem;">
            <img src="${
              product.thumbnail
            }" class="card-img-top" style="height: 150px; object-fit: cover;" alt="${
        product.title
      }">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title d-flex justify-content-start" style="min-height: 3em;">${
                product.title
              }</h5>
              <p class="card-text d-flex mb-0 justify-content-start text-decoration-line-through fw-light">$${
                product.price
              }</p>
              <div class="d-flex justify-content-between my-0 py-0">
                <p class="card-text" style="font-size:30px;font-weight:bold">$${newPrice}</p>
                <p class="card-text" style="font-size:15px;color:#0b5ed7">${
                  product.discountPercentage
                }%<br>OFF</p>
              </div>
              <a href="${address}?id=${
        product.id
      }" class="btn btn-primary my-auto">Ver</a>
            </div>
          </div>
        </div>`;

      col++;
      if (col === 5) {
        col = 0;
      }
    });

    // If there's an open row, close it
    if (col !== 0) {
      filas += `</div></div>`;
    }

    document.getElementById("products-container").innerHTML = filas;
  }

  function displayProductsByCategory(products, category) {
    currentPage = 1;
    displayProducts(products, "", category);
    setupPagination(products, category); // Update pagination based on category
  }
});
