<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangeImageRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

class UserController extends Controller
{
    /** 
     * Function that displays all the data gathered
     * 
     * @param Request $request
    */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(404);
        }
    
        $posts = $user->getFormattedPosts($user->id);
        $userProfile = $user->getUserProfile($user->id);
        $followsCount = $user->getFollowsCount($user->id);

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS RETRIEVED',
            'message' => 'User data successfully retrieved',
            'posts' => $posts,
            'userProfile' => $userProfile,
            'followsCount' => $followsCount
        ]);
    }

    /** 
     * Function to validate the user's input and execute the query to retrieve data
     * 
     * @param Request $request
    */
    
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => ['required', 'string', 'regex:/^[^<>]*$/', 'max:'
            . config('constants.SEARCH_MAX_LENGTH')],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status_code' => 403,
                'message_id' => 'UNAUTHORIZED',
                'message' => 'Unauthorized'
            ]);
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $search = $request->get('search');
        $users = User::searchUsers($search);

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Search successfully',
            'result' => $users
        ]);
    }

    /** 
     * Function to logout the user from the homepage and deletes the session of user
     * 
     * @param Request $request
    */
    public function logout(Request $request)
    {   
        $user = Auth::user();
        $user->tokens()->delete();
        
        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Logged out successfully',
        ]);
    }

    /** 
     * Function to follow/unfollow user
    */
    public function follow($id)
    {
        $user = User::findOrFail($id);
        $user->follow(auth()->user()->id);
        $following = $user->followers->contains('follower_id', auth()->user()->id);

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Followed successfully',
            'isFollowing' => $following
        ]);
    }

    /** 
     * Function updates the user's information
     * @param UpdateProfileRequest -$request
    */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return abort(404);
        }

        if (Auth::user()->id === $user->id) {
            $data = $request->validated();
            $updated = $user->update($data);

            return response()->json([
                'status_code' => 200,
                'message_id' => 'SUCCESS',
                'message' => 'Updated successfully.',
                'updated' => $updated
            ]);
        } else {
            return response()->json([
                'status_code' => 403,
                'message_id' => 'UNAUTHORIZED',
                'message' => 'Unauthorized.',
            ]);
        }
    }
    
    /** 
     * Function to change the image of the user
     * 
     * @param Request $request
    */
    public function changeImage(ChangeImageRequest $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status_code' => 403,
                'message_id' => 'UNAUTHORIZED',
                'message' => 'Unauthorized',
            ]);
        }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $user->changeProfilePicture($image);
            
            return response()->json([
                'status_code' => 200,
                'message_id' => 'SUCCESS',
                'message' => $image
            ]);
        }

    }

    /** 
     * Function to display all the followings of a user with pagination
     * 
     * @param Request $request
    */
    public function getFollowing($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            abort(404);
        }

        $followings = $user->following()->with('user')->get();

        if ($followings->isEmpty()) {
            return response()->json([
                'status_code' => 404,
                'message_id' => 'FOLLOWINGS_NOT_FOUND',
                'message' => 'Followings not found.',
            ]);
        }
        
        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Followings successfully retrieved.',
            'followings' => $followings
        ]);
    }

    /** 
     * Function to display all the followers of a user with pagination
     * 
     * @param Request $request
    */
    public function getFollower($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            abort(404);
        }

        $followers = $user->followers()->with('follower')->get();

        if ($followers->isEmpty()) {
            return response()->json([
                'status_code' => 404,
                'message_id' => 'FOLLOWERS_NOT_FOUND',
                'message' => 'Followers not found.',
            ]);
        }

        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Followers successfully retrieved.',
            'followers' => $followers
        ]);
    }

    /** 
     * Function to change the password of the user
     * 
     * @param ChangePasswordRequest $request
    */
    
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if ($user && Hash::check($request->current_password, $user->password)) {
            if ($user->id !== $request->user()->id) {
                abort(403);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status_code' => 200,
                'message_id' => 'SUCCESS',
                'message' => 'Password changed successfully.',
            ]);
        }

        return response()->json([
            'status_code' => 401,
            'message_id' => 'INPUT_ERROR',
            'message' => 'Incorrect current password.',
        ]);
    }
}
