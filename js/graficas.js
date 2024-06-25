function fetchDataAndRenderCharts() {
    fetch('get_compras.php')
        .then(response => response.json())
        .then(data => {
            // Ordenar los productos por número de compras en orden descendente
            data.sort((a, b) => b.compras - a.compras);

            // Seleccionar los tres productos con mayor número de compras
            const top3 = data.slice(0, 3);

            const labels = top3.map(producto => producto.titulo);
            const compras = top3.map(producto => producto.compras);

            // Configuración de cada gráfica
            const chartConfigs = [
                { id: 'myChart1', label: 'Más Comprados', data: compras },
                { id: 'myChart2', label: 'Más Comprados', data: compras },
                { id: 'myChart3', label: 'Más Comprados', data: compras },
                { id: 'myChart4', label: 'Más Comprados', data: compras }
            ];

            chartConfigs.forEach(config => {
                const ctx = document.getElementById(config.id).getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: config.label,
                            data: config.data,
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
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

fetchDataAndRenderCharts();
