<?php

namespace App\Models;

use App\Models\AdminModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryModel extends AdminModel
{
   // use NodeTrait;
    protected $table               = 'category';
    protected $fillable            = ['status', 'name','is_home'];
    protected $crudNotAccepted     = ['_token'];

    // public function getNameWithDepthAttribute()
    // {
    //     return str_repeat('/-----', $this->depth) . $this->name;
    // }

    // public function categoriesProduct()
    // {
    //     return $this->hasMany(CategoryModel::class, 'parent_id', 'id')->with('categories');
    // }

    // public function categoriesProductActive()
    // {
    //     return $this->hasMany(CategoryModel::class, 'parent_id', 'id')->where('status', 'active')->with('categoriesActive');
    // }
    

    public function listItems($params = null, $options = null)
    {
        //self::fixTree();
        $result = null;
        if ($options['task'] == "admin-list-items") {
            $query = $this->select('id', 'name', 'status', 'is_home', 'display', 'created', 'created_by', 'modified', 'modified_by', 'parent_id')->whereNull('parent_id');

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
            $result =  $query->orderBy('id', 'desc')->with('categories')->get();
        }

        if ($options['task'] == 'store-home-list-items') {
            $query = $this->select('id', 'name')
                ->where('status','active')
                ->limit(3);

            $result = $query->get()->toArray();
        }

        if ($options['task'] == 'store-list-items') {
            $query = $this->select('id', 'name')
                ->where('status','active');
            $result = $query->get()->toArray();
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
            $result = self::select('id', 'name', 'status', 'parent_id','_lft','_rgt')->where('id', $params['id'])->first();
        }

        if ($options['task'] == 'store-get-item') {
            $result = self::select('id', 'name')->where('id', $params['category_id'])->first();

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

        if ($options['task'] == 'add-item') {
            $params['created_by'] = "hailan";
            $params['created']    = date('Y-m-d H:m:s');
            $parent = self::find($params['parent_id']);
            self::create($this->prepareParams($params),$parent);
            /* if ($params['parent_id'] == 0) unset($params['parent_id']);
            self::insert($this->prepareParams($params)); */
        }

        if ($options['task'] == 'edit-item') {
            $params['modified_by']   = "hailan";
            $params['modified']      = date('Y-m-d');
            $parent = self::find($params['parent_id']);
            $currentNode = self::find($params['id']);
            $currentNode->update($this->prepareParams($params));
            if($currentNode->parent_id != $params['parent_id']) $currentNode->appendToNode($parent)->save();
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
