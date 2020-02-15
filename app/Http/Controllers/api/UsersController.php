<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Maint;
use App\MaintFinished;
use App\NewPro;
use App\NewProFinished;
use App\Notifications\NewRatingNotify;
use App\Notifications\UserConfirmtFinishedNotify;
use App\ProductRate;
use App\Shop;
use App\ShopRate;
use App\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * profile function
     *
     * @return Object
     * @author
     **/
    public function userProfile(Request $request)
    {

        $user                       = $request->user();
        $user['basketCount']        = count($user->basket);
        $user['notificationsCount'] = count($user->notifications);
        $user['lang']               = $user->settings->lang;
        if ($user->shop == null) {
            $user['userType'] = 'user';
        } else {
            $user['userType']             = 'shop';
            $user['shop']                 = $user->shop;
            $user['shop']['shopCategory'] = $user->shop->shopCategory->pluck('subCategory_id');
            $user['shop']['shopMaint']    = $user->shop->shopMaint->pluck('category_id');
        }

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $user]);

    }

    /**
     * my orders function
     *
     * @return Object
     * @author
     **/
    public function myOrders(Request $request)
    {
        $user = $request->user();

        $orders = NewPro::where('user_id', $user->id)
            ->with(['product.photos', 'user:id,name', 'auctions.shop', 'processe.shop', 'finished.shop'])
            ->whereDoesntHave('finished', function ($query) {
                $query->where('user_confirmation', '=', 1)->where('shop_confirmation', '=', 1);
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $orders]);
    }

    /**
     * my orders function
     *
     * @return Object
     * @author
     **/
    public function myOrdersMaint(Request $request)
    {
        $user = $request->user();

        $orders = Maint::where('user_id', $user->id)
            ->with(['subCategory', 'processe.shop', 'finished.shop'])
            ->with(['auctions' => function ($query) {
                $query->orderBy('id', 'desc')->with('shop');
            }])
            ->whereDoesntHave('finished', function ($query) {
                $query->where('user_confirmation', '=', 1)->where('shop_confirmation', '=', 1);
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $orders]);
    }

    /**
     * new finished function
     *
     * @return Object
     * @author
     **/
    public function newFinished(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'finished_id' => 'required|exists:new_products_finished,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $user                      = $request->user();
            $insert                    = NewProFinished::where('id', $inputs['finished_id'])->where('user_id', $user->id)->with(['shop.user', 'new'])->first();
            $insert->user_confirmation = true;

            $device_id   = $insert->shop->user->device_id;
            $device_type = $insert->shop->user->device_type;
            $msg         = __("messages.user_confirmation_finished_notify") . ' - ' . $user->name;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            if ($insert->save()) {
                $notify_obj = ['id' => $insert->id, 'title' => $user->name];
                $insert->shop->user->notify(new UserConfirmtFinishedNotify((Object) $notify_obj));
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.successfully_confirmed'), 'data' => $insert], 201);
            }
        }
    }

    /**
     * maintenance finished function
     *
     * @return Object
     * @author
     **/
    public function maintenanceFinished(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'finished_id' => 'required|exists:maintenances_finished,id',
        ]);
        $request->user();
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $user                      = $request->user();
            $insert                    = MaintFinished::where('id', $inputs['finished_id'])->where('user_id', $user->id)->with('shop.user')->first();
            $insert->user_confirmation = true;

            $device_id   = $insert->shop->user->device_id;
            $device_type = $insert->shop->user->device_type;
            $msg         = __("messages.user_confirmation_finished_notify") . ' - ' . $user->name;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            if ($insert->save()) {
                $notify_obj = ['id' => $insert->id, 'title' => $user->name];
                $insert->shop->user->notify(new UserConfirmtFinishedNotify((Object) $notify_obj));
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.hired_successfully'), 'data' => $insert]);
            }
        }
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function ratings(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'shop_id'            => 'required',
            'product_id'         => 'required',
            'dealingPerformance' => 'required',
            'deliveryQuality'    => 'required',
            'deliveryQuality'    => 'required',
            'workExperiencek'    => 'required',
            'dealingAgain'       => 'required',
            'productQuality'     => 'required',
            'productPerformance' => 'required',
            'buyRecommendation'  => 'required',
            'comment'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $user = $request->user();

            // Shop rating
            $insert                     = new ShopRate;
            $insert->user_id            = $user->id;
            $insert->shop_id            = $request->shop_id;
            $insert->dealingPerformance = $request->dealingPerformance;
            $insert->deliveryQuality    = $request->deliveryQuality;
            $insert->workExperiencek    = $request->workExperiencek;
            $insert->dealingAgain       = $request->dealingAgain;
            $insert->comment            = $request->comment;

            $shop = Shop::where('id', $inputs['shop_id'])->with('user')->first();

            if ($insert->save()) {
                // FCM
                $device_id   = $shop->user->device_id;
                $device_type = $shop->user->device_type;
                $msg         = __("messages.new_rating_notify") . ' - ' . $user->name;

                if ($device_id != null) {
                    sendFcm($device_id, $device_type, $msg);
                }

                // Dababase
                $notify_obj = ['id' => null, 'title' => $user->name];
                $shop->user->notify(new NewRatingNotify((Object) $notify_obj));

                // Product rating
                $insert                 = new ProductRate;
                $insert->user_id        = $user->id;
                $insert->product_id     = $request->product_id;
                $insert->quality        = $request->productQuality;
                $insert->performance    = $request->productPerformance;
                $insert->recommendation = $request->buyRecommendation;
                $insert->save();

                // Response
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.thank_you'), 'data' => $insert]);
            }
        }
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function maintRatings(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'shop_id'            => 'required',
            'dealingPerformance' => 'required',
            'deliveryQuality'    => 'required',
            'deliveryQuality'    => 'required',
            'workExperiencek'    => 'required',
            'dealingAgain'       => 'required',
            'comment'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $user = $request->user();

            // Shop rating
            $insert                     = new ShopRate;
            $insert->user_id            = $user->id;
            $insert->shop_id            = $request->shop_id;
            $insert->dealingPerformance = $request->dealingPerformance;
            $insert->deliveryQuality    = $request->deliveryQuality;
            $insert->workExperiencek    = $request->workExperiencek;
            $insert->dealingAgain       = $request->dealingAgain;
            $insert->comment            = $request->comment;

            $shop = Shop::where('id', $inputs['shop_id'])->with('user')->first();

            if ($insert->save()) {
                // FCM
                $device_id   = $shop->user->device_id;
                $device_type = $shop->user->device_type;
                $msg         = __("messages.new_rating_notify") . ' - ' . $user->name;

                if ($device_id != null) {
                    sendFcm($device_id, $device_type, $msg);
                }

                // Dababase
                $notify_obj = ['id' => null, 'title' => $user->name];
                $shop->user->notify(new NewRatingNotify((Object) $notify_obj));

                // Response
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.thank_you'), 'data' => $insert]);
            }
        }
    }

    /**
     * users settings
     *
     * @return josn
     * @author
     **/
    public function settings(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lang'          => 'required',
            'notifications' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {

            $user     = $request->user();
            $settings = UserSettings::where('user_id', $user->id)->first();

            if ($settings) {
                $settings->lang          = $request->lang;
                $settings->notifications = $request->notifications;
                $settings->save();
            } else {
                $create                = new UserSettings;
                $create->user_id       = $user->id;
                $create->lang          = $request->lang;
                $create->notifications = $request->notifications;
                $create->save();
            }
            return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.hired_successfully'), 'data' => '']);
        }
    }
}
