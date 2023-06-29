<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/images/';

    private $post;
    private $comment;

    public function __construct(Post $post, Comment $comment)
    {
        $this->post = $post;
        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_posts = $this->post->latest()->get();

        return view('posts.index')
                ->with('all_posts', $all_posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        # Validate the request
        $request->validate([
            'title' => 'required|min:1|max:50',
            'body'  => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:1048'
            // mimes ~~ multipurpose internet mail extensions
        ]);

        # Save the request to the database
        $this->post->user_id    = Auth::user()->id;
        //owner of the post     = the logged-in user
        $this->post->title      = $request->title;
        $this->post->body       = $request->body;
        $this->post->image      = $this->saveImage($request);
        $this->post->save();

        # Redirect to homepage
        return redirect()->route('index');
    }

    private function saveImage($request)
    {
        // Change the name of the image to the CURRENT TIME to avoid overwriting
        $image_name = time() . "." . $request->image->extension();

        // Save the image inside the local storage ~~ storage/app/public/images
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);
        // storeAs(destination, file_name) ~~ used to store the uploaded file/image
        
        return $image_name;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = $this->post->with('comments.user')->findOrFail($id);

        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:1|max:50',
            'body'  => 'required|min:1|max:1000',
            'image' => 'mimes:jpg, jpeg, png, gif|max:1048'
        ]);

        $post           = $this->post->findOrFail($id);
        $post->title    = $request->title;
        $post->body     = $request->body;

        # If there is a NEW image...
        if($request->image){
            # DELETE the previous image from the local storage folder
            $this->deleteImage($post->image);

            # MOVE the new image to the local storage folder
            $post->image = $this->saveImage($request);
        }

        $post->save();
        return redirect()->route('post.show', $id);
    }

    private function deleteImage($image_name)
    {
        $image_path = self::LOCAL_STORAGE_FOLDER . $image_name;
        // $image_path = "/public/images/12345.jpg";

        if(Storage::disk('local')->exists($image_path)){
            Storage::disk('local')->delete($image_path);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);

        $this->deleteImage($post->image);
        
        $post->delete();

        return redirect()->route('index');
    }


}
