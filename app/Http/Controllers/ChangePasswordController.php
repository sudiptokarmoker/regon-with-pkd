<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ChangePasswordFormRequest $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if (!Hash::check($data['password_current'], $user->password)) {
            return redirect()->route('home')->with(['error' => __('passwords.wrong_current')]);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('home')->with(['message' => __('passwords.reset')]);
    }
}
