<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ArticleModel;
use App\Models\CategoryArticleModel as MainModel;
use Illuminate\Http\Request;

class CategoryArticleController extends Controller
{
    private $pathViewController = 'store.pages.category_article.';  // slider
    private $controllerName     = 'categoryArticle';
    private $params             = [];
    private $model              = '';

    public function __construct()
    {
        $this->params["pagination"]["totalItemsPerPage"] = 5;
        $this->model = new MainModel();
        view()->share('controllerName', $this->controllerName);
    }
    public function index(Request $request)
    {   
        $params["category_article_id"]  = $request->category_article_id;
        $articleModel  = new ArticleModel();

        $itemCategoryArticle = $this->model->getItem($params, ['task' => 'store-get-item']);
        if(empty($itemCategoryArticle))  return redirect()->route('home');

        $itemCategoryArticle['articles'] = $articleModel->listItems(['category_article_id' => $itemCategoryArticle['id']], ['task' => 'store-list-items-in-category-article']);
      
        $breadcrumbs = MainModel::withDepth()->having('depth','>','0')->defaultOrder()->ancestorsAndSelf($request->category_article_id)->pluck('name','id');

        return view($this->pathViewController .  'index', [
            'params'        => $this->params,
            'itemCategoryArticle'  => $itemCategoryArticle,
            'breadcrumbs'   => $breadcrumbs
        ]);
    }
}
