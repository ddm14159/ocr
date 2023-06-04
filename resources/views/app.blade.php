<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.min.css">
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body>
        <div class="container mt-5">
            @inertia
            <div class="container">
                <div class="row justify-content-md-center">
                    @if (isset($error))
                        <div class="alert alert-danger w-75 h-25">
                            {{ $error }}
                        </div>
                    @endif
                    @if (isset($warning))
                        <div class="alert alert-warning w-75 h-25">
                            {{ $warning }}
                        </div>
                    @endif
                    @if (isset($success))
                        <div class="alert alert-success w-75 h-25">
                            {{ $success }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger w-75 h-25">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    @yield('content')
                </div>
                <div>
                    @if (isset($text))
                        @foreach($text as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </body>
</html>
