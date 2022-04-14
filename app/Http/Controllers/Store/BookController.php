<?php

namespace App\Http\Controllers\Store;

use App\Helpers\URL;
use App\Http\Controllers\Controller;
use App\Models\AttributeModel;
use App\Models\CategoryModel;
use App\Models\BookModel as MainModel;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $pathViewController = 'store.pages.book.';  // slider
    private $controllerName     = 'book';
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
        $item = $this->model->getItem(['product_id' => $request->product_id], ['task' => 'store-get-item']);
        $this->params['category_id'] = $item['category_id'];
        $relateItems = $this->model->listItems(['category_id' => $item['category_id']], ['task' => 'store-list-related-items']);
        $featuredItems = $this->model->listItems($this->params, ['task' => 'store-list-featured-items']);
        $breadcrumb = 'Danh mục sản phẩm' . ' / ' . $item['category_name'] . ' / ' . $item['name'];

        return view(
            $this->pathViewController . 'index',
            [
                'item' => $item,
                'featuredItems'  => $featuredItems,
                'relatedItems'  => $relateItems,
                'breadcrumb'   => $breadcrumb
            ]
        );
    }

    public function list(Request $request)
    {
        $this->params['search'] = $request->search;
        $this->params['sort']   = $request->sort;
        $items = $this->model->listItems($this->params, ['task' => 'store-list-items']);
        $featuredItems = $this->model->listItems($this->params, ['task' => 'store-list-featured-items']);
        if (empty($items))  return redirect()->route('home');
        $breadcrumb = 'Danh sách sản phẩm';

        return view($this->pathViewController .  'list', [
            'params'            => $this->params,
            'items'             => $items,
            'featuredItems'     => $featuredItems,
            'breadcrumb'        => $breadcrumb
        ]);
    }

    public function ajaxQuickView(Request $request)
    {
        $link = route($this->controllerName . '/ajaxQuickView', ['product_id' => $request->product_id]);
        $params['product_id'] = $request->product_id;
        $item = $this->model->getItem($params, ['task' => 'store-get-item']);
        $sale_price = $item['price'] - ($item['price'] * $item['sale_off'] / 100);
        $detailLink = URL::linkBook($item['id'],$item['name']);
        $picture = asset("store/images" . '/' . $item['picture']);
        $cartLink = route("cart/order", ['product_id' => $item['id'], 'price' => $sale_price, 'quantity' => 'new_quantity']);
        return response()->json([
            'link'              => $link,
            'item'              => $item,
            'picture'           => $picture,
            'detailLink'        => $detailLink,
            'cartLink'          => $cartLink,
        ]);
    }
}
