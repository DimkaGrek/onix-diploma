<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Product;
use App\Models\Question;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('product_id')){
            $product = Product::find($request->product_id);
            $product->load('questions', 'questions.answers')->paginate(15);
            return $product;
        }
        else return Question::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = new Question;
        $question->user_id = Auth::user()->id;
        $question->product_id = $request->product_id;
        $question->text = $request->text;
        $question->save();
        return $question;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return $question->load('answers');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $question->text = $request->text;
        $question-save();
        return $question;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(['message' => 'question deleted']);
    }

    public function createAnswer(Question $question, Request $request){
        $answer = new Answer;
        $answer->user_id = Auth::user()->id;
        $answer->question_id = $question->id;
        $answer->text = $request->text;
        $answer->save();
        return $answer;
    }

    public function updateAnswer(Answer $answer, Request $request){
        if ($answer->user_id != Auth::user()->id) return response()->json(['error' => 'Not authorize'], 401);
        $answer->text = $request->text;
        $answer->save();
        return $answer;
    }

    public function deleteAnswer(Answer $answer) {
        if ($answer->user_id != Auth::user()->id) return response()->json(['error' => 'Not authorize'], 401);
        $answer->delete();
        return response()->json(['message' => 'answer deleted']);
    }
}
