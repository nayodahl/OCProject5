<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function showHomePage()
    {       
        $posts = Post::paginate(2);

        return view('homePage', ['posts' => $posts]);
    }
    
    public function showSinglePost($id)
    {       
        $post = Post::findOrFail($id);
        $comments = $post->comments;

        return view(
            'singlePostPage', [
            'post' => $post,
            'comments' => $comments,
            ]
        );
    }

    public function showAllPosts()
    {
        $posts = Post::paginate(2);

        return view('postsPage', ['posts' => $posts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $request->validate(
            [
            'title'=>'required',
            'chapo'=>'required',
            'content'=>'required',
            ]
        );

        $post = new Post(
            [
            'title' => $request->get('title'),
            'chapo' => $request->get('chapo'),
            'content' => $request->get('content'),
            'user_id' => $request->get('author')
            ]
        );

        $post->save();

        return redirect()->route('app_admin_posts_show')->with('success', 'Article ajouté !');
    }
}
