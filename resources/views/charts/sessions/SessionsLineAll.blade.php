<canvas id="SLA"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('SLA').getContext('2d');
        var SLA_chart = new Chart(ctx, {
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
                    backgroundColor: ['#555555', '{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}','{{pastelGenerate()}}'],
                    data: []
                }],

            },

        });
    </script>
@endpush
