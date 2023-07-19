<?php

namespace App\Http\Controllers;

use App\Http\Requests\SharePostRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function userAuthPost(Request $request)
    {
        $user = Auth::user();
        $following_ids = $user->following()->pluck('user_id')->push($user->id);

        $posts = Post::getPostsForUser($user);
        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Posts successfully gathered',
            'posts' => $posts
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        $data = $request->validated();
        
        $success = Post::store($data);
        
        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Posted successfully',
            'post' => $success
        ]);
    }

    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        if (Auth::user()->id === $post->user_id) {
            $post->updatePost($request);
        } else {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (is_null($post)) {
            abort(404, 'Post not found');
        }

        if (Auth::user()->id === $post->user_id) {
            $post->comment()->delete();
            $post->likes()->delete();
            $post->delete();
        } else {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Deleted successfully',
        ]);
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();
        $post->likePost($user->id);
        $likeCount = $post->likes()->count();

        if ($likeCount == 1) {
            return response()->json([
                'status_code' => 200,
                'message_id' => 'LIKED_SUCCESSFULLY', 
                'message' => 'Liked successfully', 
                'likeCount' => $likeCount
            ]);
        } else {
            return response()->json([
                'status_code' => 200,
                'message_id' => 'UNLIKED_SUCCESSFULLY', 
                'message' => 'Unliked successfully', 
                'likeCount' => $likeCount
            ]);
        }
    }

    public function share(SharePostRequest $request, $id)
    {
        $user_id = Auth::user()->id;
        $post = Post::findOrFail($id);
        $validated = $request->validated();
        $post->sharePost($validated);

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Post shared successfully',
        ]);
    }
}
