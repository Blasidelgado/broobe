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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
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
            <section id="metrics-results">
                <p>TODO TABLE</p>
            </section>
        </main>
    </body>
    <script>
        $(document).ready(function() {
            // strategies select
            $('#strategy').select2({
                width: 'resolve',
            });

            // form submission handler
            $('#metrics-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'api/metrics',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);

                        localStorage.setItem('metricsData', JSON.stringify(response.data));
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</html>
