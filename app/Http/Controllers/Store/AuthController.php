<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthLoginRequest as MainRequest;
use App\Http\Requests\AuthRegisterRequest as RegisterRequest;
use App\Models\UserModel as MainModel;

class AuthController extends Controller
{
    private $pathViewController = 'store.pages.auth.';  // slider
    private $controllerName     = 'auth';
    private $params             = [];
    private $model;

    public function __construct()
    {
        view()->share('controllerName', $this->controllerName);
    }

    public function login(Request $request)
    {
        if (!empty(Session()->get('userInfo'))) {
            return redirect()->route('home');
        }
        
        session(['link' => url()->previous()]);
        $breadcrumb = 'Đăng nhập';
        return view($this->pathViewController . 'login', ['breadcrumb' => $breadcrumb]);
    }

    public function register(Request $request)
    {
        session(['link' => url()->previous()]);
        $breadcrumb = 'Đăng kí';
        return view(
            $this->pathViewController . 'register',
            ['breadcrumb' => $breadcrumb]
        );
    }

    public function postRegister(Request $request)
    {

        if ($request->method() == 'POST') {
            $params = $request->all()['form'];
            $userModel = new MainModel();
            $user = $userModel->getItem($params, ['task' => 'check-exist-user']);
            if ($user == null) {
                $userModel->saveItem($params, ['task' => 'register']);
                return redirect()->route($this->controllerName . '/register')->with('store_notify', 'Đã đăng kí thành công');
            } else {
                return redirect()->route($this->controllerName . '/register')->with('store_notify', 'Tài khoản đã tồn tại!');
            }
        }
    }

    // middelware


    public function postLogin(Request $request)
    {
        if ($request->method() == 'POST') {
            $params = $request->all()['form'];
            $userModel = new MainModel();
            $userInfo = $userModel->getItem($params, ['task' => 'auth-login']);

            if (!$userInfo)
                return redirect()->route($this->controllerName . '/login')->with('store_notify', 'Tài khoản hoặc mật khẩu không chính xác!');

            $request->session()->put('userInfo', $userInfo);

            return redirect(session('link'));
        }
    }

    public function logout(Request $request)
    {
        if ($request->session()->has('userInfo')) $request->session()->pull('userInfo');
        return redirect()->route('home');
    }
}
