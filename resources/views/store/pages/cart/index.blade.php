<!DOCTYPE html>
<html lang="en">
@php
use App\Helpers\URL;
@endphp
@extends('store.main')
@section('content')
    @include('store.blocks.notify')
    <form action="{{ route('cart/buy') }}" method="POST" name="admin-form" id="admin-form">
        @csrf
        <section class="cart-section section-b-space">
            <div class="container" id="cart-container">
                @if (!empty($cartItems))
                    <div class="row" id="cart-table">
                        <div class="col-sm-12">

                            <table class="table cart-table table-responsive-xs">
                                <thead>
                                    <tr class="table-head">
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Tên sách</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số Lượng</th>
                                        <th scope="col"></th>
                                        <th scope="col">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum = 0;
                                    @endphp
                                    @foreach ($cartItems as $item)
                                        @php
                                            $linkBook = URL::linkBook($item['id'], $item['name']);
                                            // $salePrice = $item['price'] - ($item['price'] * $item['sale_off']) / 100;
                                            $totalPrice = $item['price'] * $item['quantity'];
                                            $sum += $totalPrice;
                                            $cartLink = route('cart/ajaxChangeQuantity', ['product_id' => $item['id'], 'price' => $item['price'], 'quantity' => 'new_quantity']);
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ $linkBook }}"><img
                                                        src="{{ asset('store/images') }}/{{ $item['picture'] }}"
                                                        alt="{{ $item['name'] }}"></a>
                                            </td>
                                            <td>
                                                <a href="{{ $linkBook }}">{{ $item['name'] }}</a>
                                                <div class="mobile-cart-content row">
                                                    <div class="col-xs-3">
                                                        <div class="qty-box">
                                                            <div class="input-group">
                                                                <input type="number" value="{{ $item['quantity'] }}"
                                                                    data-quantity="{{ $item['id'] }}"
                                                                    class="form-control input-number" min="1">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color text-lowercase">
                                                            {{ number_format($item['price']) }} đ
                                                        </h2>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color text-lowercase">
                                                            <a href="#" class="icon"><i class="ti-close"
                                                                    data-url="{{ route('cart/removeItem', ['product_id' => $item['id']]) }}"></i></a>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h2 class="text-lowercase">{{ number_format($item['price']) }} đ</h2>
                                            </td>
                                            <td>
                                                <div class="qty-box">
                                                    <div class="input-group">
                                                        <input type="number" id="ajax-number-change"
                                                            data-url="{{ $cartLink }}"
                                                            value="{{ $item['quantity'] }}"
                                                            data-id="{{ $item['id'] }}"
                                                            data-price="{{ $item['price'] }}"
                                                            class="form-control input-number" min="1">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><a href="javaScript:void(0)" class="icon"><i
                                                        class="ti-close" id="remove-item"
                                                        data-id="{{ $item['id'] }}"
                                                        data-url="{{ route('cart/removeItem', ['product_id' => $item['id']]) }}"></i></a>
                                            </td>
                                            <td>
                                                <h2 class="td-color text-lowercase"
                                                    id="total-item-price-{{ $item['id'] }}"
                                                    data-id="{{ $item['id'] }}">
                                                    {{ number_format($totalPrice) }} đ
                                                </h2>
                                            </td>
                                        </tr>
                                        <input type="hidden" name="form[book_id][]" value="{{ $item['id'] }}">
                                        <input type="hidden" name="form[price][]" value="{{ $item['price'] }}">
                                        <input type="hidden" name="form[quantity][]" value="{{ $item['quantity'] }}">
                                        <input type="hidden" name="form[product_name][]" value="{{ $item['name'] }}">
                                        <input type="hidden" name="form[picture][]" value="{{ $item['picture'] }}">
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="table cart-table table-responsive-md">
                                <tfoot>
                                    <tr>
                                        <td>Tổng :</td>
                                        <td>
                                            <h2 class="text-lowercase" id="cart_sum">{{ number_format($sum) }} đ</h2>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row cart-buttons">
                        <div class="col-6"><a href="{{ route('book/list') }}" class="btn btn-solid">Tiếp tục
                                mua
                                sắm</a></div>
                        <div class="col-6"><button type="submit" class="btn btn-solid">Đặt hàng</button></div>
                    </div>
                @else
                    <h3 style="text-align:center">Hiện tại không có sản phẩm nào trong giỏ hàng</h3>
                @endif
            </div>
        </section>
        <input type="hidden" name="form[token]" value="{{ csrf_token() }}">
    </form>
@endsection
