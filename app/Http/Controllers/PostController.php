<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
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
}
