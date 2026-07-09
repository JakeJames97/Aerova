<?php

namespace App\Http\Controllers\Profile;

use App\Exceptions\ProfileDeletionException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DeleteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            DB::transaction(static function () use ($user) {
                $user->trips()->delete();
                $user->tokens()->delete();
                $user->delete();
            });
        } catch (Throwable $exception) {
            Log::error('An exception was thrown when attempting to delete a user', [
                'exception' => $exception,
            ]);

            throw new ProfileDeletionException('Unexpected error when deleting user');
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
