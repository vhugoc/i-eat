<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\Product;
use App\Models\User;
use App\Models\SPlan;
use App\Models\Category;

class ProductController extends Controller {

  /**
   * Show a list of products
   *
   * @param  Request $request
   * @return Response
   */
  public function index(Request $request) {
    try {
      return response()->json(Product::where('user_id', '=', $request->token->id)->get());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Show a product by id
   *
   * @param  Request $request
   * @return Response
   */
  public function show(Request $request, $id) {
    try {
      return response()->json(Product::where([
        ['user_id', '=', $request->token->id],
        ['id', '=', $request->id]
      ])->get()->first());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Add a product
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
        'code'        => ['required', 'integer'],
        'name'        => ['required', 'string'],
        'category_id' => ['required', 'integer'],
        'price'       => ['required', 'numeric']
      ]);
      if (Category::where([['user_id', '=', $request->token->id], ['id', '=', $request->category_id]])->count() !== 1) {
        return response()->json([
          "success"   => false,
          "message"   => "This category does not exists"
        ]);
      }

      /**
       * Limit validation
       * 
       */
      $limit = SPlan::where('id', '=', User::find($request->token->id)->splan_id)->get()->first()->max_products;
      if ((Product::where('user_id', '=', $request->token->id)->count()) == $limit) {
        return response()->json([
          "success"   => false,
          "message"   => "Limit reached"
        ]);
      }

      $exists = Product::where([
        ['user_id', '=', $request->token->id],
        ['code', '=', $request->code]
      ])->orWhere([
        ['user_id', '=', $request->token->id],
        ['name', '=', $request->name]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This product already exists"
        ]);
      }

      $product = new Product();

      $product->user_id = $request->token->id;
      $product->code = $request->code;
      $product->name = $request->name;
      $product->category_id = $request->category_id;
      $product->description = $request->description;
      $product->price = $request->price;

      $add = $product->save();

      if (!$add) {
        return response()->json([
          "success"   => false,
          "message"   => "error"
        ]);
      }

      return response()->json([
        "success"   => true,
        "product"      => $product
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Update a product
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
        'code'        => ['required', 'integer'],
        'name'        => ['required', 'string'],
        'category_id' => ['required', 'integer'],
        'price'       => ['required', 'numeric']
      ]);
      if (Category::where([['user_id', '=', $request->token->id], ['id', '=', $request->category_id]])->count() !== 1) {
        return response()->json([
          "success"   => false,
          "message"   => "This category does not exists"
        ]);
      }
      $exists = Product::where([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['name', '=', $request->name]
      ])->orWhere([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['code', '=', $request->code]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This product already exists"
        ]);
      }

      $product = Product::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();

      if (!$product) {
        return response()->json([
          "success"   => false,
          "message"   => "This product does not exists"
        ]);
      }

      $product->code = $request->code;
      $product->name = $request->name;
      $product->category_id = $request->category_id;
      $product->description = $request->description;
      $product->price = $request->price;

      $product->save();

      return response()->json([
        "success"   => true,
        "product"      => $product
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Delete a product
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
      $product = Product::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();
      if (!$product) {
        return response()->json([
          "success"   => false,
          "message"   => "This product does not exists"
        ]);
      }

      $product->delete();

      return response()->json([
        "success"   => true
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }
}
