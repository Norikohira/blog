<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/images/';
    private $user;
    private $post;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function profile($id)
    {
        $user = $this->user->findOrFail($id);
        $posts = $user->posts;

        return view('posts.profile')
                ->with('user', $user)
                ->with('posts', $posts);
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view('posts.profile-edit')
                ->with('user', $user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('avatar', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('profile', ['id' => $id]);
    }
}
