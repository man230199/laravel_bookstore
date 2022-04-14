<?php

namespace App\Models;

use App\Models\AdminModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;


class BookModel extends AdminModel
{
    // use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    // use NodeTrait;
    public function __construct()
    {
        $this->table               = 'book';
        $this->folderUpload        = 'book';
        $this->fieldSearchAccepted = ['name', 'short_description', 'description', 'price', 'thumb', 'status'];
        $this->crudNotAccepted     = ['_token', 'thumb_current', 'form'];
    }


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

        if ($options['task'] == 'store-list-items') {
            $result = $this->select('id', 'name', 'picture', 'short_description', 'description', 'price', 'special', 'sale_off', 'category_id')->where('status', 'active');
            if (isset($params['search'])) {
                $result->where('name', 'LIKE', '%' . $params['search'] . '%');
            }
            if (isset($params['sort'])) {
                if ($params['sort'] == 'price_asc') {
                    $result->where('status', 'active')->orderBy('price', 'asc');
                }
                if ($params['sort'] == 'price_desc') {
                    $result->where('status', 'active')->orderBy('price', 'desc');
                }
                if ($params['sort'] == 'latest') {
                    $result->where('status', 'active')->orderBy('created', 'desc');
                }
            }
            $result = $result->paginate($params["pagination"]["totalItemsPerPage"])->appends(request()->except('page'));
        }
        if ($options['task'] == 'store-list-featured-items') {
            $result = $this->select('id', 'name', 'picture', 'description', 'short_description', 'price', 'special', 'sale_off', 'category_id')->whereNotNull('short_description')->take(8)->get()->toArray();
        }

        if ($options['task'] == 'store-list-related-items') {
            $result = $this->select('id', 'name', 'picture', 'description', 'short_description', 'price', 'special', 'sale_off', 'category_id')->where('category_id', $params['category_id'])->take(6)->get()->toArray();
        }

        if ($options['task'] == 'store-best-seller-items') {
            $query = $this->select('id', 'name', 'picture', 'description', 'price', 'special', 'sale_off', 'category_id')
                ->where('status', 'active')
                ->limit(4);
            $result = $query->get()->toArray();
        }

        if ($options['task'] == 'store-list-items-by-category') {
            $query = $this->select('id', 'name', 'picture', 'short_description', 'price', 'sale_off', 'category_id')
                ->where('status', 'active')
                ->where('category_id', $params['category_id']);
            $result = $query->get()->toArray();
        }

        if ($options['task'] == 'store-list-items-in-category') {
            $query = $this->select('id', 'name', 'picture', 'short_description', 'price', 'sale_off', 'category_id')
                ->where('status', 'active');
            if (isset($params['search'])) {
                $query->where('name', 'LIKE', '%' . $params['search'] . '%');
            } else {
                $query->where('category_id', $params['category_id']);
            }

            $result = $query->paginate($params["pagination"]["totalItemsPerPage"]);
        }

        if ($options['task'] == "admin-list-items-in-selectbox") {
            $query = $this->select('id', 'name')
                ->orderBy('name', 'asc')
                ->where('status', '=', 'active');
            $result = $query->pluck('name', 'id')->toArray();
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
            $result = self::select('id', 'name', 'status', 'price', 'sale_off', 'short_description', 'description', 'picture', 'category_id')->where('id', $params['id'])->first();
        }

        if ($options['task'] == 'store-get-item') {
            $this->table = 'book as b';
            $result = self::select('b.id', 'b.name', 'b.status', 'b.price', 'b.sale_off', 'b.short_description', 'b.description', 'b.picture', 'b.category_id', 'c.name as category_name')->where('b.id', $params['product_id'])->leftJoin('category as c', 'b.category_id', '=', 'c.id')->first();

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

        if ($options['task'] == 'change-is-home') {
            $isHome = ($params['currentIsHome'] == "yes") ? "no" : "yes";
            self::where('id', $params['id'])->update(['is_home' => $isHome]);
        }
        if ($options['task'] == 'change-category') {
            $category = $params['currentCategory'];
            self::where('id', $params['id'])->update(['category_product_id' => $category]);
        }

        if ($options['task'] == 'add-item') {
            $params['created_by'] = "hailan";
            $params['created']    = date('Y-m-d');
            $params['thumb']      = $this->uploadThumb($params['thumb']);
            self::insert($this->prepareParams($params));
        }

        if ($options['task'] == 'edit-item') {
            if (!empty($params['thumb'])) {
                // Xóa hình cũ
                $this->deleteThumb($params['thumb_current']);

                // Up hình mới
                $params['thumb']      = $this->uploadThumb($params['thumb']);
            }

            $params['modified_by']   = "hailan";
            $params['modified']      = date('Y-m-d');

            self::where(['id' => $params['id']])->update($this->prepareParams($params));
        }

        if ($options['task'] == 'add-item-image') {
            $params['modified_by']   = "hailan";
            $params['modified']      = date('Y-m-d');
            foreach ($params['images'] as $image) {
                $params['images']      = $this->uploadThumb($image);
            }

            self::where(['id' => $params['id']])->update($this->prepareParams($params));
        }
    }

    public function deleteItem($params = null, $options = null)
    {
        if ($options['task'] == 'delete-item') {
            $currentNode = self::find($params['id']);
            $currentNode->delete();
        }
    }
}
