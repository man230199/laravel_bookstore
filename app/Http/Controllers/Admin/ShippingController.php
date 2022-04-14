<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingModel as MainModel;
use App\Http\Requests\ShippingRequest as MainRequest;

class ShippingController extends AdminController
{
    protected $pathViewController = 'admin.pages.shipping.';  // Shipping
    protected $controllerName     = 'shipping';
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

        $items              = $this->model->listItems($this->params, ['task'  => 'admin-list-items']);
        $itemsStatusCount   = $this->model->countItems($this->params, ['task' => 'admin-count-items-group-by-status']); 

        $cityList = $this->model->listItems($this->params,['task' => 'news-list-items']);

        return view($this->pathViewController .  'index', [
            'params'            => $this->params,
            'items'             => $items,
            'itemsStatusCount'  =>  $itemsStatusCount,
            'cityList'          => $cityList
        ]);
    }

    public function form(Request $request)
    {
        $item = null;
        if ($request->id !== null) {
            $params["id"] = $request->id;
            $item = $this->model->getItem($params, ['task' => 'get-item']);
        }

        return view($this->pathViewController .  'form', [
            'item' => $item
        ]);
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
            return redirect()->route($this->controllerName)->with("zvn_notify", $notify);
        }
    }

    public function showShipping(Request $request)
    {
        // $params["currentStatus"]  = $request->status;
        // $params["id"]             = $request->id;
        // $this->model->saveItem($params, ['task' => 'change-status']);
        // $status = $request->status == 'active' ? 'inactive' : 'active';
        // $link = route($this->controllerName . '/status', ['status' => $status, 'id' => $request->id]);
        // return response()->json([
        //     'statusObj' => config('zvn.template.status')[$status],
        //     'link' => $link,
        // ]);
    }
}
