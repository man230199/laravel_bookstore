<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\SliderModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    private $pathViewController = 'store.pages.home.';  // slider
    private $controllerName     = 'home';
    private $params             = [];

    public function __construct()
    {
        view()->share('controllerName', $this->controllerName);
    }

    public function index()
    {
        $sliderModel = new SliderModel();
        $bookModel = new BookModel();
        $categoryModel = new CategoryModel();
        $featuredItems = $bookModel->listItems(null, ['task' => 'store-list-featured-items']);
        $sliderItems = $sliderModel->listItems(null, ['task' => 'store-list-items']);
        $categoryItems = $categoryModel->listItems(null, ['task' => 'store-home-list-items']);
        $cateID = array_unique(array_column($categoryItems, 'id'));
        foreach ($cateID as $id) {
            $productsByCategory['book'][$id] = $bookModel->listItems(['category_id' => $id], ['task' => 'store-list-items-by-category']);
        }        
        return view(
            $this->pathViewController . 'index',
            [
                'featuredItems' => $featuredItems,
                'sliderItems' => $sliderItems,
                'categoryItems' => $categoryItems,
                'productsByCategory' => $productsByCategory,
            ]
        );
    }
}
