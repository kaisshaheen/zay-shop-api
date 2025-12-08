<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogInRequest;
use App\Http\Requests\SignUpRequest;
use Illuminate\Validation\ValidationException;
use App\Services\User\CreateUserService;
use App\Services\User\FindEmailService;
use Illuminate\Http\Request;
use App\Traits\PasswordsTrait;

class AuthController extends Controller
{

    use PasswordsTrait;
 
    


    public function register(SignUpRequest $request , CreateUserService $createService){
        $fields = $request -> validated();

        $user = $createService->handle($fields);

        $user->sendEmailVerificationNotification(); 

        $token = $user -> createToken($user->name)->plainTextToken;

        return ["user" => $user , "token" => $token];
    }

    public function login(LogInRequest $request , FindEmailService $findService){
        $request -> validated();
        $user = $findService->handle($request->email);
        $this->checkEmailPassword($user , $request);

         if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Your email address is not verified. Please check your inbox for a verification link.'],
            ]);
        }

        $token = $user->createToken($user->name);
        return ["user" => $user , "token" => $token->plainTextToken];
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return [
            "message" => "you are logged out"
        ]; 
    }
}