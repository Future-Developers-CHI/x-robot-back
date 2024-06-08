<?php

namespace App\Http\Traits;

use App\Models\Thing;
use App\Notifications\ThingFound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponseTrait
{
    /**
     * Send any success response
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(string $message = '', mixed $data = null, int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Send any error response
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse(string $message = '', mixed $data = null, int $code = ResponseAlias::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Send any validation
     *
     * @param $request
     * @param $validationRequest
     * @return \Illuminate\Validation\Validator
     */
    public function validationTest($request, $validationRequest): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), $validationRequest->rules(), $validationRequest->messages());
    }

    /**
     * Send any validation error response
     *
     * @param $validator
     * @return JsonResponse
     */
    public function validationErrorResponse($validator): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Please check the data sent!',
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function sendNotification($user, $message,$title, $realtime)
    {
        $message = [
            "title" => $title,
            "body" => $message
        ];
        $user->notify(new ThingFound($message));

        if ($realtime && $user->device_id != null) {
            $this->sendNotificationRealTime($user->device_id, $message);
        }
    }
    private function sendNotificationRealTime($device_id, $message)
    {
        $urlBase = 'https://fcm.googleapis.com/fcm/send';
        $SERVER_API_KEY = '';

        $data = [
            "registration_ids" => [$device_id],
            "notification" => [
                "title" => $message['title'],
                "body" => $message['body'],
                "sound" => "default"
            ],
        ];
        $data = json_encode($data);
        $headers = [
            'Authorization:key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $urlBase);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        // return Http::withHeaders($headers)->asForm()->post($urlBase, $data);
    }
}
