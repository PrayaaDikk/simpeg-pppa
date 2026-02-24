import "./bootstrap";
import "flowbite";
import Chart from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";
import Alpine from "alpinejs";

Chart.register(ChartDataLabels);

window.Chart = Chart;
window.ChartDataLabels = ChartDataLabels;

window.Alpine = Alpine;
Alpine.start();
