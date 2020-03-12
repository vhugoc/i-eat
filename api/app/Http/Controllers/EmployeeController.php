<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\Employee;
use App\Models\Post;
use App\Models\User;
use App\Models\SPlan;

class EmployeeController extends Controller {

  /**
   * Show a list of employees
   *
   * @param  Request $request
   * @return Response
   */
  public function index(Request $request) {
    try {
      return response()->json(Employee::where('user_id', '=', $request->token->id)->get());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Show an employee by id
   *
   * @param  Request $request
   * @return Response
   */
  public function show(Request $request, $id) {
    try {
      return response()->json(Employee::where([
        ['user_id', '=', $request->token->id],
        ['id', '=', $request->id]
      ])->get()->first());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Add an employee
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
        'name'        => ['required', 'string'],
        'email'       => ['required', 'email'],
        'password'    => ['required', 'min:8', 'string'],
        'post_id'     => ['required', 'integer'],
      ]);
      if (!empty($request->get_in) && !empty($request->get_out)) {
        if ((!Helpers::validateDate($request->get_in, 'H:i') || !Helpers::validateDate($request->get_out, 'H:i')) || ($request->get_in > $request->get_out)) {
          return response()->json([
            "success"   => false,
            "message"   => "Invalid time"
          ]);
        }
      }
      if (Post::where([['user_id', '=', $request->token->id], ['id', '=', $request->post_id]])->count() !== 1) {
        return response()->json([
          "success"   => false,
          "message"   => "This post does not exists"
        ]);
      }

      /**
       * Limit validation
       * 
       */
      $limit = SPlan::where('id', '=', User::find($request->token->id)->splan_id)->get()->first()->max_employees;
      if ((Employee::where('user_id', '=', $request->token->id)->count()) == $limit) {
        return response()->json([
          "success"   => false,
          "message"   => "Limit reached"
        ]);
      }

      $exists = Employee::where([
        ['user_id', '=', $request->token->id],
        ['email', '=', $request->email]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This employee already exists"
        ]);
      }

      $employee = new Employee();

      $employee->user_id = $request->token->id;
      $employee->name = $request->name;
      $employee->email = $request->email; 
      $employee->password = Helpers::encrypt($request->password);
      $employee->post_id = $request->post_id;
      $employee->phone = $request->phone;
      $employee->landline = $request->landline;
      $employee->city = $request->city;
      $employee->district = $request->district;
      $employee->street = $request->street;
      $employee->complement = $request->complement;
      $employee->get_in = $request->get_in;
      $employee->get_out = $request->get_out;
      $employee->thumb_uri = $request->thumb_uri;

      $add = $employee->save();

      if (!$add) {
        return response()->json([
          "success"   => false,
          "message"   => "error"
        ]);
      }

      return response()->json([
        "success"   => true,
        "employee"  => $employee
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Update an employee
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
        'name'        => ['required', 'string'],
        'email'       => ['required', 'email'],
        'password'    => ['required', 'min:8', 'string'],
        'post_id'     => ['required', 'integer'],
      ]);
      if (!empty($request->get_in) && !empty($request->get_out)) {
        if ((!Helpers::validateDate($request->get_in, 'H:i') || !Helpers::validateDate($request->get_out, 'H:i')) || ($request->get_in > $request->get_out)) {
          return response()->json([
            "success"   => false,
            "message"   => "Invalid time"
          ]);
        }
      }
      if (Post::where([['user_id', '=', $request->token->id], ['id', '=', $request->post_id]])->count() !== 1) {
        return response()->json([
          "success"   => false,
          "message"   => "This post does not exists"
        ]);
      }
      
      $exists = Employee::where([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['email', '=', $request->email]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This employee already exists"
        ]);
      }

      $employee = Employee::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();

      if (!$employee) {
        return response()->json([
          "success"   => false,
          "message"   => "This employee does not exists"
        ]);
      }

      $employee->user_id = $request->token->id;
      $employee->name = $request->name;
      $employee->email = $request->email; 
      $employee->password = Helpers::encrypt($request->password);
      $employee->post_id = $request->post_id;
      $employee->phone = $request->phone;
      $employee->landline = $request->landline;
      $employee->city = $request->city;
      $employee->district = $request->district;
      $employee->street = $request->street;
      $employee->complement = $request->complement;
      $employee->get_in = $request->get_in;
      $employee->get_out = $request->get_out;
      $employee->thumb_uri = $request->thumb_uri;

      $employee->save();

      return response()->json([
        "success"   => true,
        "employee"  => $employee
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Delete an employee
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
      $employee = employee::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();
      if (!$employee) {
        return response()->json([
          "success"   => false,
          "message"   => "This employee does not exists"
        ]);
      }

      $employee->delete();

      return response()->json([
        "success"   => true
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }
}
