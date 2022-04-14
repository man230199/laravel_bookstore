<?php

namespace App\Models;

use App\Models\AdminModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ArticleModel extends AdminModel
{
    public function __construct()
    {
        $this->table               = 'article as a';
        $this->folderUpload        = 'article';
        $this->fieldSearchAccepted = ['name', 'content'];
        $this->crudNotAccepted     = ['_token', 'thumb_current'];
    }

    public function listItems($params = null, $options = null)
    {

        $result = null;

        if ($options['task'] == "admin-list-items") {
            $query = $this->select('a.id', 'a.name', 'a.short_content','a.status', 'a.content', 'a.thumb', 'a.type','c.id as category_id' ,'c.name as category_name','c.parent_id','c._lft','c._rgt')
                ->leftJoin('category_article as c', 'a.category_article_id', '=', 'c.id');

            if ($params['filter']['status'] !== "all") {
                $query->where('a.status', '=', $params['filter']['status']);
            }
            if ($params['filter']['category_id'] !== "default") {
                // lay danh sach category
                $items =  CategoryModel::withDepth()->having('depth', '>', '0')->defaultOrder()->descendantsAndSelf($params['filter']['category_id'])->pluck('name_with_depth', 'id')->toArray();
                foreach($items as $id => $item) {
                    $ids[] = $id;
                }
                $query->whereIn('a.category_id', $ids);
            }

            if ($params['search']['value'] !== "") {
                if ($params['search']['field'] == "all") {
                    $query->where(function ($query) use ($params) {
                        foreach ($this->fieldSearchAccepted as $column) {
                            $query->orWhere('a.' . $column, 'LIKE',  "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where('a.' . $params['search']['field'], 'LIKE',  "%{$params['search']['value']}%");
                }
            }

            $result =  $query->orderBy('a.id', 'desc');
        }

        if ($options['task'] == 'store-list-items') {
            $query = $this->select('id', 'name', 'thumb')
                ->where('status', '=', 'active')
                ->limit(5);

            $result = $query->get()->toArray();
        }

        if ($options['task'] == 'store-list-items-in-category-article') {
            $params["pagination"]["totalItemsPerPage"] = 3;
            $result = $this->select('id', 'name', 'thumb','short_content','type','created','created_by')
                ->where('status', 'active')
                ->where('category_article_id',$params['category_article_id'])
                ->paginate($params['pagination']['totalItemsPerPage']);

            // $result = $query->get()->toArray();
        }

        if ($options['task'] == 'store-list-items-recent') {
            $query = $this->select('id', 'name', 'thumb','short_content','type','created','created_by')
                ->where('status', 'active')
                ->orderBy('id','desc')
                ->take(3);

            $result = $query->get()->toArray();
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
            $result = self::select('id', 'name', 'short_content','content', 'status', 'thumb', 'category_article_id')->where('id', $params['id'])->first();
        }

        if ($options['task'] == 'get-thumb') {
            $result = self::select('id', 'thumb')->where('id', $params['id'])->first();
        }

        if ($options['task'] == 'store-get-item') {
            $result = self::select('a.id', 'a.name', 'content', 'a.category_article_id', 'c.name as category_name', 'a.thumb', 'a.created')
                ->leftJoin('category_article as c', 'a.category_article_id', '=', 'c.id')
                ->where('a.id', '=', $params['article_id'])
                ->where('a.status', '=', 'active')->first();
            if ($result) $result = $result->toArray();
        }

        return $result;
    }

    public function saveItem($params = null, $options = null)
    {
        $this->table = 'article';
        if ($options['task'] == 'change-status') {
            $status = ($params['currentStatus'] == "active") ? "inactive" : "active";
            self::where('id', $params['id'])->update(['status' => $status]);
        }

        if ($options['task'] == 'change-type') {
            self::where('id', $params['id'])->update(['type' => $params['currentType']]);
        }

        if ($options['task'] == 'change-category') {
            self::where('id', $params['id'])->update(['category_article_id' => $params['currentCategory']]);
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
    }

    public function deleteItem($params = null, $options = null)
    {
        $this->table = 'article';
        if ($options['task'] == 'delete-item') {
            $item   = $this->getItem($params, ['task' => 'get-thumb']);
            $this->deleteThumb($item['thumb']);
            self::where('id', $params['id'])->delete();
        }
    }
}
