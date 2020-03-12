<canvas id="APG"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('APG').getContext('2d');
        var APG_chart = new Chart(ctx, {
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
                labels: ['Male', 'Female'],
                datasets: [{
                    backgroundColor: ['#02EF72', '#1FCB4A'],
                    data: []
                }],

            },

        });
    </script>
@endpush
