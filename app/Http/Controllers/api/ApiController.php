<?php

namespace App\Http\Controllers\api;

use App\Contact;
use App\Http\Controllers\Controller;
use App\Offer;
use App\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    private $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    /**
     * site Setting
     * @return json
     * @author
     **/
    public function siteSetting()
    {
        $siteSetting = SiteSetting::whereId('1')->first();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $siteSetting]);
    }

    /**
     * get offers funcation
     * @return json
     * @author
     **/
    public function offers(Request $request)
    {
        $offers = Offer::get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $offers]);
    }

    /**
     * contact us
     *
     * @return json
     * @author
     **/
    public function contactUs(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'name'  => 'message',
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $insert          = new Contact;
            $insert->email   = $request->email;
            $insert->phone   = $request->phone;
            $insert->message = $request->message;

            if ($insert->save()) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.message_sent'), 'data' => '']);
            }
        }
    }

    /**
     * site Setting
     * @return json
     * @author
     **/
    public function aboutApp()
    {
        $aboutApp = SiteSetting::whereId('1')->get('site_description');

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $aboutApp]);
    }

}
