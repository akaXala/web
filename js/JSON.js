document.addEventListener("DOMContentLoaded", function() {
    const productsContainer = document.getElementById('products-container');

    fetch('products.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const products = data.products;
            products.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.classList.add('product');
                
                const productTitle = document.createElement('h2');
                productTitle.textContent = product.title;
                productDiv.appendChild(productTitle);
                
                const productDescription = document.createElement('p');
                productDescription.textContent = product.description;
                productDiv.appendChild(productDescription);
                
                const productCategory = document.createElement('p');
                productCategory.textContent = `Category: ${product.category}`;
                productDiv.appendChild(productCategory);
                
                const productPrice = document.createElement('p');
                productPrice.textContent = `Price: $${product.price}`;
                productDiv.appendChild(productPrice);
                
                const productRating = document.createElement('p');
                productRating.textContent = `Rating: ${product.rating}`;
                productDiv.appendChild(productRating);
                
                const productStock = document.createElement('p');
                productStock.textContent = `Stock: ${product.stock}`;
                productDiv.appendChild(productStock);
                
                if (product.images && product.images.length > 0) {
                    const productImage = document.createElement('img');
                    productImage.src = product.images[0];
                    productDiv.appendChild(productImage);
                }
                
                productsContainer.appendChild(productDiv);
            });
        })
        .catch(error => {
            console.error('Error fetching the products:', error);
        });
});
