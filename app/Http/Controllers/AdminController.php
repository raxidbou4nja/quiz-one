<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator; //Use at top of the page
use Illuminate\Http\Request;

use App\Models\question;
use App\Models\Answer;

use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('admin');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addQuestion(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer1' => 'required',
            'answer2' => 'required',
            'answer3' => 'required',
            'answer4' => 'required',
        ]);
 
        /// CHECK IF URL IS INPUTED AND IS MORE THAN TEN CHARS.
        if (!$validator->passes()) 
        {
            return "un champ vide cochez et réessayez"; // ERROR Return
        }

        $QuestionId = Question::insertGetId(['content'=> $request->question,'admin_id' => 1]);
        
        $CorrectAnswerId = Answer::insertGetId(['content'=> $request->answer1,'question_id' => $QuestionId]);

        Answer::insert(['content'=> $request->answer2,'question_id' => $QuestionId]);
        Answer::insert(['content'=> $request->answer3,'question_id' => $QuestionId]);
        Answer::insert(['content'=> $request->answer4,'question_id' => $QuestionId]);

        Question::where('id',$QuestionId)->update(['correct_answer' => $CorrectAnswerId]);

        return "done";




    }


   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editQuestion(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer1' => 'required',
            'answer2' => 'required',
            'answer3' => 'required',
            'answer4' => 'required',
            'QId' => 'required|numeric',
        ]);
 
        /// CHECK IF URL IS INPUTED AND IS MORE THAN TEN CHARS.
        if (!$validator->passes()) 
        {
            return "un champ vide cochez et réessayez"; // ERROR Return
        }

        Question::where('id',$request->QId)->update(['content' => $request->question]);


        $Answer = Answer::where('question_id',$request->QId)->get();

        Answer::where('id',$Answer[0]->id)->update(['content' => $request->answer1]);
        Answer::where('id',$Answer[1]->id)->update(['content' => $request->answer2]);
        Answer::where('id',$Answer[2]->id)->update(['content' => $request->answer3]);
        Answer::where('id',$Answer[3]->id)->update(['content' => $request->answer4]);

        return 'done';




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteQuestion($id)
    {        
        $deleteAnswer = Answer::where('question_id',$id)->delete();
        $deleteQuestion = Question::where('id',$id)->delete();
        if ($deleteAnswer AND $deleteQuestion) {
            return "ok";
        }
    }


    public function addQuestionsFromApi()
    {       



        // if (IsAdmin()) {
        //  return redirect()->back(); 
        // }



exit;
$content = file_get_contents('https://the-trivia-api.com/api/questions/');

$contentArr =  json_decode($content);

foreach ($contentArr as $qa) {
    


$question = $qa->question;
$correctAnswer = $qa->correctAnswer;
$incorrectAnswers1 = $qa->incorrectAnswers[0];
$incorrectAnswers2 = $qa->incorrectAnswers[1];
$incorrectAnswers3 = $qa->incorrectAnswers[2];



        $QuestionId = Question::insertGetId(['content'=> $question,'admin_id' => 1]);
        
        $CorrectAnswerId = Answer::insertGetId(['content'=> $correctAnswer,'question_id' => $QuestionId]);

        Answer::insert(['content'=> $incorrectAnswers1,'question_id' => $QuestionId]);
        Answer::insert(['content'=> $incorrectAnswers2,'question_id' => $QuestionId]);
        Answer::insert(['content'=> $incorrectAnswers3,'question_id' => $QuestionId]);

        Question::where('id',$QuestionId)->update(['correct_answer' => $CorrectAnswerId]);







}
    }



}
