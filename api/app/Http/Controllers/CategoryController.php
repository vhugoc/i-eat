<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\Category;
use App\Models\User;

class CategoryController extends Controller {

  /**
   * Show a list of categories
   *
   * @param  Request $request
   * @return Response
   */
  public function index(Request $request) {
    try {
      return response()->json(Category::where('user_id', '=', $request->token->id)->get());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Show a category by id
   *
   * @param  Request $request
   * @return Response
   */
  public function show(Request $request, $id) {
    try {
      return response()->json(Category::where([
        ['user_id', '=', $request->token->id],
        ['id', '=', $request->id]
      ])->get()->first());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Add a category
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

      $exists = Category::where([
        ['user_id', '=', $request->token->id],
        ['name', '=', $request->name]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This category already exists"
        ]);
      }

      $category = new Category();

      $category->user_id = $request->token->id;
      $category->name = $request->name;
      $category->color = $request->color;

      $add = $category->save();

      if (!$add) {
        return response()->json([
          "success"   => false,
          "message"   => "error"
        ]);
      }

      return response()->json([
        "success"   => true,
        "category"      => $category
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Update a category
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
      $exists = Category::where([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['name', '=', $request->name]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This category already exists"
        ]);
      }

      $category = Category::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();

      if (!$category) {
        return response()->json([
          "success"   => false,
          "message"   => "This category does not exists"
        ]);
      }

      $category->name = $request->name;
      $category->color = $request->color;

      $category->save();

      return response()->json([
        "success"   => true,
        "category"      => $category
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Delete a category
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
      $category = Category::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();
      if (!$category) {
        return response()->json([
          "success"   => false,
          "message"   => "This category does not exists"
        ]);
      }

      $category->delete();

      return response()->json([
        "success"   => true
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }
}
