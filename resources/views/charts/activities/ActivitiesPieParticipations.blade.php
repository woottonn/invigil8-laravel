<canvas id="APP"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('APP').getContext('2d');
        var APP_chart = new Chart(ctx, {
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
                    backgroundColor: ['#06DCFB', '#98F0FD', '#D5F9FE', '#D7FFFD', '#75d0cb'],
                    data: []
                }],

            },

        });
    </script>
@endpush
