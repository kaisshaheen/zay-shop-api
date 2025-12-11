<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Services\User\CreateUserService;
use App\Services\User\FindEmailService;
use Illuminate\Http\Request;
use App\Traits\PasswordsTrait;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

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



    //SignIn with google

  
    public function google(Request $request)
    {
      
    /** @var GoogleProvider $driver */
    $driver = Socialite::driver('google')->stateless();
    
    $googleUser = $driver->redirectUrl($request->redirect_uri)->user();

    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        ['name' => $googleUser->getName(), 'password' => bcrypt(Str::random(16))]
    );


    $token = $user->createToken('google')->plainTextToken;

    event(new UserRegistered($user));

    return response()->json(['user' => $user, 'token' => $token]);
}
}