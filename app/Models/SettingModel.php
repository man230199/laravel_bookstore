<?php

namespace App\Models;

use App\Models\AdminModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

class SettingModel extends AdminModel
{
    protected $casts  = [
        'value' => 'array',
    ];
    public function __construct()
    {
        $this->table               = 'setting';
        $this->folderUpload        = 'setting';
        $this->fieldSearchAccepted = ['id', 'key_value', 'value'];
        $this->crudNotAccepted     = ['_token', 'thumb_current', 'current_logo','current_story_image','current_statistic_background', 'task', 'hidden_id'];
    }

    public function listItems($params = null, $options = null)
    {

        $result = null;
        $type = (isset($params['type'])) ? $params['type'] : 'setting-general';
        if ($options['task'] == "admin-list-items") {
            $result = $this->select('value')->where('key_value',$type)->first()->toArray();
            $result = $result['value'];
            
        }

        if ($options['task'] == 'news-list-items-general-setting') {
            $query = $this->select('id', 'key_value', 'value')
                ->where('key_value', '=', 'setting-general');
            $result = $query->first()->toArray();
        }
        if ($options['task'] == 'news-list-items-mail-setting') {
            $query = $this->select('id', 'key_value', 'value')
                ->where('key_value', '=', 'setting-mail');
            $result = $query->first()->toArray();
        }
        if ($options['task'] == 'news-list-items-bcc-setting') {
            $query = $this->select('id', 'key_value', 'value')
                ->where('key_value', '=', 'setting-bcc');
            $result = $query->first()->toArray();
        }
        if ($options['task'] == 'news-list-items-social-setting') {
            $query = $this->select('id', 'key_value', 'value')
                ->where('key_value', '=', 'setting-social');
            $result = $query->first()->toArray();
        }

        if ($options['task'] == 'news-list-items-social-setting') {
            $query = $this->select('id', 'key_value', 'value')
                            ->where('key_value', '=', 'setting-social');
            $result = $query->first()->toArray();
        }
        return $result;
    }

    public function getItem($params = null, $options = null)
    {
        $result = null;

        if ($options['task'] == 'get-item-setting-general') {
            $result = self::select('value')->where('key_value', 'setting-general')->first()->toArray();
        }
        if ($options['task'] == 'get-item-setting-mail') {
            $result = self::select('value')->where('key_value', 'setting-mail')->first()->toArray();
        }
        if ($options['task'] == 'get-item-setting-bcc') {
            $result = self::select('value')->where('key_value', 'setting-bcc')->first()->toArray();
        }
        if ($options['task'] == 'get-item-setting-social') {
            $result = self::select('value')->where('key_value', 'setting-bcc')->first()->toArray();
        }
        if ($options['task'] == 'get-item-setting-home-images') {
            $result = self::select('value')->where('key_value', 'setting-home-images')->first()->toArray();
        }
        $result = $result['value'];
        return $result;
       // return json_decode($result['value'], true);
    }

    public function saveItem($params = null, $option = null)
    {
        if ($option['task'] == 'setting-general') {
            if (!empty($params['logo'])) {
                $this->deleteThumb($params['current_logo']);
                $params['logo'] = $this->uploadThumb($params['logo']);
            } else {
                $params['logo'] = $params['current_logo'];
            }
            
        }
        if ($option['task'] == 'setting-home-images') {
            if (!empty($params['story_image'])) {
                $this->deleteThumb($params['current_story_image']);
                $params['story_image'] = $this->uploadThumb($params['story_image']);
            } else {
                $params['story_image'] = $params['current_story_image'];
            }

            if (!empty($params['statistic_background'])) {
                $this->deleteThumb($params['current_statistic_background']);
                $params['statistic_background'] = $this->uploadThumb($params['statistic_background']);
            } else {
                $params['statistic_background'] = $params['current_statistic_background'];
            }
        }
        $params['value'] = json_encode($this->prepareParams($params));
        self::where('key_value', $params['task'])->update(['value' => $params['value']]);
    }

}
