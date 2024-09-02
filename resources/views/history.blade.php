<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Metric History</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" />
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    </head>
    <body>
        <div class="container mt-5">
            <h2>Metric History</h2>
            <table id="metricsTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>ACCESSIBILITY</th>
                        <th>PWA</th>
                        <th>SEO</th>
                        <th>PERFORMANCE</th>
                        <th>BEST PRACTICES</th>
                        <th>STRATEGY</th>
                        <th>DATETIME</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metrics as $metric)
                    <tr>
                        <td>{{ $metric->url }}</td>
                        <td>{{ $metric->accessibility_metric ?? '-' }}</td>
                        <td>{{ $metric->pwa_metric ?? '-' }}</td>
                        <td>{{ $metric->seo_metric ?? '-' }}</td>
                        <td>{{ $metric->performance_metric ?? '-' }}</td>
                        <td>{{ $metric->best_practices_metric ?? '-' }}</td>
                        <td>{{ $metric->strategy->name }}</td>
                        <td>{{ $metric->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function() {
                $('#metricsTable').DataTable({
                    responsive: true
                });
            });
        </script>
    </body>
</html>
