@extends('store.main')
@section('content')
    <!-- Home slider -->
    @include('store.blocks.slider')
    <!-- Home slider end -->

    <!-- Top Collection -->
    <!-- Title-->
    @include('store.blocks.product_slider')
    <!-- Product slider end -->
    <!-- Top Collection end-->

    <!-- service layout -->
    @include('store.blocks.service')
    <!-- service layout  end -->

    <!-- Tab product -->

    @include('store.blocks.category_tab')

    <!-- Quick-view modal popup start-->
    @include('store.blocks.quickview')
    <!-- Quick-view modal popup end-->

    <!-- footer -->

    @include('store.blocks.phonering')
@endsection
