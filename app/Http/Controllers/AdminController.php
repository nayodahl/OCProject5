<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:see-backoffice');
    }
    
    public function showAllPosts()
    {
        $posts = Post::paginate(5);

        return view('postsManagerPage', ['posts' => $posts]);
    }

    public function showAllComments()
    {
        $comments = Comment::all();

        return view('commentsManagerPage', ['comments' => $comments]);
    }

    public function showAllUsers()
    {
        $users = User::all();

        return view('usersManagerPage', ['users' => $users]);
    }

    public function createPost()
    {
        $admins = User::all()->where('isAdmin', true);

        return view('postCreatePage', ['admins' => $admins]);
    }
}
