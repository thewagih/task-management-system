<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponserTrait
{
    protected function successResponse($data, $message = null, int $httpResponseCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? __('Success'),
            'data' => $data,
        ];

        // Check if the data is paginated
        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            // Extract the original paginator
            $paginator = $data->resource;

            if ($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
                $response['totalCount'] = $paginator->total();
                $response['pageSize'] = $paginator->perPage();
                $response['pageIndex'] = $paginator->currentPage() - 1;
            }
        }

        return response()->json($response, $httpResponseCode);
    }

    protected function errorResponse($data = null, string $message = null, ?array $errors = [], int $httpResponseCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'errors' => $errors ?? null,
        ], $httpResponseCode);
    }
}
