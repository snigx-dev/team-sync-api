<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

abstract class V1BaseController extends Controller
{
    protected function apiResponse($data = null, $message = null, $code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            return $data
                ->additional($response)
                ->response()
                ->setStatusCode($code);
        }

        // Standard non-paginated data
        $response['data'] = $data;
        return response()->json($response, $code);
    }
}
