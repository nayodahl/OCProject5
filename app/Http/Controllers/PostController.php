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
        $comments = $post->comments->where('approved', 1);

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
            'title'=>'required',
            'chapo'=>'required',
            'content'=>'required',
            ]
        );
        $post = Post::findOrFail($id);

        $post->title = $request->get('title');
        $post->chapo = $request->get('chapo');
        $post->content = $request->get('content');
        $post->user_id = $request->get('author');

        $post->save();

        return redirect()->route('app_admin_posts_show')->with('success', 'Article modifié !');
    }

    public function deletePost(int $id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        return redirect()->route('app_admin_posts_show')->with('success', 'Article supprimé !');
    }
}
