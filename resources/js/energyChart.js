import Chart from "chart.js/auto";

document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("energyChart");
    if (!ctx) return;

    new Chart(ctx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "Energy Consumption (kWh)",
                    data: [120, 135, 115, 160, 145, 155, 170],
                    fill: true,
                    backgroundColor: "rgba(20, 67, 224, 0.2)",
                    borderColor: "#1443e0",
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: "#1443e0",
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "#1e57ff",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "kWh",
                    },
                },
                x: {
                    title: {
                        display: true,
                        text: "Month",
                    },
                },
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: "#1443e0",
                        font: {
                            weight: "bold",
                            size: 14,
                        },
                    },
                },
                tooltip: {
                    mode: "index",
                    intersect: false,
                },
            },
            interaction: {
                mode: "nearest",
                axis: "x",
                intersect: false,
            },
        },
    });
});
