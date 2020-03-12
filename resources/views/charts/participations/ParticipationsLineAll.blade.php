<canvas id="PLA"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('PLA').getContext('2d');
        var PLA_chart = new Chart(ctx, {
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
                    backgroundColor: ['#555', '{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}'],
                    data: []
                }],

            },

        });
    </script>
@endpush
