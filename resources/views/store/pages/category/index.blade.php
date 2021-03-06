<!DOCTYPE html>
<html lang="en">

@include('store.elements.head')
@php
use App\Helpers\Helpers;
@endphp

<body>
    <div class="loader_skeleton">
        <div class="typography_section">
            <div class="typography-box">
                <div class="typo-content loader-typo">
                    <div class="pre-loader"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- header start -->
    @include('store.elements.header')
    <!-- header end -->

    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title">
                        <h2 class="py-2">Tất cả sách</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section-b-space j-box ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 collection-filter">
                        <!-- side-bar colleps block stat -->
                        <div class="collection-filter-block">
                            <!-- brand filter start -->
                            <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left"
                                        aria-hidden="true"></i> back</span></div>
                            @include('store.blocks.collapse-category')
                        </div>

                        @include('store.blocks.featured-book')
                        <!-- silde-bar colleps block end here -->
                    </div>
                    <div class="collection-content col">
                        <div class="page-main-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="collection-product-wrapper">
                                        <div class="product-top-filter">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="filter-main-btn">
                                                        <span class="filter-btn btn btn-theme"><i class="fa fa-filter"
                                                                aria-hidden="true"></i>
                                                            Filter</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="product-filter-content">
                                                        <div class="collection-view">
                                                            <ul>
                                                                <li><i class="fa fa-th grid-layout-view"></i></li>
                                                                <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                            </ul>
                                                        </div>
                                                        <div class="collection-grid-view">
                                                            <ul>
                                                                <li class="my-layout-view" data-number="2">
                                                                    <img src="images/icon/2.png" alt=""
                                                                        class="product-2-layout-view">
                                                                </li>
                                                                <li class="my-layout-view" data-number="3">
                                                                    <img src="images/icon/3.png" alt=""
                                                                        class="product-3-layout-view">
                                                                </li>
                                                                <li class="my-layout-view active" data-number="4">
                                                                    <img src="images/icon/4.png" alt=""
                                                                        class="product-4-layout-view">
                                                                </li>
                                                                <li class="my-layout-view" data-number="6">
                                                                    <img src="images/icon/6.png" alt=""
                                                                        class="product-6-layout-view">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-page-filter">
                                                            <form action="" id="sort-form" method="GET">
                                                                <select id="sort" name="sort">
                                                                    <option value="default" selected> - Sắp xếp -
                                                                    </option>
                                                                    <option value="price_asc">Giá tăng dần</option>
                                                                    <option value="price_desc">Giá giảm dần</option>
                                                                    <option value="latest">Mới nhất</option>
                                                                </select>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-wrapper-grid" id="my-product-list">
                                            <div class="row margin-res">
                                                @foreach ($items['products'] as $item)
                                                    @php
                                                        $productBox = Helpers::productBox($item);
                                                    @endphp
                                                    <div class="col-xl-3 col-6 col-grid-box">
                                                        {!! $productBox !!}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @include('store.pagination.index')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('store.blocks.phonering')


    <!-- Quick-view modal popup start-->
    @include('store.blocks.quickview')
    <!-- Quick-view modal popup end-->

    @include('store.elements.footer')
    <!-- footer end -->

    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="fa fa-angle-double-up"></i>
        </div>
    </div>
    <!-- tap to top end -->

    @include('store.elements.script')
</body>

</html>
