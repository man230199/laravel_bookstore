<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class FileManagerController extends Controller
{
    private $pathViewController = 'admin.pages.filemanager.';  // slider
    private $controllerName     = 'filemanager';
    private $params             = [];
    private $model;

    public function __construct()
    {
        //$this->model = new MainModel();
        $this->params["pagination"]["totalItemsPerPage"] = 5;
        view()->share('controllerName', $this->controllerName);
    }

    public function index(Request $request)
    {
        return view($this->pathViewController .  'index');
    }


   
}
