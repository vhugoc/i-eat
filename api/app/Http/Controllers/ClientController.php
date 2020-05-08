<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\Client;
use App\Models\User;

class ClientController extends Controller {

  /**
   * Show a list of clients
   *
   * @param  Request $request
   * @return Response
   */
  public function index(Request $request) {
    try {
      return response()->json(Client::where('user_id', '=', $request->token->id)->get());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Show a client by id
   *
   * @param  Request $request
   * @return Response
   */
  public function show(Request $request, $id) {
    try {
      return response()->json(Client::where([
        ['user_id', '=', $request->token->id],
        ['id', '=', $request->id]
      ])->get()->first());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Add a client
   * 
   * @param Request $request
   * @return Response
   */
  public function add(Request $request) {
    try {

      /**
       * Data Validation
       * 
       */
      $this->validate($request, [
        'name'        => ['required', 'string']
      ]);

      $exists = Client::where([
        ['user_id', '=', $request->token->id],
        ['phone', '<>', '',],
        ['phone', '=', $request->phone]
      ])->orWhere([
        ['user_id', '=', $request->token->id],
        ['landline', '<>', '',],
        ['landline', '=', $request->landline]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This client already exists"
        ]);
      }

      $client = new Client();

      $client->user_id = $request->token->id;
      $client->name = $request->name;
      $client->phone = $request->phone;
      $client->landline = $request->landline;
      $client->city = $request->city;
      $client->district = $request->district;
      $client->street = $request->street;
      $client->complement = $request->complement;
      $client->is_loyalty = $request->is_loyalty;
      $client->birthday = $request->birthday;
      $client->obs = $request->obs;

      $add = $client->save();

      if (!$add) {
        return response()->json([
          "success"   => false,
          "message"   => "error"
        ]);
      }

      return response()->json([
        "success"   => true,
        "client"      => $client
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Update a client
   * 
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(Request $request, $id) {
    try {

      /**
       * Data Validation
       * 
       */
      $this->validate($request, [
        'name'        => ['required', 'string']
      ]);

      if (isset($request->birthday)) {
        if (!Helpers::validateDate($request->birthday, 'Y-m-d')) {
          return response()->json([
            "success"   => false,
            "message"   => "Invalid birthday format"
          ]);
        }
      }

      $exists = Client::where([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['phone', '<>', '',],
        ['phone', '=', $request->phone]
      ])->orWhere([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['landline', '<>', '',],
        ['landline', '=', $request->landline]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This client already exists"
        ]);
      }

      $client = Client::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();

      if (!$client) {
        return response()->json([
          "success"   => false,
          "message"   => "This client does not exists"
        ]);
      }

      $client->name = $request->name;
      $client->phone = $request->phone;
      $client->landline = $request->landline;
      $client->city = $request->city;
      $client->district = $request->district;
      $client->street = $request->street;
      $client->complement = $request->complement;
      $client->is_loyalty = $request->is_loyalty;
      $client->birthday = $request->birthday;
      $client->obs = $request->obs;

      $client->save();

      return response()->json([
        "success"   => true,
        "client"      => $client
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Delete a client
   * 
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function delete(Request $request, $id) {
    try {

      /**
       * Data Validation
       * 
       */
      $client = Client::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();
      if (!$client) {
        return response()->json([
          "success"   => false,
          "message"   => "This client does not exists"
        ]);
      }

      $client->delete();

      return response()->json([
        "success"   => true
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }
}
