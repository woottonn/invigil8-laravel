<canvas id="ATLM"></canvas>

@push('scripting')
    <script>
        var ctx = document.getElementById('ATLM').getContext('2d');
        var ATLM_chart = new Chart(ctx, {
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
