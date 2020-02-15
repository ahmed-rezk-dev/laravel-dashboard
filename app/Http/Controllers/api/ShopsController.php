<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Maint;
use App\MaintAucts;
use App\MaintFinished;
use App\NewPro;
use App\NewProFinished;
use App\Notifications\NewMaintAuctionNotify;
use App\Notifications\NewMaintFinishedNotify;
use App\Notifications\NewProAuctionNotify;
use App\Notifications\NewProFinishedNotify;
use App\ProAucts;
use App\Process;
use App\Shop;
use App\ShopMaint;
use App\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopsController extends Controller
{
    /**
     * Shops categories function
     *
     * @return void
     * @author
     **/
    public function shopsCategories()
    {
    }

    /**
     * show shop function
     *
     * @return Object
     * @author
     **/
    public function show(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'shop_id' => 'required|exists:shops,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {

            $shop = Shop::where('id', $request->shop_id)->with(['ratings.user:id,name', 'user.analysis'])->get();

            return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $shop]);

        }
    }

    /**
     * new product function
     *
     * @return Object
     * @author
     **/
    public function newProducts(Request $request)
    {
        $shopPro = $request->user()->shop->shopCategory->pluck('subCategory_id');

        $subCategories = SubCategory::whereIn('id', $shopPro)->with('products')->get();

        $products = $subCategories->map(function ($item, $key) {
            return $item->products->pluck('id');
        });
        $productsIds = call_user_func_array('array_merge', $products->toArray());
        // return response()->json();

        $orders = NewPro::whereIn('product_id', $productsIds)
            ->with(['product.photos', 'product.fields', 'user:id,name'])
            ->doesntHave('auctions')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $orders]);
    }

    /**
     * new maintenance function
     *
     * @return Object
     * @author
     **/
    public function newMaintenance(Request $request)
    {

        $shop = $request->user()->shop;

        $maint = ShopMaint::where('shop_id', $shop->id)->with(['category'])->get();

        $category = $maint->pluck('category')->pluck('id');

        $orders = Maint::whereIn('subCategory_id', $category->all())->with('subCategory')->whereDoesntHave('auctions', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $orders]);
    }

    /**
     * maintenances auction function
     *
     * @return Object
     * @author
     **/
    public function maintenancesAuction(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'maint_id'    => 'required|unique:processing,maint_id||unique:maintenances_finished,maint_id',
            'deliveryDay' => 'required',
            'price'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {

            $shop_id             = $request->user()->shop->id;
            $shopName            = $request->user()->name;
            $insert              = new MaintAucts;
            $insert->shop_id     = $shop_id;
            $insert->maint_id    = $inputs['maint_id'];
            $insert->note        = $request->note;
            $insert->deliveryDay = $inputs['deliveryDay'];
            $insert->price       = $inputs['price'];

            $maint = Maint::where('id', $request->maint_id)->with('user')->first();

            $device_id   = $maint->user->device_id;
            $device_type = $maint->user->device_type;
            $msg         = __("messages.new_auction_offer") . ' - ' . $shopName;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            if ($insert->save()) {
                $maint->user->notify(new NewMaintAuctionNotify(['title' => $shopName, 'id' => $insert->id]));
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.auction_added_successfully'), 'data' => $insert]);
            }
        }
    }

    /**
     * maintenances auction function
     *
     * @return Object
     * @author
     **/
    public function productsAuction(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'new_id'         => 'required|unique:processing,new_id||unique:new_products_finished,new_id',
            'note'           => 'string',
            'payment_method' => 'required',
            'deliveryDay'    => 'required',
            'price'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {
            $shop_id                = $request->user()->shop->id;
            $shopName               = $request->user()->name;
            $insert                 = new ProAucts;
            $insert->shop_id        = $shop_id;
            $insert->new_id         = $inputs['new_id'];
            $insert->note           = $request->note;
            $insert->payment_method = $inputs['payment_method'];
            $insert->deliveryDay    = $inputs['deliveryDay'];
            $insert->price          = $inputs['price'];

            $new = NewPro::where('id', $request->new_id)->with('user')->first();

            $device_id   = $new->user->device_id;
            $device_type = $new->user->device_type;
            $msg         = __("messages.new_auction_offer") . ' - ' . $shopName;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            if ($insert->save()) {
                $new->user->notify(new NewProAuctionNotify(['title' => $shopName, 'id' => $insert->id]));
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.auction_added_successfully'), 'data' => $insert]);
            }
        }
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
            'new_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 460);
        } else {

            $new = NewPro::where('id', $request->new_id)->with('user', 'product:id,name_ar,name_en')->first();

            $shop     = $request->user();
            $shopName = $shop->shop->name;
            $shop_id  = $shop->shop->id;

            $insert                    = new NewProFinished;
            $insert->shop_id           = $shop_id;
            $insert->new_id            = $inputs['new_id'];
            $insert->user_id           = $new->user_id;
            $insert->shop_confirmation = true;

            $device_id   = $new->user->device_id;
            $device_type = $new->user->device_type;
            $msg         = __("messages.new_pro_finished_notify") . ' - ' . $shopName;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            if ($insert->save()) {
                $notify_obj = ['id' => $insert->id, 'title' => $new->product->name];
                $new->user->notify(new NewProFinishedNotify((Object) $notify_obj));
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.new_pro_finished_notify'), 'data' => $insert], 201);
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
            'maint_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {

            $user = Maint::where('id', $request->maint_id)->with('user', 'subCategory:id,name_ar,name_en')->first();

            $shop     = $request->user();
            $shopName = $shop->shop->name;
            $shop_id  = $shop->shop->id;

            $insert                    = new MaintFinished;
            $insert->shop_id           = $shop_id;
            $insert->maint_id          = $inputs['maint_id'];
            $insert->user_id           = $user->user_id;
            $insert->shop_confirmation = true;

            $device_id   = $user->user->device_id;
            $device_type = $user->user->device_type;
            $msg         = __("messages.maint_pro_finished_notify") . ' - ' . $shopName;

            if ($device_id != null) {
                sendFcm($device_id, $device_type, $msg);
            }

            $notify_obj = ['id' => $insert->id, 'title' => $user->subCategory->name];
            $user->user->notify(new NewMaintFinishedNotify((Object) $notify_obj));

            if ($insert->save()) {
                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.hired_successfully'), 'data' => $insert], 201);
            }
        }
    }

    /**
     * maintenance finished function
     *
     * @return Object
     * @author
     **/
    public function underwayOrders(Request $request)
    {
        $shop    = $request->user();
        $shop_id = $shop->shop->id;

        $orders = Process::where('shop_id', $shop_id)
            ->with(['user', 'newProduct.product', 'maintenance.subCategory', 'maintenance.finished', 'newProduct.finished'])
            ->whereDoesntHave('newProduct.finished', function ($query) {
                $query->where('user_confirmation', '=', 1)->where('shop_confirmation', '=', 1);
            })
            ->orWhereDoesntHave('maintenance.finished', function ($query) {
                $query->where('user_confirmation', '=', 1)->where('shop_confirmation', '=', 1);
            })
            ->get();

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $orders]);
    }
}
