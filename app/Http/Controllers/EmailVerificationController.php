<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
public function verify(Request $request , $id , $hash) {
    $user = User::find($id);

    if (! $user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Check hash
    if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        return response()->json(['message' => 'Invalid verification hash'], 400);
    }

    // Check signature
    if (! URL::hasValidSignature($request)) {
        return response()->json(['message' => 'Invalid or expired link'], 403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return response()->json(['message' => 'Email verified successfully!']);
    }
}