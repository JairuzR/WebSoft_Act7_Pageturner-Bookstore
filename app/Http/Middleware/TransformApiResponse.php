<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransformApiResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            
            // Apply transformations based on route/controller
            $transformedData = $this->transformData($data, $request);
            
            $response->setData($transformedData);
        }

        return $response;
    }

    protected function transformData(array $data, Request $request): array
    {
        // Add metadata
        if (!isset($data['meta'])) {
            $data['meta'] = [
                'version' => '1.0',
                'timestamp' => now()->toIso8601String(),
                'environment' => app()->environment(),
            ];
        }

        // Transform book data to hide sensitive fields for non-admin
        if (str_contains($request->path(), 'books') && isset($data['data'])) {
            $data['data'] = $this->transformBookData($data['data'], $request->user());
        }

        // Convert snake_case to camelCase if requested
        if ($request->has('camel_case')) {
            $data = $this->arrayKeysToCamelCase($data);
        }

        return $data;
    }

    protected function transformBookData($bookData, $user = null)
    {
        // If not admin, remove sensitive fields
        if (!$user || !$user->isAdmin()) {
            if (is_array($bookData) && !isset($bookData[0])) {
                // Single resource
                unset($bookData['created_at']);
                unset($bookData['updated_at']);
            } elseif (is_array($bookData)) {
                // Collection
                foreach ($bookData as &$item) {
                    unset($item['created_at']);
                    unset($item['updated_at']);
                }
            }
        }
        return $bookData;
    }

    protected function arrayKeysToCamelCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $camelKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if (is_array($value)) {
                $result[$camelKey] = $this->arrayKeysToCamelCase($value);
            } else {
                $result[$camelKey] = $value;
            }
        }
        return $result;
    }
}