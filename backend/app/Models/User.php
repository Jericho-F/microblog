<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use App\Models\Follower;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'birthdate',
        'mobile_no',
        'lot_block',
        'street',
        'city',
        'province',
        'country',
        'zip_code',
        'image',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Like::class);
    }

    public function sharedPosts()
    {
        return $this->belongsToMany(Post::class, 'shares', 'user_id', 'post_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    /** 
     * Function to get the post of a user
     * 
     * $id
    */
    public function getUserProfile($id)
    {
        $userProfile = $this->with(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        $userProfile->loadCount('posts');
        
        return $userProfile;
    }
    /** 
     * Function to get the followers/followings count of a user
     * 
     * $id
    */
    public function getFollowsCount($id)
    {
        $user = $this->findOrFail($id);

        return [
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ];
    }
    /** 
     * Function to get the post of a user
     * 
     * $id
    */
    public function getPosts($id)
    {
        return $this->findOrFail($id)
            ->posts()
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->withCount('likes')
            ->get();
    }
    /** 
     * Function to format the date and time of the posts
     * 
     * $posts
    */
    public function formatPosts($posts)
    {
        $posts->each(function ($post) {
            $post->timeElapsed = $post->created_at->format('Y-m-d h:i A');
            $post->timeElapsed = $post->created_at->timezone('Asia/Manila')->format('Y-m-d h:i A');
            
            if ($post->originalPost) {
                $post->originalPost->timeElapsed = $post->originalPost->created_at->format('Y-m-d h:i A');
                $post->timeElapsed = $post->originalPost->created_at->timezone('Asia/Manila')->format('Y-m-d h:i A');
            }
        });

        return $posts;
    }
    
    /** 
     * Function that process the formatting of the date and time of the certain post
     * 
     * $id
    */
    public function getFormattedPosts($id)
    {
        $posts = $this->getPosts($id);
        return $this->formatPosts($posts);
    }

    /** 
     * Function to retrieve all the data based on the input of the user
     * 
     * $query
    */
    public static function searchUsers($query)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status_code' => 403,
                'message_id' => 'UNAUTHORIZED',
                'message' => 'Unauthorized',
            ]);
        }

        return User::whereNotNull('email_verified_at')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('username', 'LIKE', "%{$query}%");
                    })->get();
    }

    /** 
     * Function to follow and unfollow a user
     * 
     * $follower_id
    */
    public function follow($follower_id)
    {
        $follower = Follower::where('user_id', $this->id)
                            ->where('follower_id', $follower_id)
                            ->first();

        if ($follower) {
            $follower->delete();
        } else {
            Follower::create([
                'user_id' => $this->id,
                'follower_id' => $follower_id,
            ]);
        }
    }

    /** 
     * Function to update or change the user's image
     * 
     * $image
    */
    public function changeProfilePicture($image)
    {
        $extenstion = $image->getClientOriginalExtension();
        $dateTime = now()->format('Ymd_His');
        $imageName = $dateTime . '.' . $extenstion;
        Storage::disk('public')->put('storage/profile_pictures/' . $this->id . '/' . $imageName, file_get_contents($image));

        $this->image = $imageName;
        $this->save();
    }
}