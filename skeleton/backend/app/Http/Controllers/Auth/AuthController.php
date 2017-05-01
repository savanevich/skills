<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use App\Http\Requests\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Tymon\JWTAuth\JWTAuth;
use JWTAuthException;
use Hash;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    private $user;
    private $jwtauth;

    /**
     * Create a new authentication controller instance.
     *
     * @param User $user
     * @param JWTAuth $jwtauth
     */
    public function __construct(User $user, JWTAuth $jwtauth)
    {
        $this->user = $user;
        $this->jwtauth = $jwtauth;
    }

	/**
     * Add new user to db
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $newUser = User::createUser($request);

        if (!$newUser) {
            $error = 'Failed to create new user';

            return $this->toJsonResponse(500, false, $error);
        }

        return $this->toJsonResponse(200, $newUser, false);
    }

    /**
     * Create jwt if this user entered the true data
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)
                     ->first();
        if(!$user) {
            $error = 'User with this email doesn\'t exist.';

            return $this->toJsonResponse(422, false, $error);
        }
        if (!Hash::check($request->password, $user->password)) {
            $error = 'You entered incorrect password.';

            return $this->toJsonResponse(422, false, $error);
        }

        try {
            $token = $this->jwtauth->fromUser($user);
        } catch (JWTAuthException $e) {
            $error = 'Failed to create token';

            return $this->toJsonResponse(500, false, $error);
        }

        return response()->json(compact('token'), 200);
    }

	/**
     * Get user's information if token is valid and we have found the user via the sub claim
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = $this->jwtauth->parseToken()->authenticate()) {
                $error = 'User wasn\'t found';

                return $this->toJsonResponse(404, false, $error);
            }
        } catch (JWTAuthException $e) {
            $error = 'Token invalid';

            return $this->toJsonResponse(500, false, $error);
        }
        $userData = User::getUserFollowers($user['id']);
        $user['followers'] = $userData->followers;
        $user['followers_count'] = $userData->followers_count;
        $user['following_count'] = $userData->following_count;

        $data = compact('user');

        return $this->toJsonResponse(200, $data, false);
    }
}
