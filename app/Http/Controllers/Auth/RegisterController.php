<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Register API
 */
class RegisterController extends Controller
{
    /**
     * Create a new User
     *
     * This endpoint  lets you create a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $inputs = $request->all();

        foreach ($inputs as $value){
            $value = trim($value);
            if (empty($value)){
                return new JsonResponse([
                    'error' => true,
                    'message' => 'L\'une ou plusieurs des données obligatoires sont manquantes.'
                ], 401);
            }
        }

        $validator = Validator::make($inputs, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
            'confirm_password' => ['required', 'same:password']
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'error' => true,
                'message' => 'L\'une des données obligatoires ne sont pas conformes.'
            ], 401);
        }

        if (User::where('email', $inputs['email'])->first()) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Votre email n\'ai pas correct.'
            ], 401);
        }

        try {
            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => Hash::make($inputs['password'])]
            ));

            return new JsonResponse([
                'error' => false,
                'message' => 'L\'utilisateur a bien été créé avec succès.',
                'tokens' => [
                    'token' => $user->createAuthToken('sayna.api')->plainTextToken,
                    'refresh-token' => $user->createRefreshToken('sayna.api')->plainTextToken,
                    'createdAt' => Carbon::now()->toDateString()
                ],
            ], 201);
        } catch (ValidationException $exception){
            return new JsonResponse([
                'error' => true,
                'message' => $exception->getMessage()
            ], 409);
        }
    }
}
