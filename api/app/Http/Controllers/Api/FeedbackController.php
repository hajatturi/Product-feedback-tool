<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Comment;

class FeedbackController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback = Feedback::latest()->with('user')->paginate(10);
        return FeedbackResource::collection($feedback);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:bug,feature,improvement',
        ]);

        // Create new feedback entry
        $feedback = Feedback::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category' => $validatedData['category'],
            'user_id' => auth()->id(), // Assuming user authentication is implemented
        ]);
        return new FeedbackResource($feedback);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $Feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        $feedbackId = $feedback->id;
        $feedback = Feedback::with('user')->where('user_id', $feedback->user_id)->first();

        $comments = Comment::with('user')->where('feedback_id', $feedbackId)->get();
        $feedback->comments = $comments;

        return new FeedbackResource($feedback);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $Feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $Feedback)
    {
        $Feedback->delete();
        return new FeedbackResource($Feedback);
    }

    /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($title)
    {
        $feedback = Feedback::where('name', 'like', '%' . $title . '%')->get();
        return FeedbackResource::collection($feedback);
    }
}
