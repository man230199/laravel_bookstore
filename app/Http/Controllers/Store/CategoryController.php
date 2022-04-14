<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel as MainModel;
use App\Models\CategoryModel;
use App\Models\BookModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $pathViewController = 'store.pages.category.';  // category
    private $controllerName     = 'category';
    private $params             = [];
    private $model              = '';

    public function __construct()
    {
        $this->params["pagination"]["totalItemsPerPage"] = 8;
        $this->model = new MainModel();
        view()->share('controllerName', $this->controllerName);
    }
    public function index(Request $request)
    {
        $this->params['search']     = $request->search;
        $params["category_id"]      = $request->category_id;
        $bookModel      = new BookModel();
        $categoryModel  = new CategoryModel();
        $featuredItems  = $bookModel->listItems($this->params, ['task' => 'store-list-featured-items']);
        $items          = $categoryModel->getItem($params, ['task' => 'store-get-item']);
        if (empty($items))  return redirect()->route('home');
        $this->params['category_id']    = $items['id'];
        $items['products']              = $bookModel->listItems($this->params, ['task' => 'store-list-items-in-category']);
        $request_id = $request->category_id;
        $breadcrumb = 'Danh mục sản phẩm' . ' / ' . $items['name'];
        return view('store.pages.book.list', [
            'params'        => $this->params,
            'items'         => $items,
            'request_id'    => $request_id,
            'featuredItems' => $featuredItems,
            'breadcrumb'   => $breadcrumb
        ]);
    }
}
