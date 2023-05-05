<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Http\Responses\HttpResponse;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponse;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'type' => 'user',
            'id' => $user->id,
            'attributes' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken,
            'relationships' => [
                'profile' => [
                    'data' => [
                        'id' => $user->profile->id,
                        'type' => 'profile',
                    ]
                ]
            ],
            'included' => [
                [
                    'type' => 'profile',
                    'id' => $user->profile->id,
                    'attributes' => [
                        'first_name' => $user->profile->first_name,
                        'last_name' => $user->profile->last_name,
                        'sex' => $user->profile->sex,
                        'age' => $user->profile->age,
                    ]
                ]
            ],
        ]);
    }

    public function register(RegisterUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Profile::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sex' => $request->sex,
            'age' => $request->age,
            'user_id' => $user->id,
        ]);

        return $this->success([
            'type' => 'user',
            'id' => $user->id,
            'attributes' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken,
            'relationships' => [
                'profile' => [
                    'data' => [
                        'id' => $user->profile->id,
                        'type' => 'profile',
                    ]
                ]
            ],
            'included' => [
                [
                    'type' => 'profile',
                    'id' => $user->profile->id,
                    'attributes' => [
                        'first_name' => $user->profile->first_name,
                        'last_name' => $user->profile->last_name,
                        'sex' => $user->profile->sex,
                        'age' => $user->profile->age,
                    ]
                ]
            ],
        ]);
    }

    public function getUser()
    {
        $user = Auth::user();

        return $this->jsonResponse([
            'data' => [
                'type' => 'user',
                'id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'relationships' => [
                    'profile' => [
                        'data' => [
                            'id' => $user->profile->id,
                            'type' => 'profile',
                        ]
                    ]
                ],
                'included' => [
                    [
                        'type' => 'profile',
                        'id' => $user->profile->id,
                        'attributes' => [
                            'first_name' => $user->profile->first_name,
                            'last_name' => $user->profile->last_name,
                            'sex' => $user->profile->sex,
                            'age' => $user->profile->age,
                        ]
                    ]
                ]
            ]
        ], 200);
    }

    public function logout()
    {
        // This is working, but I don't know why Vs code thinking is an error
        Auth::user()->currentAccessToken()->delete();

        return $this->jsonResponse('You has been loged out sucessfully');
    }
}
