<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\ShopCat;
use App\ShopMaint;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function Login(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'phone'       => 'required|exists:users,phone',
            'password'    => 'required',
            'device_id'   => 'required',
            'device_type' => 'required',
            'lang'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            if (Auth::attempt(['phone' => request('phone'), 'password' => request('password')])) {
                $user                    = Auth::user();
                $updateUser              = $user::find($user->id);
                $updateUser->device_id   = request('device_id');
                $updateUser->device_type = request('device_type');
                if ($updateUser->save()) {
                    $updateUser->settings()->update(['lang' => $inputs['lang']]);
                    $success['access_token'] = $user->createToken('MyApp')->accessToken;
                    return response()->json($success, $this->successStatus);
                } else {
                    return response()->json(['error' => 'User Update.'], 401);
                }
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        }
    }

    /**
     * Register new user function
     *
     * @return void
     * @author
     **/
    public function Register(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|confirmed|min:6',
            'phone'       => 'required|unique:users,phone',
            'address'     => 'required',
            'lat'         => 'required',
            'lng'         => 'required',
            'device_id'   => 'required',
            'device_type' => 'required',
            'lang'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $insert              = new User;
            $insert->name        = $inputs['name'];
            $insert->email       = $inputs['email'];
            $insert->password    = bcrypt($inputs['password']);
            $insert->phone       = $inputs['phone'];
            $insert->address     = $inputs['address'];
            $insert->lat         = $inputs['lat'];
            $insert->lng         = $inputs['lng'];
            $insert->device_id   = $inputs['device_id'];
            $insert->device_type = $inputs['device_type'];

            if ($insert->save()) {
                $insert->settings()->create(['lang' => $inputs['lang']]);
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.signing_successfully'), 'data' => $inputs]);
            }
        }
    }

    /**
     * Update User function
     *
     * @return void
     * @author
     **/
    public function UpdateUser(Request $request)
    {
        $inputs = $request->all();
        $id     = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'email'   => 'required|email|unique:users,email,' . $id,
            'phone'   => 'required|unique:users,phone,' . $id,
            'address' => 'required',
            'lat'     => 'required',
            'lng'     => 'required',
            'lang'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $update = User::find($id);

            $update->name        = $inputs['name'];
            $update->email       = $inputs['email'];
            $update->phone       = $inputs['phone'];
            $update->address     = $inputs['address'];
            $update->lat         = $inputs['lat'];
            $update->lng         = $inputs['lng'];
            $update->device_id   = $inputs['device_id'];
            $update->device_type = $inputs['device_type'];

            $update->settings()->update(['lang' => $inputs['lang']]);

            if ($update->save()) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.updated_successfully'), 'data' => $inputs]);
            }

        }
    }

    /**
     * Register Company function
     * @return void
     * @author
     **/
    public function RegisterCompany(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'logo'                    => 'required',
            'commercial_register_img' => 'required',
            'name'                    => 'required',
            'commercial_register'     => 'required',
            'email'                   => 'required|email|unique:users,email',
            'password'                => 'required|confirmed|min:6',
            'phone'                   => 'required|unique:users,phone',
            'address'                 => 'required',
            'lat'                     => 'required',
            'lng'                     => 'required',
            'device_id'               => 'required',
            'device_type'             => 'required',
            'lang'                    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $user              = new User;
            $user->name        = $inputs['name'];
            $user->email       = $inputs['email'];
            $user->password    = bcrypt($inputs['password']);
            $user->phone       = $inputs['phone'];
            $user->address     = $inputs['address'];
            $user->lat         = $inputs['lat'];
            $user->lng         = $inputs['lng'];
            $user->device_id   = $inputs['device_id'];
            $user->device_type = $inputs['device_type'];

            if ($user->save()) {

                $safeName = time() . rand();

                $shop = [
                    'name'                => $inputs['name'],
                    'desc'                => $inputs['desc'],
                    'commercial_register' => $inputs['commercial_register'],
                ];

                if (save_img($request->logo, $safeName, 'uploads/logos')) {
                    $shop['logo'] = $safeName . '.png';
                }

                if (save_img($request->commercial_register_img, $safeName, 'uploads/commercial')) {
                    $shop['commercial_register_img'] = $safeName . '.png';
                }

                $user->shop()->create($shop);
                $user->settings()->create(['lang' => $inputs['lang']]);

                $inputs['logo']                    = URL::to('public/uploads/logos/' . $user->shop->logo);
                $inputs['commercial_register_img'] = URL::to('public/uploads/commercial/' . $user->shop->commercial_register_img);

                // foreach ($request->products as $key => $value) {
                //     $user->shop()->products()->create(['subCategory_id' => $value]);
                // }

                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.signing_successfully'), 'data' => $inputs]);
            }
        }
    }

    /**
     * Update company function
     * @return void
     * @author
     **/
    public function UpdateCompany(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'user_id'             => 'required',
            'name'                => 'required',
            'commercial_register' => 'required',
            'address'             => 'required',
            'lat'                 => 'required',
            'lng'                 => 'required',
            'email'               => 'required|email|unique:users,email,' . $request->user_id,
            'phone'               => 'required|unique:users,phone,' . $request->user_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $user = User::whereId($request->user_id)->with('shop')->first();
            if ($user) {
                $user->name    = $inputs['name'];
                $user->email   = $inputs['email'];
                $user->phone   = $inputs['phone'];
                $user->address = $inputs['address'];
                $user->lat     = $inputs['lat'];
                $user->lng     = $inputs['lng'];

                if ($user->save()) {
                    $safeName = time() . rand();

                    $shop = [
                        'name'                => $inputs['name'],
                        'desc'                => $inputs['desc'],
                        'commercial_register' => $inputs['commercial_register'],
                    ];

                    // Upload logo Image if is there any update.
                    if ($request->logo) {
                        if (save_img($request->logo, $safeName, 'uploads/logos')) {
                            $shop['logo'] = $safeName . '.png';
                            // Delete the old image
                            $path = public_path('uploads/logos/' . $user->shop->logo);
                            if (file_exists($path)) {
                                unlink($path);
                            }
                        }
                    }

                    // Upload commercial number Image if is there any update.
                    // if ($request->commercial_register_img) {
                    //     if (save_img($request->commercial_register_img, $safeName, 'uploads/commercial')) {
                    //         $shop['commercial_register_img'] = $safeName . '.png';
                    //         // Delete the old image
                    //         $path = public_path('uploads/commercial/' . $user->shop->commercial_register_img);
                    //         if (file_exists($path)) {
                    //             unlink($path);
                    //         }
                    //     }
                    // }

                    $user->shop()->update($shop);

                    $inputs['logo'] = URL::to('public/uploads/logos/' . $user->shop->logo);
                    // $inputs['commercial_register_img'] = URL::to('public/uploads/commercial/' . $user->shop->commercial_register_img);

                    // Update notifications

                    if ($request->maintenanceIds != null) {
                        ShopMaint::where('shop_id', $user->shop->id)->delete();
                        foreach ($request->maintenanceIds as $key => $value) {
                            ShopMaint::firstOrCreate(['shop_id' => $user->shop->id, 'category_id' => $value]);
                        }
                    }

                    if ($request->productsIds != null) {
                        ShopCat::where('shop_id', $user->shop->id)->delete();
                        foreach ($request->productsIds as $key => $value) {
                            ShopCat::firstOrCreate(['shop_id' => $user->shop->id, 'subCategory_id' => $value]);
                        }
                    }

                    return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.updated_successfully'), 'data' => $user], 201);
                }
            }
        }
    }

    /**
     * Profile company function
     *
     * @return void
     * @author
     **/
    public function ProfileCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $user = User::whereId($request->user_id)->with('shop')->first();
            if ($user) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.signing_successfully'), 'data' => $inputs]);
            } else {
                return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => __('messages.wrong_id'), 'data' => []]);
            }
        }
    }

    /**
     * Profile company function
     *
     * @return void
     * @author
     **/
    public function passwordChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password'     => 'required|confirmed|min:6',
        ]);

        $inputs = $request->all();

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            if (password_verify($inputs['old_password'], $request->user()->password)) {
                $user = User::whereId($request->user()->id)->first();

                $user->password = bcrypt($inputs['password']);

                if ($user->save()) {
                    return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.password_was_successfully_changed'), 'data' => null], 201);
                }
            } else {
                return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => __('messages.wrong_password'), 'data' => []], 406);
            }
        }
    }
}
