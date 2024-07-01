document.addEventListener('DOMContentLoaded', () => {
    const productTableBody = document.getElementById('productTableBody');
    const sortOrder = document.getElementById('sortOrder');
    const searchInput = document.getElementById('searchInput');

    function renderTable(data) {
        productTableBody.innerHTML = '';
        data.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.id}</td>
                <td>${product.titulo}</td>
                <td>${product.precio}</td>
                <td>${product.descuento}</td>
                <td>${product.stock}</td>
                <td><img src="${product.miniatura}" alt="${product.titulo}" width="50" height="50"></td>
                <td>
                    <a href='../html/modificarProducto.php?productoId=${product.id}' class='btn btn-secondary' style="display: block; margin-bottom: 10px;">Modify product</a>
                    <a href='#' class='btn btn-danger' style="display: block; margin-bottom: 10px;">Remove product</a>
                </td>
            `;
            productTableBody.appendChild(row);
        });
    }

    function filterAndSortProducts() {
        let filteredData = [...productData];

        // Filtrar por título
        const searchTerm = searchInput.value.toLowerCase();
        filteredData = filteredData.filter(product => product.titulo.toLowerCase().includes(searchTerm));

        // Ordenar
        const sortOrderValue = sortOrder.value;
        filteredData.sort((a, b) => {
            switch (sortOrderValue) {
                case 'id_asc':
                    return a.id - b.id;
                case 'id_desc':
                    return b.id - a.id;
                case 'price_asc':
                    return a.precio - b.precio;
                case 'price_desc':
                    return b.precio - a.precio;
                case 'stock_asc':
                    return a.stock - b.stock;
                case 'stock_desc':
                    return b.stock - a.stock;
                default:
                    return 0;
            }
        });

        renderTable(filteredData);
    }

    searchInput.addEventListener('input', filterAndSortProducts);
    sortOrder.addEventListener('change', filterAndSortProducts);

    // Renderizar la tabla con los datos iniciales
    renderTable(productData);
});
