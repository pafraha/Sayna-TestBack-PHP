<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use MohamedGaber\SanctumRefreshToken\Models\PersonalAccessToken;

/**
 * API for User management
 */
class AuthController extends Controller
{
    /**
     * Get User information
     *
     * This endpoint lets to get the authenticated user information
     *
     * @authenticated
     * @param $token
     * @return JsonResponse
     */
    public function user($token): JsonResponse
    {
        if (count(str_split($token)) !== 40){
            return new JsonResponse([
                'error' => true,
                'message' => 'Le token envoyé n\'est pas conforme.'
            ], 401);
        }

        $_token = PersonalAccessToken::findToken($token);
        if ($_token && $_token->expired_at < Carbon::now()){ // token revoke
            return new JsonResponse([
                'error' => true,
                'message' => 'Votre token n\'ai plus valide, veuillez le réinitialiser.'
            ], 401);
        }

        if ($_token && $_token->tokenable()){
            return new JsonResponse([
                'error' => false,
                'user' => $_token->tokenable()->first()
            ], 200);
        }

        return new JsonResponse([
            'error' => true,
            'message' => 'Votre token n\'ai pas valide, veuillez le réinitialiser.'
        ], 401);
    }


    /**
     * Users list
     *
     * This endpoint lets you getting all register Users.
     *
     * @authenticated
     * @param $token
     * @return JsonResponse
     */
    public function users($token): JsonResponse
    {
        if (count(str_split($token)) !== 40){
            return new JsonResponse([
                'error' => true,
                'message' => 'Le token envoyé n\'est pas conforme.'
            ], 401);
        }

        $_token = PersonalAccessToken::findToken($token);
        if ($_token && $_token->expired_at < Carbon::now()){ // token revoke
            return new JsonResponse([
                'error' => true,
                'message' => 'Votre token n\'ai plus valide, veuillez le réinitialiser.'
            ], 401);
        }

        if ($_token && $_token->tokenable()){
            return new JsonResponse([
                'error' => false,
                'users' => User::all()
            ], 200);
        }

        return new JsonResponse([
            'error' => true,
            'message' => 'Votre token n\'ai pas valide, veuillez le réinitialiser.'
        ], 401);
    }


    /**
     * Change user information
     *
     * This endpoint lets you updating some information such as password and many else.
     *
     * @bodyParam firstname string  only required if you need to change it.
     * @bodyParam lastname string  only required if you need to change it.
     * @bodyParam date_naissance date only required if you need to change it, YYYY-MM-DD.
     * @bodyParam password string only required if you need to change the password of the user. And must be 6 characters in min.
     * @bodyParam email string only required if you need to change the email of the user. exemple@mail.com
     * @bodyParam sexe string male or female only.
     * @authenticated
     * @param Request $request
     * @param $token
     * @return JsonResponse
     */
    public function change(Request $request, $token): JsonResponse
    {
        if (!$request->all()){
            return new JsonResponse([
                'error' => true,
                'message' => 'Aucun données n\'a été envoyée.'
            ], 401);
        }

        if (count(str_split($token)) !== 40){
            return new JsonResponse([
                'error' => true,
                'message' => 'Le token envoyé n\'est pas conforme.'
            ], 401);
        }

        $_token = PersonalAccessToken::findToken($token);
        if (is_null($_token) || $_token->expired_at < Carbon::now()){ // token revoke
            return new JsonResponse([
                'error' => true,
                'message' => 'Votre token n\'ai plus valide, veuillez le réinitialiser.'
            ], 401);
        }

        /** @var User $user */
        $user = $_token->tokenable()->first();
        $user->update($request->all());
        if ($request->has('password')){
            $user->update([
                'password' => Hash::make($request->get('password'))
            ]);
        }

        return new JsonResponse([
            'error' => true,
            'message' => 'L\'utilisateur a été modifié avec succès.'
        ], 200);
    }

    /**
     * Log out
     *
     * This endpoint lets you logging out a user.
     *
     * @authenticated
     *
     * @param string $token
     * @return JsonResponse
     */
    public function destroy(string $token): JsonResponse
    {
        $_token = PersonalAccessToken::findToken($token);
        if ($user = $_token->tokenable()->first()){
            $user->tokens()->delete();
        }

        return new JsonResponse([
            'error' => false,
            'message' => 'L\'utilisateur a été déconnecté avec succès.'
        ], 200);
    }
}
