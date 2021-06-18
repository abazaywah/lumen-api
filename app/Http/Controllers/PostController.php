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
        $this->validate($request, [
            'title' => 'required|unique:posts|max:255|string',
            'body' => 'required|string',
        ]);

        $input = $request->only(['title', 'body']);
        $post = new Post();
        $post->fill($input);

        if ($post->save()) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Post created.',
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Failed.',
                ],
                412
            );
        }

        return response()->json(
            [
                'status' => 'failed',
                'message' => 'Error...',
            ],
            500
        );
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if ($post != null) {
            // data type and length validation
            $this->validate($request, [
                'title' => 'max:255|string',
                'body' => 'string',
            ]);

            $input = $request->all();

            //unique check init
            $except = [];

            // unique check step 1
            if ($request->filled('title')) {
                if ($request->title === $post->title) {
                    $except[] = 'title';
                }
            }

            if (!empty($except)) {
                $input = $request->except($except);
            }

            // unique check step 2
            if (isset($input['title'])) {
                $this->validate($request, [
                    'title' => 'unique:posts',
                ]);
            }

            $post->fill($input);

            if ($post->save()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Post updated.',
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'status' => 'failed',
                        'message' => 'Update failed.',
                    ],
                    412
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Data not found.',
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'failed',
                'message' => 'Error...',
            ],
            500
        );
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if ($post != null) {
            if ($post->delete()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Post deleted.',
                    ],
                    200
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Data not found.',
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'failed',
                'message' => 'Error...',
            ],
            500
        );
    }
}
