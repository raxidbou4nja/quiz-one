@extends('layouts.app')

@section('title', 'Quiz Homepage')

@section('customStyle')
<style type="text/css">
body{
        font-family: 'OCR A';
}
.welcomePage h1 {
    font-size: 120px;
}

.welcomePage button {
    width: 140px;
    font-size: 26px;
    border-radius: 42px;
    margin-top:10px;
}

.answer{
    padding: 7px;
    margin: 5px 0px;
    border: 1px solid gray;
    cursor: pointer;
    font-size: 18px;
    width: 100%;
    background: white;
}
.answer:hover {
    background: #607d8b26;
}

.correct-answer, .correct-answer:hover{
    background:#4caf506b;
} 

.incorrect-answer, .incorrect-answer:hover{
    background:#ff00006b;
} 

.nextBtn{
    text-align: right;
}
.nextBtn button, .resultHolder button {
    background:#4dcb52;
    color: white;
}
.nextBtn button:hover, .resultHolder button:hover{
    background:#45b84a;
    color: white;
}

.resultHolder{
    padding: 60px 0;
}
</style>
@endsection

@section('content')
<div class="container">
<div class="row">
    <div class="welcomePage text-center">
        <h1 class="text-center">Quiz</h1>
        <button class="btn btn-info playBtn">Play</button>
    </div>
    <div class="quizHolder col-md-6 m-auto" style="display:none">
        <h5>Question <span class="counter"></span>:</h5>
        <div class="holderBody"></div>
        <div class="nextBtn">
                <button class="btn nextQuestion">Suivant🡪</button>
        </div>
    </div>
    <div class="resultHolder col-md-6 m-auto text-center" style="display:none">
        <h4>Votre Score est:</h4>
        <h5><span class="resultScore">0</span></h5>
        <button class="btn playBtn">⟲ Replay</button>
    </div>
</div>
</div>
@endsection

@section('customJs')

<script
  src="https://code.jquery.com/jquery-3.6.4.min.js"
  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
  crossorigin="anonymous"></script>
  
<script type="text/javascript">

var score = 0;
var questionCounter = 0;
var questionsLimit = 0;
var idArray = [];


$('.playBtn').on('click', function() {

    score = 0;
    questionCounter = 0;
    $('.welcomePage').hide();
    $('.resultHolder').hide();
    $('.nextQuestion').html('Suivant🡪');
    getQuestion();
    disableNextBtn();
});




$('.nextQuestion').on('click', function(e) {
    if (questionCounter >= questionsLimit){
        showResult();
        return false;
    }
    getQuestion(idArray[questionCounter]);
    disableNextBtn();
});

$('body').on('click', '.answer',  function(e) {
    var thisAnswer = $(this);
    var answerId = $(this).attr('num');
    var questionId = $('.question h4').attr('num');

    $('.answer').attr('disabled', true).removeAttr('num');

    if (questionsLimit == (questionCounter)) {
        getResultBtn();
    }

    if (!answerId) {
        return alert('Error Accured! Please try again later');
    }

    $.ajax({
        url: '/checkCorrectAnswer',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {questionId: questionId, answerId:answerId},
    })
    .done(function(data) {
        if (data > 0) 
        {
            thisAnswer.addClass('correct-answer');
            score += parseInt(data);
            enableNextBtn();
        }
        else
        {
            thisAnswer.addClass('incorrect-answer');
            enableNextBtn();
        }
    })
    .fail(function() {
        alert('Error Happened! Please try again');
    })

});


function showResult(){
    $('.holderBody').html('');
    $('.quizHolder').hide();
    $('.resultHolder').show();
    $('.resultScore').html(score);

}


function enableNextBtn(){
    $('.nextQuestion').removeAttr('disabled');
}

function disableNextBtn(){
    $('.nextQuestion').attr('disabled', true);
}

function getResultBtn(){
    $('.nextQuestion').html('Get Result!');
}

function getQuestion(num = 0){
    questionCounter += 1;
    $.ajax({
        url: '/getQuestion',
        type: 'POST',
        dataType: 'JSON',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {questionId:num},
    })
    .done(function(data) {
        $('.holderBody').html(data.quizBody);
        questionsLimit = data.limit;
        $('.counter').html(questionCounter);
        $('.quizHolder').show();
        if (num == 0) {
            idArray = JSON.parse(data.arr);
        }
    })
    .fail(function() {
        alert('Error Happened! Please try again');
    })
}



</script>



@endsection
