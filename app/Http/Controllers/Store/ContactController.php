<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;;

use App\Http\Requests\ContactRequest as MainRequest;
use App\Jobs\SendEmail;
use App\Models\MenuModel;
use App\Models\SettingModel;
use App\Models\ContactModel as MainModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    private $pathViewController = 'store.pages.contact.';  // slider
    private $controllerName     = 'contact';
    private $params             = [];
    private $model;

    public function __construct()
    {
        view()->share('controllerName', $this->controllerName);
        $this->model = new MainModel();
    }

    public function index(Request $request)
    {
        $settingModel   = new SettingModel();
        $itemContact = $settingModel->getItem(null, ['task' => 'get-item-setting-general']);
        return view($this->pathViewController .  'index', [
            'params'        => $this->params,
            'itemContact'   => $itemContact,

        ]);
    }

    public function sendContact(MainRequest $request)
    {
        if ($request->method() == 'POST') {
            $params = $request->all();
            $task   = "add-item";
            $notify = '';
            
            $settingModel   = new SettingModel();
            $settingBCC     = $settingModel->getItem(null, ['task' => 'get-item-setting-bcc']);
            $mailBCC        = explode(',', $settingBCC['bcc']);
            $mail = (new SendEmail($params,$mailBCC))->delay(Carbon::now()->addMinute(1));
            dispatch($mail);
          
            if( count(Mail::failures()) > 0 ) {

                foreach(Mail::failures() as $email_address) {
                    echo "$email_address <br />";
                 }
             
             }
            $notify = (count(Mail::failures()) > 0) ? 'Đã xảy ra lõi' : 'Đã gửi mail thành công';

            $this->model->saveItem($params, ['task' => $task]);
            return redirect()->route($this->controllerName . '/index')->with('contact_notify', $notify);
        }
    }
}
