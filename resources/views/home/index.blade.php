@extends('layouts.khach')

@section('content')

    @if (session('message'))
        <div id="mes"
             class="position-fixed top-0 start-50 translate-middle-x mt-3 p-3 rounded-3 shadow text-white fw-bold"
             role="alert" aria-live="assertive" aria-atomic="true"
             style="z-index: 9999; min-width: 350px; text-align:center; background-color:#dc3545; transition: opacity 0.5s ease; opacity: 1;">
            {!! session('message') !!}
        </div>

        <script>
            const mes = document.getElementById('mes');
            if (mes) {
                setTimeout(() => {
                    mes.style.opacity = '0';
                    setTimeout(() => mes.remove(), 500);
                }, 2500);
            }
        </script>
    @endif

@endsection
