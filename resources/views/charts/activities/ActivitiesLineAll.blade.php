<canvas id="ALA"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ALA').getContext('2d');
        var ALA_chart = new Chart(ctx, {
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
