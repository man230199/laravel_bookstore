<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;;    

use App\Models\ArticleModel as MainModel;
use App\Models\CategoryModel;
use App\Models\MenuModel;
use App\Models\SettingModel;

class ArticleController extends Controller
{
    private $pathViewController = 'store.pages.article.';  // slider
    private $controllerName     = 'article';
    private $params             = [];
    private $model;

    public function __construct()
    {
        $this->params["pagination"]["totalItemsPerPage"] = 3;
        $this->model = new MainModel();
        view()->share('controllerName', $this->controllerName);
    }

    public function index(Request $request)
    {   
        $params["article_id"]  = $request->article_id;
        
        $itemArticle = $this->model->getItem($params, ['task' => 'store-get-item']);
        if(empty($itemArticle))  return redirect()->route('home');
        // $itemsLatest   = $this->model->listItems(null, ['task'  => 'store-list-items-latest']);
        
        $params["category_article_id"]  = $itemArticle['category_article_id'];
        $recentItems = $this->model->listItems($params,['task' => 'store-list-items-recent']);
        // $itemArticle['related_articles'] = $this->model->listItems($params, ['task' => 'store-list-items-related-in-category']);
       
        $breadcrumbs = CategoryModel::withDepth()->having('depth','>','0')->defaultOrder()->ancestorsAndSelf($itemArticle['category_article_id'])->pluck('name','id');

        return view($this->pathViewController .  'index', [
            'params'        => $this->params,
            'recentItems'   => $recentItems,
            'itemArticle'   => $itemArticle,
            'breadcrumbs'   => $breadcrumbs
        ]);
    }

 
}