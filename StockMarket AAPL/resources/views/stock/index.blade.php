<!DOCTYPE html>
<html>

<head>
    <title>Stock Website</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <canvas id="stockChart"></canvas>

    <script>
        // Retrieve the stock data from PHP and format it for the chart
        var stockData = @json($data);

        var labels = stockData.map(function (stock) {
            return stock['Date'];
        });

        var closePrices = stockData.map(function (stock) {
            return parseFloat(stock['Close']);
        });

        // Create the chart
        var ctx = document.getElementById('stockChart').getContext('2d');
        var stockChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'AAPL Stock Prices',
                    data: closePrices,
                    borderColor: 'blue',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Price'
                        }
                    }
                }
            }
        });
    </script>

    <table align="center">
        <tr>
            <th>Date</th>
            <th>Open</th>
            <th>High</th>
            <th>Low</th>
            <th>Close</th>
            <th>Volume</th>
        </tr>
        @foreach ($data as $stock)
        <tr>
            <td>{{ $stock['Date'] }}</td>
            <td>{{ $stock['Open'] }}</td>
            <td>{{ $stock['High'] }}</td>
            <td>{{ $stock['Low'] }}</td>
            <td>{{ $stock['Close'] }}</td>
            <td>{{ $stock['Volume'] }}</td>
        </tr>
        @endforeach
    </table>

    
</body>

</html>
