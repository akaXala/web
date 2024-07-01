document.addEventListener('DOMContentLoaded', () => {
    const orderTableBody = document.getElementById('orderTableBody');
    const filterMonth = document.getElementById('filterMonth');
    const sortOrder = document.getElementById('sortOrder');
    const applyFilters = document.getElementById('applyFilters');

    function renderTable(data) {
        orderTableBody.innerHTML = '';
        data.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${order.id}</td>
                <td>${order.fecha}</td>
                <td>${order.usuario_id}</td>
                <td>${order.nombre}</td>
                <td>${order.primerAp}</td>
                <td><a href='../php/generarPDF.php?orderId=${order.id}&userId=${order.usuario_id}' class='btn btn-primary' target='_blank'>Ver PDF</a></td>
            `;
            orderTableBody.appendChild(row);
        });
    }

    function filterAndSortOrders() {
        let filteredData = orderData;

        // Filtrar por mes
        const selectedMonth = filterMonth.value;
        if (selectedMonth) {
            const [year, month] = selectedMonth.split('-');
            filteredData = filteredData.filter(order => {
                const orderDate = new Date(order.fecha);
                return orderDate.getFullYear() === parseInt(year) && (orderDate.getMonth() + 1) === parseInt(month);
            });
        }

        // Ordenar por fecha
        const sortOrderValue = sortOrder.value;
        filteredData.sort((a, b) => {
            const dateA = new Date(a.fecha);
            const dateB = new Date(b.fecha);
            return sortOrderValue === 'asc' ? dateA - dateB : dateB - dateA;
        });

        renderTable(filteredData);
    }

    applyFilters.addEventListener('click', filterAndSortOrders);

    // Renderizar la tabla con los datos iniciales
    renderTable(orderData);
});
