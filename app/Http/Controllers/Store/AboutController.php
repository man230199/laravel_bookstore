<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use App\Models\SettingModel;

class AboutController extends Controller
{
    private $pathViewController = 'store.pages.about.';  // slider
    private $controllerName     = 'about';
    private $params             = [];

    public function __construct()
    {
        view()->share('controllerName', $this->controllerName);
    }
    public function index()
    {
        $productModel = new ProductModel();
        $settingModel = new SettingModel();
        $settingHomeImages = $settingModel::select('value')->where('key_value', 'setting-home-images')->get()->first()->toArray()['value'];
        $bestSellerItems = $productModel->listItems(null, ['task' => 'store-best-seller-items']);
        return view(
            $this->pathViewController . 'index',
            [
                'settingHomeImages' => $settingHomeImages,
                'bestSellerItems'   => $bestSellerItems,
            ]
        );
    }
}
