@extends('layouts.guest')

@section('title','Tìm chuyến')

@section('content')
    <h3>Tìm chuyến</h3>
    <p>Form đơn giản: nhập mã chuyến hoặc nhấn "Tìm" để chuyển tới trang đặt vé.</p>
    <form method="get" action="{{ url('/ticket/book') }}">
        <div class="mb-3">
            <label>Mã chuyến</label>
            <input class="form-control" name="tripID" id="tripID" placeholder="VD: HN-DN-231025C">
        </div>
        <button type="button" id="go" class="btn btn-primary">Mở trang đặt vé</button>
    </form>

    <script>
        document.getElementById('go').addEventListener('click', function(){
            const id = document.getElementById('tripID').value.trim();
            if(!id) { alert('Nhập mã chuyến'); return; }
            window.location = '/ticket/book/' + encodeURIComponent(id);
        });
    </script>

@endsection
