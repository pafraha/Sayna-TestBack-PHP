<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Login API
 */
class LoginController extends Controller
{
    use ThrottlesLogins;
    protected $maxAttempts = 4; // Bloquer en 3 tentatives d'erreur authentication
    protected $decayMinutes = 60; // Bloquer pendant 1h ou 60 minutes

    /**
     * Log in a user
     *
     * This endpoint lets you log in a user with email and password
     *
     * @bodyParam email string required The email of the user. exemple@mail.com
     * @bodyParam password string required The password of the user. And must be 6 characters in min.
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Trop de tentative sur l\'email: '.$request->get('email').' - Veuillez patienter 1h'
            ], 409);
        }

        try {
            // Données manquant
            if (empty($request->get('email')) || empty($request->get('password'))){
                $this->incrementLoginAttempts($request);
                return new JsonResponse([
                    'error' => true,
                    'message' => 'L\'email / password est manquant.'
                ], 401);
            }

            $user = User::where('email', $request->get('email'))->first();
            if(!$user || !Hash::check($request->get('password'), $user->password)) {
                $this->incrementLoginAttempts($request);
                return new JsonResponse([
                    'error' => true,
                    'message' => 'Votre email ou password est erroné.'
                ], 401);
            }

            return new JsonResponse([
                'error' => false,
                'message' => 'L\'utilisateur a été authentifié avec succès.',
                'tokens' => [
                    'token' => $user->createAuthToken('sayna.api')->plainTextToken,
                    'refresh-token' => $user->createRefreshToken('sayna.api')->plainTextToken,
                    'createdAt' => Carbon::now()->toDateString()
                ]
            ], 200);
        } catch (Exception $exception){
            return new JsonResponse([
                'error' => true,
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }
}
