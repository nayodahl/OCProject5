<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $postId)
    {        
        $request->validate(
            [
            'comment'=>'required',
            ]
        );

        $id = Auth::id();

        $comment = new Comment(
            [
            'content' => $request->get('comment'),
            ]
        );

        $comment->approved = 0;
        $comment->post_id = $postId;
        $comment->user_id = $id;

        $comment->save();

        return redirect()->route('app_post_show', ['id' => $postId])->with('success', 'Commentaire ajouté !');
    }

    public function approveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approved = 1;
        $comment->save();

        return redirect()->route('app_admin_comments_show')->with('success', 'Commentaire approuvé !');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return redirect()->route('app_admin_comments_show')->with('success', 'Commentaire supprimé !');
    }
}
