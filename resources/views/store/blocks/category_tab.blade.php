@php
use App\Helpers\URL;
use App\Helpers\Helpers;
@endphp
<div class="title1 section-t-space title5">
    <h2 class="title-inner1">Danh mục nổi bật</h2>
    <hr role="tournament6">
</div>
<section class="p-t-0 j-box ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="theme-tab">
                    <ul class="tabs tab-title">
                        @foreach ($categoryItems as $index => $category)
                            @php
                                $activeTab = $index == 0 ? 'current' : '';
                            @endphp
                            <li class="{{ $activeTab }}"><a href="tab-category-{{ $category['id'] }}"
                                    class="my-product-tab"
                                    data-category="{{ $category['id'] }}">{{ $category['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                    @foreach ($categoryItems as $index => $category)
                        <div class="tab-content-cls">
                            @php
                                $activeClass = $index == 0 ? 'active default' : '';
                                $categoryID = $category['id'];
                                $linkCategory = URL::linkCategory($categoryID, $category['name']);
                                
                            @endphp
                            <div id="tab-category-{{ $category['id'] }}" class="tab-content {{ $activeClass }}">
                                <div class="no-slider row tab-content-inside">
                                    @foreach ($productsByCategory['book'][$categoryID] as $index => $book)
                                        @if ($categoryID == $book['category_id'] && $index < 8)
                                            @php
                                                $name = Helpers::stringLength($book['name']);
                                                $salePrice = $book['price'] - ($book['price'] * $book['sale_off']) / 100;
                                                $price = $book['price'];
                                                $linkBook = URL::linkBook($book['id'], $book['name']);
                                                $cartLink = route('cart/order', ['product_id' => $book['id'], 'price' => $salePrice, 'quantity' => 'new_quantity']);
                                                $quickview = route('book/ajaxQuickView', ['product_id' => $book['id']]);
                                            @endphp
                                            {{-- <div id="tab-category-{{ $category_id }}"
                                        class="tab-content {{ $activeClass }}"> --}}

                                            <div class="product-box">
                                                <div class="img-wrapper">
                                                    <div class="lable-block">
                                                        <span class="lable4 badge badge-danger">
                                                            -{{ $book['sale_off'] }}%</span>
                                                    </div>
                                                    <div class="front">
                                                        <a href="{{ $linkBook }}">
                                                            <img src="{{ asset('store/images') }}/{{ $book['picture'] }}"
                                                                class="img-fluid blur-up lazyload bg-img" alt="product">
                                                        </a>
                                                    </div>
                                                    <div class="cart-info cart-wrap">
                                                        <a href="javaScript:void(0)" id="add-one-to-cart"
                                                            data-url="{{ $cartLink }}"><i
                                                                class="ti-shopping-cart"></i></a>
                                                        <a href="javaScript:void(0)" title="Quick View"><i
                                                                class="ti-search" data-toggle="modal"
                                                                data-url="{{ $quickview }}"
                                                                data-target="#quick-view"></i></a>
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
                                                    <a href="{{ $linkBook }}" title="{{ $book['name'] }}">
                                                        <h6>{{ $name }}</h6>
                                                    </a>
                                                    <h4 class="text-lowercase">{{ number_format($salePrice) }} đ
                                                        <del>{{ number_format($price) }} đ</del>
                                                    </h4>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="text-center"><a href="{{ $linkCategory }}" class="btn btn-solid">Xem
                                        tất
                                        cả</a>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </div>
    </div>
</section> <!-- Tab product end -->
