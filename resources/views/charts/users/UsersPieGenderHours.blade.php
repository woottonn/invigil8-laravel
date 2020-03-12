<canvas id="UPGH"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('UPGH').getContext('2d');
        var UPGH_chart = new Chart(ctx, {
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
                    backgroundColor: ['#55EF72', '#55CB4A'],
                    data: []
                }],

            },

        });
    </script>
@endpush
