<?php
namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait PasswordsTrait{
    public function checkEmailPassword($var , $request){
        if(!$var || !Hash::check($request->password , $var->password)){
            return [
                "errors" =>[
                   "email" => ["The provided credentials incorrect"]
                ] 
            ];
        }
    }
}