<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function submit(Request $request)
    {
        // validate phone number
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        //find or create user model
        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if (!$user) {
            return response()->json(['message' => 'Could not process a user with that phone number.'], 401);
        }

        //send the user one-time code
        $user->notify(new LoginNeedsVerification());

        //return back a response
        return response()->json(['message' => 'Text message notification sent.']);
    }

    public function verify(Request $request)
    {
        // dd($request->all());
        // validate incoming request
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        //find the user
        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
            ->first();

        //if user have phone number and login code
        if ($user) {
            $user->update([
                'login_code' => null
            ]);

            return $user->createToken($request->login_code)->plainTextToken;
        }

        //if user not found, return back a message
        return response()->json(['message' => 'Invalid verification code.'], 401);
    }
}
