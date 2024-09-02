<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Metric History</title>
        <!-- Datatable installation -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" />
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
        <!-- Bootstrap js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <!-- Boostrap css -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css" rel="stylesheet">
    </head>
    <body>
        <header class="container h100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-center">
                    <h1 class="fw-light">{{ config('app.name') }}</h1>
                </div>
            </div>
            <nav class="navbar">
                <ul class="nav nav-underline d-flex justify-content-evenly w-100">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Load Metrics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('history') ? 'active' : '' }}" href="/history">Metric History</a>
                    </li>
                </ul>
            </nav>
        </header>
        <main class="container mt-5">
            <h2>Metric History</h2>
            <table id="metrics-table" class="table table-striped" style="width:100%">
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
        </main>
        <script>
            $(document).ready(function() {
                $('#metrics-table').DataTable({
                    responsive: true
                });
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
    </body>
</html>
