<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class URL
{
    public static function linkCategory($id, $name)
    {
        return route('category/index', ['category_name' => Str::slug($name), 'category_id' => $id]);
    }

    public static function linkBook($id, $name)
    {
        return route('book/index', ['product_name' =>  Str::slug($name), 'product_id' => $id]);
    }
}
