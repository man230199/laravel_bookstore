@php
use App\Helpers\Helpers;
use App\Helpers\URL;
@endphp
<div class="title1 section-t-space title5">
    <h2 class="title-inner1">Sản phẩm nổi bật</h2>
    <hr role="tournament6">
</div>

<!-- Product slider -->
<section class="section-b-space p-t-0 j-box ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="product-4 product-m no-arrow">
                    @foreach ($featuredItems as $item)
                        @php
                            $description = Helpers::stringLength($item['short_description'], 50, 100);
                            $salePrice = $item['price'] - ($item['price'] * $item['sale_off']) / 100;
                            $link = URL::linkBook($item['id'], $item['name']);
                            $cartLink = route('cart/order', ['product_id' => $item['id'], 'price' => $salePrice, 'quantity' => 'new_quantity']);
                            $quickview = route('book/ajaxQuickView', ['product_id' => $item['id']]);
                        @endphp
                        <div class="product-box">
                            <div class="img-wrapper">
                                <div class="lable-block">
                                    <span class="lable4 badge badge-danger"> -{{ $item['sale_off'] }}%</span>
                                </div>
                                <div class="front">
                                    <a href="{{ $link }}">
                                        <img src="{{ asset('store/images') }}/{{ $item['picture'] }}"
                                            class="img-fluid blur-up lazyload bg-img" alt="product">
                                    </a>
                                </div>
                                <div class="cart-info cart-wrap">
                                    <a href="javaScript:void(0)" id="add-one-to-cart" data-url="{{ $cartLink }}"
                                        title="Add to cart"><i class="ti-shopping-cart"></i></a>
                                    <a href="javaScript:void(0)" title="Quick View"><i
                                            class="ti-search" data-toggle="modal" id="ajax-quickview"
                                            data-target="#quick-view" data-url="{{ $quickview }}"></i></a>
                                </div>
                            </div>
                            <div class="product-detail">
                                <div class="rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <a href="{{ $link }}" title="{{ $item['name'] }}">
                                    <h6>{!! $description !!}</h6>
                                </a>
                                <h4 class="text-lowercase">{{ number_format($salePrice) }} đ
                                    <del>{{ number_format($item['price']) }} đ</del>
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
