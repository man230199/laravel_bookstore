@php
use App\Helpers\Helpers;
use App\Helpers\URL;
@endphp
<div class="theme-card">
    <h5 class="title-border">Sách nổi bật</h5>
    <div class="offer-slider slide-1">
        @foreach ($featuredItems as $index => $item)
            @php
                $total = count($featuredItems);
                $middle = $total / 2;
                $title = Helpers::stringLength($item['name'], 100, 50);
                $link = URL::linkBook($item['id'], $item['name']);
            @endphp
            @if ($index == 0)
                <div>
            @endif
            @if ($index == $middle)
    </div>
    <div>
        @endif
        @if ($index == $total - 1)
    </div>
    @endif
    <div class="media">
        <a href="{{ $link }}">
            <img class="img-fluid blur-up lazyload" src="{{ asset('store/images') }}/{{ $item['picture'] }}"
                alt="{{ $item['name'] }}"></a>
        <div class="media-body align-self-center">
            <div class="rating">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>

            <a href="{{ $link }}" title="{{ $item['name'] }}">
                <h6>{{ $title }}</h6>
            </a>
            <h4 class="text-lowercase">{{ number_format($item['price']) }} đ</h4>
        </div>
    </div>
    @endforeach
</div>
</div>
