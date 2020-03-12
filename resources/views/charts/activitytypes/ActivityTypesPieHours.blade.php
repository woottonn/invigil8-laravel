<canvas id="ATPH"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ATPH').getContext('2d');
        var ATPH_chart = new Chart(ctx, {
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
                    backgroundColor: ['#FF4848', '#FF6A6A', '#FF8C8C', '#FFAEAE', '#FFD0D0', '#ccc'],
                    data: []
                }],

            },

        });
    </script>
@endpush
