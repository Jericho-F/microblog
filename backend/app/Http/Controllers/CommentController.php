<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request, $id) 
    {
        $user_id = Auth::id();
        $comment = $request->input('comment');
        $post = Post::findOrFail($id);

        if ($post && !$post->trashed()) {
            $commented = Comment::create([
                'user_id' => $user_id,
                'post_id' => $post->id,
                'comment' => $comment
            ]);

            return response()->json([
                'status_code' => 200,
                'message_id' => 'SUCCESS',
                'message' => 'Comment post successfully.',
                'comment' => $commented
            ]);
        } else {
            return response()->json([
                'status_code' => 404,
                'message_id' => 'POST_NOT_FOUND',
                'message' => 'Post not found.',
            ]);
        }
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $validated = $request->validated();
        if(Auth::id() === $comment->user_id) {
            $comment->update($validated);

            return response()->json([
                'status_code' => 200,
                'message_id' => 'COMMENT_UPDATED_SUCCESSFULLY',
                'message' => 'Comment updated successfully',
                'comment' => $comment
            ]);
        } else {
            return response()->json([
                'status_code' => 403,
                'message_id' => 'UNAUTHORIZED',
                'message' => 'Unauthorized',
            ]);
        }
    }

    public function destroy($id)
    {
        $comment = Comment::where('id', $id)->first();

        if ($comment === null) {
            return response()->json([
                'status_code' => 404,
                'message_id' => 'COMMENT_NOT_FOUND',
                'message' => 'Comment cannot be found',
            ]);
        }

        if ($comment && $comment->user_id === Auth::id()) {

            $comment->delete();
            
            return response()->json([
                'status_code' => 200,
                'message_id' => 'COMMENT_DELETED_SUCCESSFULLY',
                'message' => 'Comment deleted successfully',
            ]);
        } else {
            return response()->json([
                'status_code' => 403,
                'message_id' => 'UNAUTHORIZED',
                'message' => 'Unauthorized',
            ]);
        }
    }
}