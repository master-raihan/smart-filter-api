<?php
namespace App\Services;

use App\Contracts\Repositories\UserRepository;
use App\Contracts\Services\AuthContract;
use App\Helpers\UtilityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthService implements AuthContract
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($request): array
    {
        try{
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_BAD_REQUEST,
                    $validator->errors()
                );
            }

            $credentials = $request->only(['email', 'password']);
            if (! $token = Auth::attempt($credentials)) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_UNAUTHORIZED,
                    [
                        'email' => ['Unknown Email or Password']
                    ],
                );
            }

            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK,
                'Successfully Authenticated!',
                $this->respondWithToken($token)
            );
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(
                ResponseAlias::HTTP_BAD_REQUEST
            );
        }
    }

    public function me(): array
    {
        try{
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK,
                'Auth User Fetched Successfully',
                Auth::user()
            );
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(
                ResponseAlias::HTTP_BAD_REQUEST
            );
        }
    }

    public function refresh(): array
    {
        try{
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK,
                'Refreshed Successfully!',
                $this->respondWithToken(Auth::refresh())
            );
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(
                ResponseAlias::HTTP_BAD_REQUEST
            );
        }
    }

    public function logout(): array
    {
        try{
            Auth::logout();
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK,
                'User Logged Out Successfully'
            );
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(
                ResponseAlias::HTTP_BAD_REQUEST
            );
        }
    }

    public function register($request): array
    {
        try{
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_BAD_REQUEST,
                    $validator->errors()
                );
            }
            $user = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status ? $request->status : 1
            ];

            $userData = $this->userRepository->createUser($user);

            if($userData){
                $credentials = $request->only(['email', 'password']);

                if (! $token = Auth::attempt($credentials)) {
                    return UtilityHelper::RETURN_ERROR_FORMAT(
                        ResponseAlias::HTTP_UNAUTHORIZED,
                        [
                            'email' => ['Unknown Email or Password']
                        ],
                    );
                }

                return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK,
                    'Successfully Authenticated!',
                    $this->respondWithToken($token)
                );
            }

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => Auth::user(),
            'expires_in' => Auth::factory()->getTTL() * 60 * 24
        ];
    }
}
