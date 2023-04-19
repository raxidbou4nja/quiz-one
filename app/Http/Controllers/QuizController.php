<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;//Use at top of the page

use App\Models\Question;
use App\Models\Answer;

class QuizController extends Controller
{   

    const NUMBER_OF_QUESTIONS = 5;
    const SCORE_OF_CORRECT_ANSWER = 10;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRandomQuestion(Request $request)
    {        
       if ($request->questionId == 0) 
       {
        $question = Question::inRandomOrder()->limit(5)->get();
       }
       else
       {
         $question = Question::where('id',$request->questionId)->get();
       }

        $idArray = $question->pluck('id')->toArray();

        $answers_array = Answer::where('question_id','=',$question[0]->id)->get();

        $answers = iterator_to_array($answers_array);
        shuffle($answers);

        $data['quizBody'] = '<div class="question"><h4 num="'.$question[0]->id.'">'.$question[0]->content.'</h4>
        </div>
        <div class="answers text-center">
            <button num="'.$answers[0]->id.'" class="answer">
                '.$answers[0]->content.'
            </button>
            <button num="'.$answers[1]->id.'" class="answer">
                '.$answers[1]->content.'
            </button>
            <button num="'.$answers[2]->id.'" class="answer">
                '.$answers[2]->content.'
            </button>
            <button num="'.$answers[3]->id.'" class="answer">
                '.$answers[3]->content.'
            </button>
        </div>';
        $data['limit'] = self::NUMBER_OF_QUESTIONS;


        $data['arr'] = json_encode($idArray);


        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCorrectAnswer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'questionId' => 'required|numeric|min:1',
            'answerId' => 'required|numeric|min:1',
        ]);

        /// CHECK IF URL IS INPUTED AND IS MORE THAN TEN CHARS.
        if (!$validator->passes()) 
        {
            return false; // ERROR Return
        }


        $getQuestion = Question::where('id', '=', $request->questionId)->get();

        if ($getQuestion[0]->correct_answer == $request->answerId) {
            return self::SCORE_OF_CORRECT_ANSWER;
        } else {
            return "0";
        }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQuestionById(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'questionId' => 'required|numeric|min:1',
        ]);

        /// CHECK IF URL IS INPUTED AND IS MORE THAN TEN CHARS.
        if (!$validator->passes()) 
        {
            return false; // ERROR Return
        }


        $question = Question::where('id', '=', $request->questionId)->get();

        $data['question'] = $question[0]->content;
        $data['answer1'] = $question[0]->answer[0]->content;
        $data['answer2'] = $question[0]->answer[1]->content;
        $data['answer3'] = $question[0]->answer[2]->content;
        $data['answer4'] = $question[0]->answer[3]->content;

        return json_encode($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
