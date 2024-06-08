<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    //
    function getAll()
    {


        $user = User::find(1);

        $notifications = [];
        foreach ($user->notifications as $notification) {
                $notifications[] = $this->notificationData($notification);
                $notification->markAsRead();
        }
        return $this->successResponse("Notifications", $notifications, 200);
    }

    function send(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string',
                'message' => 'required|string',
                'realtime' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse('Please check the data sent!', $validator->errors(), 400);
        }

        $user = User::find($request->input('user_id'));
        $this->sendNotification($user,$request->input('message'), $request->input('title'), $request->input('realtime'));
        return $this->successResponse('Notification successfully sented');
    }
    function notificationData($notification)
    {
        return [
            "id" => $notification->id,
            "data" => $notification->data,
            "read" => $notification->read_at == null ? false : true,
            "dateTime" => $notification->created_at
        ];
    }
}
