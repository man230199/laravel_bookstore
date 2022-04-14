<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingModel as MainModel;
use App\Http\Requests\SettingRequest as MainRequest;

class SettingController extends AdminController
{
    protected $pathViewController = 'admin.pages.setting.';  // slider
    protected $controllerName     = 'setting';
    protected $params             = [];
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new MainModel();
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $setting              = $this->model->listItems($params, ['task'  => 'admin-list-items']);

        return view($this->pathViewController .  'index', [
            'params'            => $this->params,
            'setting'           => $setting,
        ]);
    }
    public function save(MainRequest $request)
    {
        if ($request->method() == 'POST') {
            $params = $request->all();
            $task = $params['task'];
            $this->model->saveItem($params,['task' => $task]);
            
            return redirect()->route('setting',['type' => $task]);
            //return view($this->pathViewController . 'index');
        }
    }
}
