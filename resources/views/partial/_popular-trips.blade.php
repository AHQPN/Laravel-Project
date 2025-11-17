{{-- [Nguồn: 6] --}}
{{--
  Biến $popularTrips (một mảng) được truyền vào
  từ Controller (ví dụ: HomeController@index)
--}}
<section class="popular-routes">
    <div class="container">
        <h2 class="title">TUYẾN PHỔ BIẾN</h2>
        <p class="subtitle">Được khách hàng tin tưởng và lựa chọn</p>

        <div class="routes-grid">
            @if (isset($popularTrips) && count($popularTrips) > 0)
                @foreach ($popularTrips as $card)
                    <div class="routes-card">
                        <div class="header-image"> {{-- [Nguồn: 7] --}}
                            <div class="overlay"></div>
                            <div class="header-content">
                                <p class="subtitle">Tuyến xe từ</p>
                                <h2 class="title">{{ $card['Departure'] }}</h2> {{-- [Nguồn: 8] --}}
                            </div>
                        </div>

                        <div class="routes-list">
                            @foreach ($card['Routes'] as $route) {{-- [Nguồn: 9] --}}
                            <a class="route-item" href="{{ route('trip.gfind', [
                                        'FromCity' => $route['Departure'],
                                        'ToCity' => $route['Destination'],
                                        'txtDate' => $route['Date']->format('Y-m-d'),
                                        'SoVe' => 1
                                    ]) }}"> {{-- [Nguồn: 10, 11] --}}

                                <div class="info">
                                    <h3>{{ $route['Destination'] }}</h3> {{-- [Nguồn: 12] --}}
                                    <p>{{ $route['Duration'] }} - {{ $route['Date']->format('d/m/Y') }}</p>
                                </div> {{-- [Nguồn: 13] --}}
                                <div class="price">{{ number_format($route['Price'], 0, ',', '.') }} đ</div>
                            </a> {{-- [Nguồn: 14] --}}
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <p>Không có tuyến nào phổ biến.</p>
            @endif
        </div>
    </div>
</section>
