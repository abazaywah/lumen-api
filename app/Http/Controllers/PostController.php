<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|unique:posts|max:255|string',
                'body' => 'required|string',
            ]);
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;
            if ($post->save()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Post created.',
                    ],
                    201
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ],
                412
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'title' => 'unique:posts|max:255|string',
                'body' => 'string',
            ]);
            $post = Post::findOrFail($id);
            $data = $request->all();
            $post->fill($data);
            if ($post->save()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Post updated.',
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ],
                412
            );
        }
    }

    public function destroy($id) {
        try {
            $post = Post::findOrFail($id);

            if ($post->delete()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Post deleted.',
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ],
                412
            );
        }
    }
}
