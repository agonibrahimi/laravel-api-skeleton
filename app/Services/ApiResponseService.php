<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    /**
     * Success response format.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $status
     * @return JsonResponse
     */
    public static function success($data = null, $message = 'Success', $status = 200): JsonResponse
    {
        $pagination = null;
        if ($data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator || $data instanceof \Illuminate\Contracts\Pagination\Paginator) {
            $pagination = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ];
            $data = $data->items();
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination,
        ], $status);
    }

    /**
     * Error response format.
     *
     * @param  string  $message
     * @param  int  $status
     * @param  mixed  $data
     * @return JsonResponse
     */
    public static function error($message = 'Error', $status = 400, $errors = []): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    /**
     * Unauthenticated response format.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    public static function unauthenticated($message = 'Unauthorized'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], 401);
    }
}
