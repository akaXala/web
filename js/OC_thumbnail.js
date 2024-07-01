// Fetch and display the top 3 most purchased products
fetch('../php/obtenerCompras.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

        data.sort((a, b) => b.compras - a.compras);
        const top3 = data.slice(0, 3); // Muestra los 3 productos más comprados

        const labels = top3.map(producto => producto.titulo);
        const compras = top3.map(producto => producto.compras);

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Compras',
                    data: compras,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error fetching data:', error));
