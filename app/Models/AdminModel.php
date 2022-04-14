<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB; 

class AdminModel extends Model
{
     
    public $timestamps = false;
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $table            = '';
    protected $folderUpload     = '' ;
    protected $fieldSearchAccepted   = [
        'id',
        'name'
    ]; 

    protected $crudNotAccepted = [
        '_token',
        'thumb_current',
    ];


    public function uploadThumb($thumbObj) {
        $thumbName        = Str::random(6) . '.' . $thumbObj->clientExtension();
        $thumbObj->storeAs($this->folderUpload, $thumbName, 'coffee_store_images');
        return $thumbName;
    }

    public function deleteThumb($thumbName){
        Storage::disk('coffee_store_images')->delete($this->folderUpload . '/' . $thumbName);
    }

    public function prepareParams($params){
        return array_diff_key($params, array_flip($this->crudNotAccepted));
    }

}

