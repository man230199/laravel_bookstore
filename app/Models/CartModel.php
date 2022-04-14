<?php

namespace App\Models;

use App\Helpers\BackendFunction;
use App\Models\AdminModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class CartModel extends AdminModel
{
    public function __construct()
    {
        $this->table               = 'cart';
        $this->folderUpload        = 'cart';
        $this->fieldSearchAccepted = ['name', 'short_description', 'description', 'price', 'thumb', 'status'];
        $this->crudNotAccepted     = ['_token', 'thumb_current', 'form'];
    }

    /* public function getNameWithDepthAttribute()
    {
        return str_repeat('/-----', $this->depth) . $this->name;
    }

    public function categories()
    {
        return $this->hasMany(CategoryModel::class, 'parent_id', 'id')->with('categories');
    }

    public function categoriesActive()
    {
        return $this->hasMany(CategoryModel::class, 'parent_id', 'id')->where('status', 'active')->with('categoriesActive');
    } */


    public function listItems($params = null, $options = null)
    {

        $result = null;
        if ($options['task'] == "admin-list-items") {
            $query = $this->select('id', 'name', 'short_description', 'description', 'thumb', 'price', 'status', 'is_home', 'created', 'created_by', 'category_product_id', 'modified', 'modified_by');

            if ($params['filter']['status'] !== "all") {
                $query->where('status', '=', $params['filter']['status']);
            }

            if ($params['search']['value'] !== "") {
                if ($params['search']['field'] == "all") {
                    $query->where(function ($query) use ($params) {
                        foreach ($this->fieldSearchAccepted as $column) {
                            $query->orWhere($column, 'LIKE',  "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE',  "%{$params['search']['value']}%");
                }
            }

            if (isset($params['category_product_id'])) {
                $query->where('category_product_id',  $params['category_product_id']);
            }

            $result =  $query->orderBy('id', 'desc')->paginate($params['pagination']['totalItemsPerPage']);
        }

        if ($options['task'] == 'store-list-items-in-cart') {
            $this->table = 'book';
            $cart = $params;
            if (!empty($cart)) {
                $query = $this->select('id', 'name', 'price');
                $ids = [];
                foreach ($params['quantity'] as $key => $value) {
                    $ids[] = $key;
                }
                $query = $this->whereIn('id', $ids);
                $result = $query->get()->toArray();
                foreach ($result as $key => $value) {
                    $result[$key]['quantity']       = $cart['quantity'][$value['id']];
                    //$result[$key]['size']           = $cart['size'][$value['id']];
                    $result[$key]['totalprice']     = $cart['price'][$value['id']];
                    $result[$key]['price']          = $result[$key]['totalprice'] / $result[$key]['quantity'];
                }
            }
        }

        return $result;
    }

    public function countItems($params = null, $options  = null)
    {

        $result = null;

        if ($options['task'] == 'admin-count-items-group-by-status') {

            $query = $this::groupBy('status')
                ->select(DB::raw('status , COUNT(id) as count'));

            if ($params['search']['value'] !== "") {
                if ($params['search']['field'] == "all") {
                    $query->where(function ($query) use ($params) {
                        foreach ($this->fieldSearchAccepted as $column) {
                            $query->orWhere($column, 'LIKE',  "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE',  "%{$params['search']['value']}%");
                }
            }

            $result = $query->get()->toArray();
        }

        return $result;
    }

    public function getItem($params = null, $options = null)
    {
        $result = null;

        if ($options['task'] == 'get-item') {
            $result = self::select('id', 'name', 'status', 'price', 'short_description', 'description', 'thumb', 'category_product_id')->where('id', $params['id'])->first();
        }

        if ($options['task'] == 'store-get-item') {
            $result = self::select('id', 'name', 'thumb', 'description', 'price', 'category_product_id')->where('id', $params['product_id'])->first();

            if ($result) $result = $result->toArray();
        }

        return $result;
    }

    public function saveItem($params = null, $options = null)
    {
        if ($options['task'] == 'change-status') {
            $status = ($params['currentStatus'] == "active") ? "inactive" : "active";
            self::where('id', $params['id'])->update(['status' => $status]);
        }


        if ($options['task'] == 'submit-cart') {
            $params['id']               = BackendFunction::randomString(7);
            $params['books']            = json_encode($params['form']['book_id']);
            $params['prices']           = json_encode($params['form']['price']);
            $params['quantities']       = json_encode($params['form']['quantity']);
            $params['names']            = json_encode($params['form']['product_name'], JSON_UNESCAPED_UNICODE);
            $params['thumbs']           = json_encode($params['form']['picture']);
            $params['date']             = date('Y-m-d H:i:s', time());
            $params['username']         = session()->get('userInfo')['name'];
            // $params['pictures']      = $this->uploadThumb($params['form']['thumb']);
            self::insert($this->prepareParams($params));
        }
    }

    public function removeItem($params = null, $options = null)
    {
        $cart = Session()->get('cart');
        if ($options['task'] == 'remove-item') {
            $currentNode = self::find($params['product_id']);
            $currentNode->delete();
        }
    }
}
