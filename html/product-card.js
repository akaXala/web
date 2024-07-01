document.addEventListener("DOMContentLoaded", function () {
  const product = {
    imageUrl: "https://via.placeholder.com/250",
    title: "Nombre del Producto",
    price: "$999.99",
    oldPrice: "$1299.99",
    discount: "23% OFF",
  };

  const productContainer = document.getElementById("product-container");

  const productCard = `
      
    `;

  productContainer.innerHTML = productCard;
});
