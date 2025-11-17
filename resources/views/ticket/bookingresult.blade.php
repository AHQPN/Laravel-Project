@extends('layouts.guest')

@section('title','Kết quả đặt vé')

@section('content')
    <h3>Kết quả đặt vé</h3>

    @if(!empty($BookedSeats) && count($BookedSeats)>0)
        <div class="alert alert-success">Đặt thành công: {{ implode(',', $BookedSeats) }}</div>
    @endif

    @if(!empty($FailedSeats) && count($FailedSeats)>0)
        <div class="alert alert-danger">Không đặt được: {{ implode(',', $FailedSeats) }}</div>
    @endif

    <a href="{{ route('ticket.find') }}" class="btn btn-primary">Quay lại tìm chuyến</a>

@endsection
