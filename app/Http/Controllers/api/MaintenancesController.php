<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Maint;
use App\Notifications\NewMaintNotify;
use App\ShopMaint;
use App\SubCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenancesController extends Controller
{
    /**
     * product New function
     *
     * @return josn object
     * @author
     **/
    public function maintenancesNew(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), [
            'subCategory_id' => 'required',
            'image'          => 'required',
            'address'        => 'required',
            'lat'            => 'required',
            'lng'            => 'required',
            'deliveryDay'    => 'required',
            'deliveryTime'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []], 406);
        } else {

            $user_id                = $request->user()->id;
            $insert                 = new Maint;
            $insert->user_id        = $user_id;
            $insert->subCategory_id = $inputs['subCategory_id'];
            $insert->type           = $inputs['type'];
            $insert->address        = $inputs['address'];
            $insert->lat            = $inputs['lat'];
            $insert->lng            = $inputs['lng'];
            $insert->deliveryDay    = $inputs['deliveryDay'];
            $insert->deliveryTime   = $inputs['deliveryTime'];

            $safeName = time() . rand();
            if (save_img($request->image, $safeName, 'uploads/maintenances')) {
                $insert->image = $safeName . '.png';
            }

            $subCat = SubCategory::where('id', $inputs['subCategory_id'])->with('category')->first();

            $shops = ShopMaint::where('category_id', $subCat->category->id)->with('shop:id,user_id')->get();

            if ($insert->save()) {
                // Notification;
                foreach ($shops as $item) {
                    $device_id   = $item->shop->user->device_id;
                    $device_type = $item->shop->user->device_type;
                    $msg         = __("messages.new_maint_notify") . ' - ' . $subCat->category->name;

                    if ($device_id != null) {
                        sendFcm($device_id, $device_type, $msg);
                    }
                    $item->shop->user->notify(new NewMaintNotify(['title' => $subCat->category->name, 'id' => $insert->id]));
                }

                return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.auction_created_successfully'), 'data' => $insert]);
            }
        }
    }
}
