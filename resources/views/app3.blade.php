@extends('layouts.app')

@section('content')
    {{ Form::open(['action' => ['App\Http\Controllers\OcrSpaceController@index'], 'files' => true]) }}
    {{ Form::token() }}
    {{ Form::file('image', ['class' => 'btn btn-lg btn-light fw-bold border-white bg-white', 'id' => 'selectImage']) }}
    {{ Form::submit('Распознать', ['class' => 'btn btn-light']) }}
    {{ Form::close() }}
@endsection
@push('script')
    <script>
        selectImage.onchange = evt => {
            preview = document.getElementById('preview');
            preview.style.display = 'block';
            const [file] = selectImage.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
    </script>
@endpush
