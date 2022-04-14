<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\CategoryModel as MainModel;
use App\Http\Requests\CategoryRequest as MainRequest;

class CategoryController extends AdminController
{
    protected $pathViewController = 'admin.pages.category.';
    protected $controllerName     = 'category';
    protected $params             = [];
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new MainModel();
    }

    public function index(Request $request)
    {
        $this->params['filter']['status'] = $request->input('filter_status', 'all');
        $this->params['search']['field']  = $request->input('search_field', ''); // all id description
        $this->params['search']['value']  = $request->input('search_value', '');

        $items              = MainModel::withDepth()->having('depth', '>', '0')->defaultOrder()->get()->toFlatTree();
        $itemsStatusCount   = $this->model->countItems($this->params, ['task' => 'admin-count-items-group-by-status']); // [ ['status', 'count']]

        return view($this->pathViewController .  'index', [
            'params'            => $this->params,
            'items'             => $items,
            'itemsStatusCount'  =>  $itemsStatusCount
        ]);
    }

    public function updateTree(Request $request)
    {
        $data = $request->data;
        $rootItem = MainModel::where('name', 'Root')->get()->first();
        $root = MainModel::find($rootItem->id);
        MainModel::rebuildSubtree($root, $data);
        return response()->json($data);
    }

    public function save(MainRequest $request)
    {
        if ($request->method() == 'POST') {
            $params = $request->all();

            $task   = "add-item";
            $notify = "Thêm phần tử thành công!";

            if ($params['id'] !== null) {
                $task   = "edit-item";
                $notify = "Cập nhật phần tử thành công!";
            }
            $this->model->saveItem($params, ['task' => $task]);
            return redirect()->route($this->controllerName)->with("coffee_store_notify", $notify);
        }
    }

    public function form(Request $request)
    {
        $item = null;
        $categories = MainModel::withDepth()->defaultOrder();
        if ($request->id !== null) {
            $params["id"] = $request->id;
            $item = $this->model->getItem($params, ['task' => 'get-item']);
            $categories = $categories->where('_lft', '<', $item['_lft'])->orWhere('_lft', '>', $item['_rgt']);
        }
        $categories = $categories->get()->toFlatTree()->pluck('name_with_depth', 'id');
        return view($this->pathViewController .  'form', [
            'item'          => $item,
            'categories'    => $categories
        ]);
    }

    public function isHome(Request $request)
    {
        $params["currentIsHome"]  = $request->is_home;
        $params["id"]             = $request->id;
        $this->model->saveItem($params, ['task' => 'change-is-home']);
        $isHomeValue = $request->is_home == 'yes' ? 'no' : 'yes';

        $link = route($this->controllerName . '/isHome', ['is_home' => $isHomeValue, 'id' => $request->id]);
        return response()->json([
            'isHomeObj' => config('coffee.template.is_home')[$isHomeValue],
            'link' => $link,
        ]);
    }

    public function move(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $node = MainModel::find($id);
        if ($type == 'up') {
            $node->up();
        }
        if ($type == 'down') {
            $node->down();
        }
        return redirect()->back();
    }
}
