var ctx = document.getElementById('grafico').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
        datasets: [{
            label: 'Monto de ventas',
            backgroundColor: 'rgba(68, 200, 242, 0.75)',
            borderColor: 'rgb(255,255,255)',
            data: [1, 10, 5, 2, 20, 30, 45]
        }]
    },

    // Configuration options go here
    options: {}
});


