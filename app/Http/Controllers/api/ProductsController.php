<?php

namespace App\Http\Controllers\api;

use App\Basket;
use App\Http\Controllers\Controller;
use App\NewPro;
use App\Notifications\NewHireNotify;
use App\Notifications\NewProductNotify;
use App\Process;
use App\Product;
use App\Shop;
use App\ShopCat;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function show(Request $request)
    {
        $getProduct = Product::where('id', $request->product_id)
            ->has('photos')
            ->has('fields')
            ->with(['photos', 'fields'])
            ->first();

        $smiler = Product::inRandomOrder()
            ->where('name_ar', 'LIKE', '%' . $getProduct->name . '%')
            ->orWhere('name_en', 'LIKE', '%' . $getProduct->name . '%')
            ->where('id', '!=', $getProduct->id)
            ->with(['photos'])
            ->take(3)->get();

        $getProduct['smilerProducts'] = $smiler;

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $getProduct]);
    }

    /**
     * product New function
     *
     * @return josn object
     * @author
     **/
    public function productNew(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'payment_method'       => 'required',
            'airConditioningPipes' => 'required',
            'address'              => 'required',
            'lat'                  => 'required',
            'lng'                  => 'required',
            'deliveryDay'          => 'required',
            'deliveryTime'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {

            $user_id = $request->user()->id;

            $basket = Basket::whereUserId($user_id)->with('product')->get();

            $shopCat = $basket->map(function ($item, $key) {
                return $item->product->subCategory_id;
            });

            $shops = ShopCat::whereIn('subCategory_id', $shopCat)->with('shop.user', "subCategory")->get();
            // return response()->json($shops, 406);

            // Notification;
            foreach ($shops as $item) {
                $device_id   = $item->shop->user->device_id;
                $device_type = $item->shop->user->device_type;
                $msg         = __("messages.new_product_notify") . ' - ' . $item->subCategory->name;

                if ($device_id != null) {
                    sendFcm($device_id, $device_type, $msg);
                }
                $item->shop->user->notify(new NewProductNotify($item->subCategory->name));
            }

            foreach ($basket as $key => $value) {
                $inputs['product_id'] = $value->product_id;
                $inputs['number']     = $value->number;
                $inputs['user_id']    = $user_id;
                NewPro::create($inputs);
            }

            if (Basket::truncate()) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.auction_created_successfully'), 'data' => $inputs], 201);
            }
        }
    }

    /**
     * basket List function
     *
     * @return josn object
     * @author
     **/
    public function basketList(Request $request)
    {
        $user_id = $request->user()->id;

        $basket = Basket::whereUserId($user_id)
            ->with(['product:id,name_ar,name_en', 'product.photos'])
            ->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $basket]);
    }

    /**
     * basket Add function
     *
     * @return josn object
     * @author
     **/
    public function basketAdd(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {

            $user_id            = $request->user()->id;
            $insert             = new Basket;
            $insert->user_id    = $user_id;
            $insert->product_id = $inputs['product_id'];
            $insert->number     = 1;

            if ($insert->save()) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.added_basket_successfully'), 'data' => $insert]);
            }
        }
    }

    /**
     * basket Add function
     *
     * @return josn object
     * @author
     **/
    public function basketRemove(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:basket,product_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {

            $destory = Basket::where('product_id', $request->product_id)->where('user_id', $request->user()->id)->delete();

            if ($destory) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.deleted_successfully'), 'data' => []]);
            }
        }
    }
    /**
     * basket Add function
     *
     * @return josn object
     * @author
     **/
    public function updateQuantity(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:basket,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {

            $update = Basket::where('id', $request->id)->where('user_id', $request->user()->id)->first();

            $update->number = $request->number;

            if ($update->save()) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.saved'), 'data' => []]);
            }
        }
    }

    /**
     * basket List function
     *
     * @return josn object
     * @author
     **/
    public function fetchBasketList(Request $request)
    {
        $list    = collect($request->list);
        $plucked = $list->pluck('product_id');

        $products = Product::whereIn('id', $plucked->all())
            ->with('photos')
            ->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $products]);
    }

    /**
     * Hiring funcation
     *
     * @return void
     * @author
     **/
    public function hire(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'shop_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {

            $user_id          = $request->user()->id;
            $username         = $request->user()->name;
            $insert           = new Process;
            $insert->user_id  = $user_id;
            $insert->shop_id  = $request->shop_id;
            $insert->new_id   = $request->new_id;
            $insert->maint_id = $request->maint_id;

            $shop = Shop::where('id', $request->shop_id)->with('user')->first();

            $device_id   = $shop->user->device_id;
            $device_type = $shop->user->device_type;
            $msg         = __("messages.hired_notifiy") . ' - ' . $username;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            if ($insert->save()) {
                $shop->user->notify(new NewHireNotify(['title' => $username, 'new_id' => $request->new_id, 'maint_id' => $request->maint_id]));
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.hired_successfully'), 'data' => $insert]);
            }
        }
    }
}
