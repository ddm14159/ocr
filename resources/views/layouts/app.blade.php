<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qwintry OCR</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="d-flex h-100 text-center text-bg-dark">
@inertia
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

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
        <div class="row justify-content-md-center mt-5 gx-0">
            <img id="preview" src="#" class="img-responsive border" style="display:none; max-width: 50%"/>
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
@stack('script')
</body>
</html>
