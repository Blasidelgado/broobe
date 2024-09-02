<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <style>
            .fieldset-title {
                font-size: 0.8rem;
            }
            select {
                width: 100%;
                background-color: red;
            }
        </style>
        <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="container">
        <header class="container h100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-center">
                    <h1 class="fw-light">{{ config('app.name') }}</h1>
                </div>
            </div>
            <nav class="my-3">
                <form id="metrics-form">
                    @csrf
                    <div id="main-form" class="d-flex row">
                        <div class="col-lg-3 col-md-12 mb-3">
                            <label for="url" class="fieldset-title">URL</label>
                            <input id="url" type="text" class="form-control" name="url" placeholder="Enter URL" required>
                        </div>
                        <div class="col-lg-7 col-md-12 mb-3 abc d-flex justify-content-center align-items-center">
                            <fieldset class="w-100 text-center">
                                <legend class="fieldset-title">CATEGORIES</legend>
                                <div class="d-flex flex-wrap justify-content-center">
                                    @foreach ($categories as $category)
                                        <div class="form-check form-check-inline mx-2">
                                            <input id="{{ $category->name }}" class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->name }}">
                                            <label class="form-check-label" for="{{ $category->name }}">
                                                {{ str_replace('_', '-', $category->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-lg-2 col-md-12 mb-3">
                            <label for="strategy" class="fieldset-title">STRATEGY</label>
                            <select id="strategy" class="form-select" name="strategy" style="width: 100%;" required>
                                @foreach ($strategies as $strategy)
                                    <option value="{{ $strategy->name }}">
                                        {{ ucwords(strtolower($strategy->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="metrics-btn" class="mt-3">
                        <button type="submit" class="btn btn-primary">Get Metrics</button>
                    </div>
                </form>
            </nav>
        </header>
        <main>
            <section id="metrics-results" class="mt-5">
                <div>
                    <canvas id="metrics-chart"></canvas>
                </div>

                <button id="save-metrics-btn" class="btn btn-primary mt-3" style="display:none;">Save Metric Run</button>
            </section>
        </main>
    </body>
    <script>
        $(document).ready(function() {
            // strategies select
            $('#strategy').select2({
                width: 'resolve',
            });

            // chart render function
            function renderCharts(data) {
                const categories = ['PERFORMANCE', 'ACCESSIBILITY', 'BEST PRACTICES', 'SEO', 'PWA'];
                const scores = categories.map(category => (data[category] * 100) ?? 0);
                const colors = ['#4caf50', '#2196f3', '#ffeb3b', '#ff5722', '#9c27b0'];

                const ctx = document.getElementById('metrics-chart');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: categories,
                        datasets: [{
                            label: 'Results for ' + data.url,
                            data: scores,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // form submission handler
            $('#metrics-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'api/metrics',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#metrics-form').trigger('reset');
                        $('#metrics-chart').remove();
                        $('#metrics-results').prepend('<canvas id="metrics-chart"></canvas>');
                        const data = response.data;
                        localStorage.setItem('metricsData', JSON.stringify(data));

                        if (data['BEST_PRACTICES']) {
                            data['BEST PRACTICES'] = data['BEST_PRACTICES'];
                            delete data['BEST_PRACTICES'];
                        }

                        renderCharts(data);

                        $('#save-metrics-btn').show();
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred: ' + error);
                    }
                });
            });

            // save metrics button handler
            $('#save-metrics-btn').on('click', function() {
                const metricsData = JSON.parse(localStorage.getItem('metricsData'));

                if (metricsData) {
                    $.ajax({
                        type: 'POST',
                        url: 'api/history',
                        data: metricsData,
                        success: function(response) {
                            alert(response.message);
                            localStorage.removeItem('metricsData');
                        },
                        error: function(xhr, status, error) {
                            console.error('An error occurred while saving metrics: ' + error);
                        }
                    });
                } else {
                    alert('No metrics data found to save.');
                }
    });
        });
    </script>
</html>
