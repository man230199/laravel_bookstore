<!DOCTYPE html>
<html lang="en">
@include('store.elements.head')

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
    @include('store.blocks.breadcrumbs')
    @yield('content')

    @include('store.elements.footer')

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
