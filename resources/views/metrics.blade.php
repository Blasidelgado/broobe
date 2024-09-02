<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.0.2/spin.min.js"></script>
        <style>
            .fieldset-title {
                font-size: 0.8rem;
            }
            select {
                width: 100%;
                background-color: red;
            }
            .nav-link {
            position: relative;
            color: #000;
            transition: color 0.3s ease;
            }
            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                background-color: #000;
                left: 50%;
                bottom: -5px;
                transition: width 0.3s ease, left 0.3s ease;
            }
            .nav-link:hover::after {
                width: 100%;
                left: 0;
            }
            .nav-link.active::after {
                width: 100%;
                left: 0;
            }
            #loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
            }
        </style>
    </head>
    <body class="container">
        <div id="loader"></div>
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
        <main>
            <section class="my-3">
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
            </section>
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
            // clear function
            function clearPage() {
                $('#metrics-form').trigger('reset');
                $('#metrics-chart').remove();
                $('#save-metrics-btn').hide();
                $('#metrics-results').prepend('<canvas id="metrics-chart"></canvas>');
            }

            // spinner
            var opts = {
                lines: 13,
                length: 28,
                width: 14,
                radius: 42,
                scale: 1,
                corners: 1,
                color: '#3498db',
                opacity: 0.25,
                rotate: 0,
                direction: 1,
                speed: 1,
                trail: 60,
                fps: 20,
                zIndex: 2e9,
                className: 'spinner',
                top: '50%',
                left: '50%',
                shadow: false,
                hwaccel: false,
                position: 'absolute'
            };

            var target = document.getElementById('loader');
            var spinner = new Spinner(opts).spin(target);

            function showLoader() {
                $('#loader').show();
            }

            function hideLoader() {
                $('#loader').hide();
            }

            // ajax loader management
            $(document).ajaxStart(function(){
                showLoader();
            });

            $(document).ajaxComplete(function(){
                hideLoader();
            });

            $(document).ajaxError(function(){
                hideLoader();
            });

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
                        clearPage();
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
                            clearPage();
                            localStorage.removeItem('metricsData');
                            alert(response.message);
                        },
                        error: function(xhr, status, error) {
                            let response = JSON.parse(xhr.responseText);

                            alert('An error occurred while saving metrics: ' + response.message);

                            console.error('An error occurred while saving metrics: ' + error);
                        }
                    });
                }
            });
        });
    </script>
</html>
