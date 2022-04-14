<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel as MainModel;
use App\Models\CategoryModel;
use App\Http\Requests\ProductRequest as MainRequest;
use App\Models\AttributeModel;
use App\Models\ProductAttributeModel;

class ProductController extends Controller
{
    private $pathViewController = 'admin.pages.product.';
    private $controllerName     = 'product';
    private $params             = [];
    private $model;

    public function __construct()
    {
        $this->model = new MainModel();
        $this->params["pagination"]["totalItemsPerPage"] = 5;
        view()->share('controllerName', $this->controllerName);
    }

    public function index(Request $request)
    {
        $this->params['filter']['status'] = $request->input('filter_status', 'all');
        $this->params['search']['field']  = $request->input('search_field', ''); // all id description
        $this->params['search']['value']  = $request->input('search_value', '');
        $this->params['category_product_id']  = $request->category_product_id;
        $items              = $this->model->listItems($this->params, ['task'  => 'admin-list-items']);
        $itemsStatusCount   = $this->model->countItems($this->params, ['task' => 'admin-count-items-group-by-status']); // [ ['status', 'count']]
        $categories     = CategoryModel::withDepth()->having('depth', '>', '0')->defaultOrder()->get()->toFlatTree()->pluck('name_with_depth', 'id');

        return view($this->pathViewController .  'index', [
            'params'            => $this->params,
            'items'             => $items,
            'itemsStatusCount'  => $itemsStatusCount,
            'categories'        => $categories
        ]);
    }

    public function form(Request $request)
    {
        $item = null;
        // $attributeItems = AttributeModel::select('id', 'name')->get()->toArray();
        if ($request->id !== null) {
            $params["id"] = $request->id;
            $item = $this->model->getItem($params, ['task' => 'get-item']);
            // foreach ($attributeItems as  $key => $attribute) {
            //     $values = ProductAttributeModel::select('value')->where('product_id', $request->id)->where('attribute_id', $attribute['id'])->get()->toArray();
            //     $values = array_column($values, 'value');
            //     $attributeItems[$key]['value'] = implode(',', $values);
            // }
        }
        $categories     = CategoryModel::withDepth()->having('depth', '>', '0')->defaultOrder()->get()->toFlatTree()->pluck('name_with_depth', 'id');
        return view($this->pathViewController .  'form', [
            'item'          => $item,
            'categories'    => $categories,
            // 'attributeItems' => $attributeItems,
            'id'            => $request->id
        ]);
    }

    public function save(MainRequest $request)
    {
        // $productAttriButeModel = new ProductAttributeModel();
        if ($request->method() == 'POST') {
            $params = $request->all();
            $task   = "add-item";
            $notify = "Thêm phần tử thành công!";

            if ($params['id'] !== null) {
                $task   = "edit-item";
                $notify = "Cập nhật phần tử thành công!";
            }
            if ($params['form'] == 'product') {
                $this->model->saveItem($params, ['task' => $task]);
            }
            if ($params['form'] == 'product_attribute') {
                // $productAttriButeModel->saveItem($params, ['task' => $task]);
            }
            
            if ($params['form'] == 'product_image') {
                $imagesName = json_decode($params['hidden_images'][0], JSON_UNESCAPED_UNICODE);

                foreach ($imagesName as $image) {
                    //  $image->move(public_path('images'), $image);
                    $params['images'][] = $image;
                   
                    $this->model->saveItem($params, ['task' => 'add-item-image']);
                    return response()->json(['success' => $image]);
                }
                
                // echo '<pre>';
                // print_r($image);
                // echo '</pre>';die();
                //$avatarName = $image->getClientOriginalName();
                // 


                // $imageUpload->images = $avatarName;
                // $imageUpload->save();

            }
            return redirect()->route($this->controllerName)->with("coffee_store_notify", $notify);
        }
    }


    public function status(Request $request)
    {
        $params["currentStatus"]  = $request->status;
        $params["id"]             = $request->id;
        $this->model->saveItem($params, ['task' => 'change-status']);
        $status = $request->status == 'active' ? 'inactive' : 'active';
        $link = route($this->controllerName . '/status', ['status' => $status, 'id' => $request->id]);
        return response()->json([
            'statusObj' => config('coffee.template.status')[$status],
            'link' => $link,
        ]);
    }

    public function type(Request $request)
    {
        $params["currentType"]    = $request->type;
        $params["id"]             = $request->id;
        $this->model->saveItem($params, ['task' => 'change-type']);
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function category(Request $request)
    {
        $params["currentCategory"]      = $request->category;
        $params["id"]                   = $request->id;
        $this->model->saveItem($params, ['task' => 'change-category']);
        return response()->json([
            'status' => 'success'
        ]);
    }


    public function delete(Request $request)
    {
        $params["id"]             = $request->id;
        $this->model->deleteItem($params, ['task' => 'delete-item']);
        return redirect()->route($this->controllerName)->with('coffee_store_notify', 'Xóa phần tử thành công!');
    }
}
