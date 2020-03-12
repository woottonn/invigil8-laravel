<canvas id="ATPP"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ATPP').getContext('2d');
        var ATPP_chart = new Chart(ctx, {
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
                    backgroundColor: ['#06DCFB', '#98F0FD', '#D5F9FE', '#D7FFFD', '#ccc'],
                    data: []
                }],

            },

        });
    </script>
@endpush
