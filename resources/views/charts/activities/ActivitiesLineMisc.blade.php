<canvas id="ALM"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ALM').getContext('2d');
        var ALM_chart = new Chart(ctx, {
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
                    backgroundColor: ['#888', '{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}','{{pastelGenerate(3)}}'],
                    data: []
                }],

            },

        });
    </script>
@endpush
