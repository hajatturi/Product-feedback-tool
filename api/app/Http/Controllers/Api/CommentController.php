<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\User;

class CommentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'feedback_id' => 'required',
        ]);

        $comment = new Comment([
            'content' => $validatedData['content'],
            'user_id' => auth()->id(),
            'feedback_id' => $validatedData['feedback_id'],
        ]);
        $comment->save();

        return response()->json(['message' => 'Comment posted successfully', 'comment' => $comment], 201);
    }

   

    public function getUsers()
    {
        $users = User::get();
        return response()->json(['users' => $users]);
    }

    public function mentionUser(Request $request, Comment $comment)
    {
        $userIds = $request->input('user_ids');

        // Find the mentioned users
        $mentionedUsers = User::whereIn('id', $userIds)->get();

        // Attach mentioned users to the comment
        $comment->mentionedUsers()->attach($mentionedUsers);

        return response()->json(['message' => 'Users mentioned successfully'], 200);
    }
}
