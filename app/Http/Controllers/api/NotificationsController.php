<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * get all the notifications
     *
     * @return json
     * @author
     **/
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications;

        foreach ($notifications as $key => $notification) {
            $lang_key                     = $notification->data['lang_key'];
            $title                        = (isset($notification->data['title'])) ? $notification->data['title'] : '';
            $notifications[$key]['title'] = __('messages.' . $lang_key) . ' ' . $title;
        }

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $notifications]);
    }

    /**
     * marke all the notifications as readed
     *
     * @return json
     * @author
     **/
    public function read(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => "", 'data' => '']);
    }

    /**
     * remove notification
     *
     * @return json
     * @author
     **/
    public function remove(Request $request)
    {
        $notification = Notification::find($request->id);
        if ($notification->delete()) {
            return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.deleted_successfully'), 'data' => '']);
        }

    }

    /**
     * remove all the notifications
     *
     * @return json
     * @author
     **/
    public function removeAll(Request $request)
    {
        $user = $request->user();
        $user->notifications()->delete();
        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => __('messages.deleted_successfully'), 'data' => '']);
    }
}
