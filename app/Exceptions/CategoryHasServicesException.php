<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryHasServicesException extends Exception
{
    public function __construct()
    {
        parent::__construct('Category cannot be deleted because it has attached services.');
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], 409);
        }

        return back()->with('error', $this->getMessage());
    }
}
