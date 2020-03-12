<canvas id="UPGA"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('UPGA').getContext('2d');
        var UPGA_chart = new Chart(ctx, {
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
                    backgroundColor: ['#02EF72', '#1FCB4A'],
                    data: []
                }],

            },

        });
    </script>
@endpush
