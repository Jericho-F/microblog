<?php

namespace App\Models;

use App\Http\Requests\SharePostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\User;
use App\Models\Comment;
use App\Rules\CustomImageRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Post extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_id', 'content', 'image', 'deleted_at'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment() 
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    /** 
     * Function to get all the posts of a user
     * 
     * $user, $page
    */
    public static function getPostsForUser($user)
    {
        $following_ids = $user->following()->pluck('user_id')->push($user->id);
        
        $posts = Post::whereIn('user_id', $following_ids)
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->with('originalPost')
            ->withCount('likes')
            ->get();

        foreach ($posts as $post) {
            $post->comment = $post->comment()->get();
            $post->timeElapsed = $post->created_at->format('Y-m-d h:i A');
            $post->timeElapsed = $post->created_at->timezone('Asia/Manila')->format('Y-m-d h:i A');

            if ($post->originalPost) {
                $post->originalPost->timeElapsed = $post->originalPost->created_at->format('Y-m-d h:i A');
                $post->timeElapsed = $post->originalPost->created_at->timezone('Asia/Manila')->format('Y-m-d h:i A');
            }
        }

        return $posts;
    }
    /** 
     * Function to store or post a content or image
     * 
     * $data
    */
    
    public static function store($data)
    {
        $processedData = $data;
        if (isset($processedData['content'])) {
            $processedData['content'] = str_replace(["\r\n", "\r"], "\n", $processedData['content']);
        }

        $validator = Validator::make($processedData, [
            'content' => ['nullable', 'max:140'],
            'image' => ['nullable', 'max:2048'],
        ]);

        $validator->sometimes(['content', 'image'], 'required', function ($input) {
            return is_null($input->content) && is_null($input->image);
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::id();
        $imageName = null;

        if (isset($data['image'])) {
            $originalName = $data['image']->getClientOriginalName();
            $extension = $data['image']->getClientOriginalExtension();
            $dateTime = now()->format('Ymd_His');
            $imageName = $dateTime . '.' . $extension;

            Storage::disk('public')->put('storage/post_images/' . $user_id . '/' . $imageName, file_get_contents($data['image']));
            $data['image'] = $imageName;
        } else {
            $data['image'] = null;
        }        

        $post = Post::create([
            'user_id' => $user_id,
            'content' => $data['content'],
            'image' => $data['image'],
        ]);

        return $post;
    }

    /** 
     * Function to update the post
     * 
     * @param PostUpdateRequest $request
    */
    public function updatePost(PostUpdateRequest $request)
    {
        $user_id = Auth::id();
        $imageName = null;

        $processedData = $request->validated();

        if (isset($processedData['content'])) {
            $oldContent = $this->content;
            $newContent = $processedData['content'];

            if ($oldContent !== $newContent) {
                $processedData['content'] = $newContent;
            } else {
                unset($processedData['content']);
            }
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $dateTime = now()->format('Ymd_His');
            $imageName = $dateTime . '.' . $extension;

            Storage::disk('public')->put('storage/post_images/' . $user_id . '/' . $imageName, file_get_contents($image));
            $processedData['image'] = $imageName;
        } else {
            $processedData['image'] = $request->old_image;
        }
        
        if (!empty($processedData)) {
            $this->update($processedData);
        }

        return true;
    }
    /** 
     * Function to like the post
     * 
     * $user_id
    */
    public function likePost($user_id)
    {
        $this->likes()->where('user_id', $user_id)->exists()
            ? $this->likes()->where('user_id', $user_id)->delete()
            : Like::create(['user_id' => $user_id, 'post_id' => $this->id]);
    }

    /** 
     * Function to share a post
     * 
     * @param SharePostRequest $request
    */
    public function sharePost($data)
    {
        $user_id = Auth::user()->id;
        $processedContent = preg_replace("/\R/u", "\n", $data['content']);

        self::create([
            'user_id' => $user_id,
            'post_id' => $this->post_id ?? $this->id,
            'content' => $processedContent,
        ]);

        return true;
    }

}
