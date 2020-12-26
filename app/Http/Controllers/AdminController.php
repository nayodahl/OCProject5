<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showAllPosts()
    {
        $posts = Post::paginate(2);

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
}
