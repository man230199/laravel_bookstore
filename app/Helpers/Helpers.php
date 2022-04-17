<?php

namespace App\Helpers;

class Helpers
{
    public static function stringLength($text, $number = 100, $substr = 50)
    {
        if (strlen($text) > $number)  $text = substr($text, 0, $substr) . '...';
        return $text;
    }


    public static function createImage($item)
    {
        $result = null;
        $picture = $item['picture'];
        $result .= sprintf(
            '<img src="%s" class="img-fluid blur-up lazyload bg-img" alt="">',
            asset("store/images/$picture")
        );
        return $result;
    }

    public static function createSpan($item)
    {
        $result = null;
        $result .= '<span class="lable4 badge badge-danger"> -' . $item . '%</span>';
        return $result;
    }
    public static function productBox($item, $showDesc = false)
    {
        $result = '';
        $shortDescription = ($showDesc == true) ? $item['short_description'] : null;
        $link = URL::linkBook($item['id'], $item['name']);
        $name = Helpers::stringLength($item['name'], 50, 40);
        $image = Helpers::createImage($item);
        $price = number_format($item['price']);
        $sale_off = Helpers::createSpan($item['sale_off']);
        $sale_price = number_format($item['price'] - ($item['price'] * $item['sale_off'] / 100));
        $cartLink = route('cart/order', ['product_id' => $item['id'], 'price' => $item['price'] - ($item['price'] * $item['sale_off'] / 100), 'quantity' => 'new_quantity']);
        $quickviewLink = route('book/ajaxQuickView', ['product_id' => $item['id']]);
        $result .= sprintf(
            '
            <div class="product-box">
                <div class="img-wrapper">
                    <div class="lable-block">
                        %s
                    </div>
                    <div class="front">
                        <a href="%s">
                            %s
                        </a>
                    </div>
                    <div class="cart-info cart-wrap">
                        <a href="javaScript:void(0)" id="add-one-to-cart" data-url="%s" title="Add to cart"><i class="ti-shopping-cart"></i></a>
                        <a href="javaScript:void(0)" title="Quick View"><i class="ti-search" id="ajax-quickview" data-url="%s" data-toggle="modal" data-target="#quick-view"></i></a>
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
                    <a href="%s" title="%s">
                        <h6>%s</h6>
                    </a>
                    <p>%s</p>
                    <h4 class="text-lowercase">%s đ <del>%s đ</del></h4>
                </div>
            </div>
        ',
            $sale_off,
            $link,
            $image,
            $cartLink,
            $quickviewLink,
            $link,
            $item['name'],
            $name,
            $shortDescription,
            $sale_price,
            $price
        );

        return $result;
    }
}
