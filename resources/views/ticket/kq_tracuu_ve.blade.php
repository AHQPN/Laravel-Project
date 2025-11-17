@extends('layouts.guest')

@section('title','Kết quả tra cứu vé')

@section('content')
    <h3>Kết quả tra cứu vé</h3>

    @if(!empty($tickets) && count($tickets)>0)
        <table class="table table-bordered">
            <thead><tr><th>Mã vé</th><th>Chuyến</th><th>Ghế</th><th>Trạng thái</th></tr></thead>
            <tbody>
            @foreach($tickets as $t)
                <tr>
                    <td>{{ $t->mave }}</td>
                    <td>{{ $t->machuyendi }}</td>
                    <td>{{ $t->maghe }}</td>
                    <td>{{ $t->trangthai }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Không tìm thấy vé.</p>
    @endif

@endsection
