<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use View;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getResetToken(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

       $validator= Validator::make($request->all(), ['email' => 'required|email|exists:users']);
        if ($request->wantsJson())
        {
            if($validator->fails())
            {
                return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
            }
             
            $user = User::where('email', $request->input('email'))->first();
            $token = $this->broker()->createToken($user);
            return response()->json(['status'=>'1','token' => $token]);
        }
    }
}
