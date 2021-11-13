<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            return new JsonResponse([
                'error' => false,
                'message'=> __('messages.welcome'),
                'data' => [
                    'title' => 'Page d\'index.html',
                    'version' => '1.0-'.app()->getLocale(),
                    'platform.' => 'developer back-end',
                    'documentation' => "https://".$_SERVER['HTTP_HOST']."/docs",
                    'support' => 'raharinirinapf@gmail.com'
                ]
            ], 200);
        } catch (\Exception $exception){
            return new JsonResponse([
                'title' => 'error 404: page not found.',
                'message' => $exception->getMessage()
            ], 404);
        }

    }
}
