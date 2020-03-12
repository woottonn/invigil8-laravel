<canvas id="ATLA"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ATLA').getContext('2d');
        var ATLA_chart = new Chart(ctx, {
            type: 'bar',
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
                    backgroundColor: ['{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}'],
                    data: []
                }],

            },

        });
    </script>
@endpush
