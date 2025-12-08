<?php

namespace App\Http\Controllers;



use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\LoginAdminRequest;
use App\Services\Admin\CreateAdminService;
use App\Services\Admin\FindEmailService;
use App\Traits\PasswordsTrait;
use Illuminate\Http\Request;



class AdminController extends Controller
{
    
   use PasswordsTrait;
   public function register (CreateAdminRequest $request , CreateAdminService $createService): array{
        $fields = $request -> validated();
        $admin = $createService->handle($fields);
        $token = $admin -> createToken($admin->name);
        return ["admin" => $admin , "token" => $token->plainTextToken];
   }

   public function login(LoginAdminRequest $request , FindEmailService $findService): array{
        $request -> validated();

        $admin = $findService->handle($request->email);

        $this->checkEmailPassword($admin , $request);
        
        $token = $admin -> createToken($admin->name);
        return ["admin" => $admin , "token" => $token->plainTextToken];
   }

   public function logout(Request $request){
    $request->admin()->tokens()->delete();
    return ["message" => "you are logged out"];
   }
}
