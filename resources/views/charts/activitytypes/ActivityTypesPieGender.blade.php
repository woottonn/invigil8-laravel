<canvas id="ATPG"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ATPG').getContext('2d');
        var ATPG_chart = new Chart(ctx, {
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
