<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('theme.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        //Image Upload
        //1- Get the image from the request
        $image = $request->image;
        //2- Generate a unique name for the image
        $newImageName = time() . '-' . $image->getClientOriginalName();
        //3- Move the image to the public folder
        $image->storeAs('blogs', $newImageName , 'public');
        //4- Save the image name in the database
        $data['image'] = $newImageName;
        $data['user_id'] = Auth::user()->id;
        Blog::create($data);
        //5- Redirect to the index page
        return back()->with('blog_created', 'Blog created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view('theme.blog-details', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        if ($blog->user_id == Auth::user()->id) {
            $categories = Category::all();
            return view('theme.blogs.edit', compact('categories', 'blog'));
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        if ($blog->user_id == Auth::user()->id){
        // dd($request->all());
        $data = $request->validated();
        //Check if the user uploaded a new image
        if ($request->hasFile('image')) {
        //1- Delete the old image from the public folder
        Storage::delete("public/blogs/$blog->image");
        //2- Get the new image from the request
        $image = $request->image;
        //2- Generate a unique name for the new image
        $newImageName = time() . '-' . $image->getClientOriginalName();
        //3- Move the new image to the public folder
        $image->storeAs('blogs', $newImageName , 'public');
        //4- Save the new image name in the database
        $data['image'] = $newImageName;
        
        }
        $blog->update($data);
        return back()->with('blog_updated', 'Blog updated successfully');
         }
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->user_id == Auth::user()->id) {
            //1- Delete the image from the public folder
            Storage::delete("public/blogs/$blog->image");
            //2- Delete the blog from the database
            $blog->delete();
            return back()->with('blog_deleted', 'Blog deleted successfully');
        }
        abort(403, 'Unauthorized action.');
    }
    // Display the blogs of the authenticated user
    public function myBlogs()
    {
        if (Auth::check()) {
            $blogs = Blog::where('user_id', Auth::user()->id)->paginate(10);
            return view('theme.blogs.my-blogs', compact('blogs'));
        }
        return redirect()->route('login');
    }
}
