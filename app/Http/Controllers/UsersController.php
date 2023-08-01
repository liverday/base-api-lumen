<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class UsersController extends BaseController
{

  public function me()
  {
    return response()->json(
      auth()->user()
    );
  }

  public function create(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|confirmed',
    ]);

    try {
      $user = new User;
      $user->name = $request->input('name');
      $user->email = $request->input('email');
      $plainPassword = $request->input('password');
      $user->password = app('hash')->make($plainPassword);

      $user->save();

      //return successful response
      return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'User Registration Failed!'], 409);
    }
  }
}