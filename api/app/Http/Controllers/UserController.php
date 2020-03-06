<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\User;
use App\Models\Splan;
use App\Models\Sign;

class UserController extends Controller {

  /**
   * Show a list of all of the application's users.
   *
   * @return Response
   */
  public function index() {
    try {
      return response()->json([
        "Helper"   => Helpers::encrypt("teste"),
        "users"    => User::all(),
        "splans"   => Splan::all()
      ]);
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Show an user
   *
   * @param  Request $request
   * @return Response
   */
  public function show(Request $request) {
    try {
      $id = $request->token->id;
      $user = User::find($id);
      return response()->json($user);
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Register an user
   * 
   * @param Request $request
   * @return Response
   */
  public function register(Request $request) {
    try {

      /**
       * Data Validation
       * 
       */
      $this->validate($request, [
        'company'     => ['required', 'max:100'],
        'email'       => ['required', 'email', 'unique:users'],
        'password'    => ['required', 'min:8'],
        'splan_id'    => ['required', 'max:15']
      ]);

      $splan = Splan::find($request->splan_id);
      if (!$splan) {
        return response()->json([
          "success"   => false,
          "message"   => "Subscription plan does not exists"
        ]);
      }

      /**
       * Registering user
       * 
       */
      $user = new User;

      $user->company = $request->company;
      $user->phone = $request->phone;
      $user->email = $request->email;
      $user->password = Helpers::encrypt($request->password);
      $user->splan_id = $request->splan_id;

      $register = $user->save();

      $user = User::where("email", "=", $request->email)->get()->first();
      
      if ($register) {
        return response()->json([
          "success"   => true,
          "user"      => $user
        ]);
      }

      return response()->json([
        "success"   => false,
        "message"   => "error"
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Sign In
   *
   * @param  Request $request
   * @return Response
   */
  public function signin(Request $request) {
    try { 

      /**
       * Data Validation
       * 
       */
      $this->validate($request, [
        'email'     => ['required', 'email'],
        'password'  => ['required', 'min:8'],
        'type'      => ['required']
      ]);

      if ($request->type !== "user") {
        return response()->json([
          "success"   => false,
          "message"   => "invalid user type"
        ]);
      }

      $login = User::where([
        ['email', '=', $request->email],
        ['password', '=', Helpers::encrypt($request->password)]
      ])->first();

      if (empty($login)) {
        return response()->json([
          "success"  => false,
          "message"  => "incorrect email/password"
        ]);
      }

      $token = Helpers::generateJWT($login->id, $login->email, 'user');

      /**
       * Registering sign history
       * 
       */
      $sign = new Sign();
      if ($request->type == "user") {
        $sign->user_id = $login->id;
      }
      $sign->save();

      return response()->json([
        "success"   => true,
        "token"     => $token,
        "user"      => $login,
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Sign out
   *
   * @param  Request $request
   * @return Response
   */
  public function signout(Request $request) {
    try {

      $sign = new Sign();
      if ($request->token->type == "user") {
        $sign->user_id = $request->token->id;
        $sign->action = "out";
      }
      $sign->save();
      
    } catch (Exception $err) {
      return $err;
    }
  }
}
