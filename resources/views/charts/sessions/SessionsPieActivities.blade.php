<canvas id="SPA"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('SPA').getContext('2d');
        var SPA_chart = new Chart(ctx, {
            type: 'pie',
            displayLegend: false,
            options: {
                title: {
                    minimalist: true,
                },
                legend: {
                    display: false,
                },
                maintainAspectRatio: false,
            },
            data: {
                labels: [],
                datasets: [{
                    backgroundColor: ['#9219F1', '#AD52F4', '#C380F7', '#D8ADFA', '#EEDBFD'],
                    data: []
                }],

            },

        });
    </script>
@endpush
