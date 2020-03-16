<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Models\Post;
use App\Models\User;
use App\Models\SPlan;

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
      return response()->json(Post::where([
        ['user_id', '=', $request->token->id],
        ['id', '=', $request->id]
      ])->get()->first());
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
        'access'      => ['required', 'max:6', 'integer'],
        'is_active'   => ['required', 'boolean']
      ]);

      /**
       * Limit validation
       * 
       */
      $limit = SPlan::where('id', '=', User::find($request->token->id)->splan_id)->get()->first()->max_posts;
      if ((Post::where('user_id', '=', $request->token->id)->count()) == $limit) {
        return response()->json([
          "success"   => false,
          "message"   => "Limit reached"
        ]);
      }

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
        'access'      => ['required', 'max:2', 'integer'],
        'is_active'   => ['required', 'boolean']
      ]);
      $exists = Post::where([
        ['id', '<>', $id],
        ['user_id', '=', $request->token->id],
        ['name', '=', $request->name]
      ])->count();

      if ($exists) {
        return response()->json([
          "success"   => false,
          "message"   => "This post already exists"
        ]);
      }

      $post = Post::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();

      if (!$post) {
        return response()->json([
          "success"   => false,
          "message"   => "This post does not exists"
        ]);
      }

      $post->name = $request->name;
      $post->access = $request->access;
      $post->is_active = $request->is_active;
      $post->description = $request->description;

      $post->save();

      return response()->json([
        "success"   => true,
        "post"      => $post
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }

  /**
   * Delete a post
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
      $post = Post::where([
        ['id', '=', $id],
        ['user_id', '=', $request->token->id]
      ])->get()->first();
      if (!$post) {
        return response()->json([
          "success"   => false,
          "message"   => "This post does not exists"
        ]);
      }

      $post->delete();

      return response()->json([
        "success"   => true
      ]);

    } catch (Exception $err) {
      return $err;
    }
  }
}
