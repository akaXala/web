// Fetch and display the daily logins for the last 3 days
fetch('../php/obtenerRegistrosDiarios.php')
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

        // Limita los datos a los últimos 3 días
        const lastThreeDays = data.slice(-3);

        const labels = lastThreeDays.map(registro => registro.fecha);
        const conteos = lastThreeDays.map(registro => registro.conteo);

        const ctxLogins = document.getElementById('loginsChart').getContext('2d');
        const loginsChart = new Chart(ctxLogins, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros',
                    data: conteos,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    pointStyle: 'rectRot',
                    pointRadius: 5,
                    pointBorderColor: 'rgba(75, 192, 192, 1)',
                    pointBackgroundColor: 'rgba(75, 192, 192, 0.2)'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error fetching data:', error));
