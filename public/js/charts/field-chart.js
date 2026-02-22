export function initFieldChart(elementId, bidangData) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return;

    // Hitung total untuk persentase
    const total = bidangData.reduce((sum, item) => sum + item.count, 0);

    const labels = bidangData.map((item) => `Bidang ${item.akronim}`);
    const data = bidangData.map((item) => item.count);

    const config = {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Jumlah Pegawai",
                    data: data,
                    backgroundColor: "#4A4AFF",
                    borderRadius: 6,
                    barThickness: "flex",
                    maxBarThickness: 50,
                },
            ],
        },
        options: {
            indexAxis: "y",
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: "Poppins", size: 12 },
                    },
                    grid: { display: true, drawBorder: false },
                },
                y: {
                    ticks: {
                        font: { family: "Poppins", size: 12, weight: "500" },
                    },
                    grid: { display: false },
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    padding: 12,
                    titleFont: { family: "Poppins", size: 13, weight: "bold" },
                    bodyFont: { family: "Poppins", size: 12 },
                    callbacks: {
                        label: (context) => {
                            const count = context.parsed.x;
                            const percentage = ((count / total) * 100).toFixed(
                                1,
                            );
                            return `Jumlah: ${count} pegawai (${percentage}%)`;
                        },
                    },
                },
                datalabels: {
                    color: "#ffffff",
                    font: {
                        family: "Poppins",
                        size: 13,
                        weight: "bold",
                    },
                    anchor: "center",
                    align: "center",
                    formatter: (value, context) => {
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${value} (${percentage}%)`;
                    },
                },
            },
        },
        plugins: [ChartDataLabels],
    };

    return new Chart(ctx, config);
}
