<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Quiz::with('questions')->get();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quiz = Quiz::findOrFail($id);
         return $quiz->load('questions');
    }

     public function evaluate(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        $correctAnswers = 0;
        $totalQuestions = $quiz->questions()->count();

        // Loop through submitted answers
        foreach ($request->answers as $questionId => $answer) {
            // Here we would match the answer with correct answers
            // Assume we have a field in `questions` table to store correct answer for now
            $question = $quiz->questions()->find($questionId);
            if ($question && $question->correct_answer == $answer) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / $totalQuestions) * 100;

        return response()->json([
            'score' => $score,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions
        ]);
    }

}
