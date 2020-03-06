<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\Post;

class PostController extends Controller {

  /**
   * Show a list of posts
   *
   * @param  Request $request
   * @return Response
   */
  public function index(Request $request) {
    try {
      return response()->json(Post::where('user_id', '=', $request->token->id)->get());
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Show a post by id
   *
   * @param  Request $request
   * @return Response
   */
  public function show(Request $request, $id) {
    try {
      return response()->json(Post::find($id));
    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Add a post
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
        'access'      => ['required', 'max:2', 'integer'],
        'is_active'   => ['required', 'boolean']
      ]);
      $exists = Post::where([
        ['user_id', '=', $request->token->id],
        ['name', '=', $request->name]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This post already exists"
        ]);
      }

      $post = new Post();

      $post->user_id = $request->token->id;
      $post->name = $request->name;
      $post->access = $request->access;
      $post->is_active = $request->is_active;
      $post->description = $request->description;

      $add = $post->save();

      if (!$add) {
        return response()->json([
          "success"   => false,
          "message"   => "error"
        ]);
      }

      return response()->json([
        "success"   => true,
        "post"      => $post
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Update a post
   * 
   * @param Request $request
   * @return Response
   */
  public function update(Request $request, $id) {
    try {

    } catch (Exception $err) {
      return $err;
    }
  }
}
