@php
use App\Models\CategoryModel;
use App\Helpers\URL;
$categoryModel = new CategoryModel();
$categories = $categoryModel->listItems(null, ['task' => 'store-list-items']);
$cartQuantity = 0;
if (!empty(session()->get('cart'))) {
    $cart = session()->get('cart');
    foreach ($cart['quantity'] as $id => $quantity) {
        $cartQuantity += $quantity;
    }
}
@endphp

@if (Request::get('search') != null || Request::get('search') != '')
    @php
        $search = Request::get('search');
    @endphp
    {{ redirect()->route('book/list', ['search' => $search]) }}
@endif
<header class="my-header sticky">
    <div class="mobile-fix-option"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-menu">
                    <div class="menu-left">
                        <div class="brand-logo">
                            <a href="{{ route('home') }}">
                                <h2 class="mb-0" style="color: #5fcbc4">BookStore</h2>
                            </a>
                        </div>
                    </div>
                    <div class="menu-right pull-right">
                        <div>
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li>
                                        <div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2"
                                                aria-hidden="true"></i></div>
                                    </li>
                                    {{-- {{ request()->is('sites/*/edit') ? 'active' : '' }} --}}
                                    {{-- <li><a href="{{ route('home') }}" class="my-menu-link active">Trang chủ</a></li> --}}
                                    <li><a href="{{ route('home') }}"
                                            class="my-menu-link {{ Request::routeIs('home') ? 'active' : '' }}">Trang
                                            chủ</a></li>
                                    <li><a href="{{ route('book/list') }}"
                                            class="my-menu-link {{ Request::routeIs('book/list') ? 'active' : '' }}">Sách</a>
                                    </li>
                                    <li>
                                        <a href="#">Danh mục</a>
                                        <ul>
                                            @foreach ($categories as $item)
                                                @php
                                                    $link = URL::linkCategory($item['id'], $item['name']);
                                                @endphp
                                                <li><a href="{{ $link }}">{{ $item['name'] }}</a></li>
                                            @endforeach

                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="top-header">
                            <ul class="header-dropdown">
                                <li class="onhover-dropdown mobile-account">


                                    {{-- <li><a href="#">{{ session('userInfo')['name'] }}</a>
                                            </li> --}}
                                    <?php $nav_items = config('voyager.dashboard.navbar_items');
                                    
                                    ?>
                                    @if (is_array($nav_items) && !empty($nav_items))
                                        @foreach ($nav_items as $name => $item)
                                            @if (isset($item['route']) && $item['route'] == 'voyager.logout')
                                                @if (Auth::user())
                                                    <img src="{{ asset('store') }}/images/avatar.png" alt="avatar">
                                                    <ul class="onhover-show-div">
                                                        <li><a href="#">{{ Auth::user()->name }}</a>
                                                        </li>
                                                        @if (Auth::user()->role_id == 1)
                                                            <li><a href="{{ route('voyager.dashboard') }}">Trang quản
                                                                    lí</a>
                                                            </li>
                                                        @endif
                                                        <form action="{{ route('voyager.logout') }}" method="POST">
                                                            {{ csrf_field() }}
                                                            <button type="submit" class="btn btn-danger btn-block">
                                                                Đăng xuất
                                                            </button>
                                                        </form>

                                                        {{-- <li><a href="{{ route('voyager.logout') }}">Đăng xuất</a></li> --}}
                                                    @else
                                                        <img src="{{ asset('store') }}/images/avatar.png"
                                                            alt="avatar">
                                                        <ul class="onhover-show-div">
                                                            <li><a href="{{ route('auth/login') }}">Đăng nhập</a>
                                                            </li>
                                                            <li><a href="{{ route('auth/register') }}">Đăng ký</a>
                                                            </li>


                                                        </ul>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div>
                            <div class="icon-nav">
                                <ul>
                                    <li class="onhover-div mobile-search">
                                        <div>
                                            <img src="{{ asset('store') }}/images/search.png" onclick="openSearch()"
                                                class="img-fluid blur-up lazyload" alt="">
                                            <i class="ti-search" onclick="openSearch()"></i>
                                        </div>
                                        <div id="search-overlay" class="search-overlay">
                                            <div>
                                                <span class="closebtn" onclick="closeSearch()"
                                                    title="Close Overlay">×</span>
                                                <div class="overlay-content">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <form action="" method="GET">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"
                                                                            name="search" id="search-input"
                                                                            placeholder="Tìm kiếm sách...">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary"><i
                                                                            class="fa fa-search"></i></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="onhover-div mobile-cart">
                                        <div>
                                            <a href="{{ route('cart/show') }}" id="cart" class="position-relative">
                                                <img src="{{ asset('store') }}/images/cart.png"
                                                    class="img-fluid blur-up lazyload" alt="cart">
                                                <i class="ti-shopping-cart"></i>
                                                <span class="badge badge-warning"
                                                    id="cart_quantity">{{ $cartQuantity }}</span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
