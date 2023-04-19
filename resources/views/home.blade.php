@extends('layouts.app')

@section('title',"Admin HomePage")

@section('customStyle')
            <style type="text/css">
                .custom-modal{   
                  display:none;
                  position: fixed; /* Stay in place */
                  z-index: 1; /* Sit on top */
                  padding-top: 100px; /* Location of the box */
                  left: 0;
                  top: 0;
                  width: 100%; /* Full width */
                  height: 100%; /* Full height */
                  overflow: auto; /* Enable scroll if needed */
                  background-color: rgb(0,0,0); /* Fallback color */
                  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                }
                .modal-content{
                  position: relative;
                  background-color: #fefefe;
                  margin: auto;
                  padding: 0;
                  border: 1px solid #888;
                  width: 59%;
                  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
                  border-radius: 30px;
                }
                .modal-header{
                    padding: 20px;
                    font-size: 17px;
                }

                .modal-body{
                    padding: 3px 73px;
                }
                .form-control{
                border-radius: 20px;
                boder: 1px solid gray;
                }
                .fa-times{
                    cursor:pointer;
                }
                .hidden{
                    display: none;
                }
                thead{
                    background: #8080804a;
                }
                tbody{
                    background:white;
                }
                table th:first-child {
                border-top-left-radius: 10px;
                    }
                table th:last-child {
                border-top-right-radius: 10px;
                    }
                .editQuestion, .deleteQuestion{
                    width: 95%;
                    margin:3px;
                }
            </style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center">ADMIN</h1>
            <div class="text-end m-2">
                <button class="btn btn-secondary rounded add-question-btn">+ Ajouter</button>
            </div>
                    <div  style="overflow-x:auto;">
                    <table class="table table-bordered">
                        <thead class="text-center">
                            <th>#</th>
                            <th>Question</th>
                            <th>Reponse</th>
                            <th>Choix1</th>
                            <th>Choix2</th>
                            <th>Choix3</th>
                            <th class="col-1">Action</th>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            <tr id="row-{{ $question->id }}">
                                <td>{{ $question->id }}</td>
                                <td>{{ $question->content }}</td>
                                @foreach($question->answer as $answer)
                                <td class="text-center">{{ $answer->content }}</td>
                                @endforeach
                                <td>
                                    <button num="{{ $question->id }}" class="editQuestion btn btn-success btn-sm"><i class="fa fa-edit"></i></button>
                                    <button num="{{ $question->id }}" class="deleteQuestion btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="text-center">
                        {{ $questions->onEachSide(5)->links() }}
                    </div>
                </div>
            </div>
            <div class="custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">Ajouter Une Question</div>
                        <div class="modal-close"><i class="fa fa-times modal-close"></i></div>
                    </div>
                    <div class="modal-form-inputs p-3">
                        <div class="col-md-8 m-auto">
                            <div class="form-group">
                                <label>Question:</label>
                                <input type="text" class="form-control" id="questionHolder">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                   <div class="form-group mt-2">
                                        <label>Reponse:</label>
                                        <input type="text" class="form-control" id="answer1Holder">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Choix1:</label>
                                        <input type="text" class="form-control" id="answer2Holder">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-2">
                                        <label>Choix2:</label>
                                        <input type="text" class="form-control" id="answer3Holder">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Choix3:</label>
                                        <input type="text" class="form-control" id="answer4Holder">
                                    </div>
                                </div>
                            </div>
                            <div class="btns col-12 text-end py-3">
                                <button class="btn btn-warning modal-close">Annuler</button>
                                <button class="btn btn-success validate" for="new">Valider</button>
                            </div>
                        </div>
                    </div>
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
var currentQuestionId = 0;

$('.modal-close').on('click', function(){
    $('.custom-modal').hide();
    $('.modal-form-inputs input').val('');
});

$('.add-question-btn').on('click', function(){
    $('.custom-modal').show();
    $('.validate').attr('for','new');
});

$('.editQuestion').on('click', function(){
    $('.validate').attr('for','old');
    var questionId = $(this).attr('num');

    currentQuestionId = questionId;
    $.ajax({
        url: '/getQuestionById',
        type: 'POST',
        dataType: 'JSON',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {questionId:questionId},
    })
    .done(function(data) {
        $('.custom-modal').show();
        $('#questionHolder').val(data.question);
        $('#answer1Holder').val(data.answer1);
        $('#answer2Holder').val(data.answer2);
        $('#answer3Holder').val(data.answer3);
        $('#answer4Holder').val(data.answer4);

    })
    .fail(function(data) {
        console.log("error");
    })
});


$("body").on('click', ".validate[for='new']", function(){

           var question = $('#questionHolder').val();
           var answer1 =  $('#answer1Holder').val();
           var answer2 =  $('#answer2Holder').val();
           var answer3 =  $('#answer3Holder').val();
           var answer4 =  $('#answer4Holder').val();
    $.ajax({
            url: '/addQuestion',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {question:question,answer1:answer1,answer2:answer2,answer3:answer3,answer4:answer4},
        })
        .done(function(data) {
            if (data == "done") {
                $('.custom-modal').hide();
                $('.modal-form input').val('');
            }else{
                alert(data);
            }
        })
        .fail(function(data) {
            console.log("error");
        })

});


$("body").on('click', ".validate[for='old']", function(){

           var question = $('#questionHolder').val();
           var answer1 =  $('#answer1Holder').val();
           var answer2 =  $('#answer2Holder').val();
           var answer3 =  $('#answer3Holder').val();
           var answer4 =  $('#answer4Holder').val();
    $.ajax({
            url: '/editQuestion',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {QId:currentQuestionId,question:question,answer1:answer1,answer2:answer2,answer3:answer3,answer4:answer4},
        })
        .done(function(data) {
            if (data == "done") {
                $('.custom-modal').hide();
                $('.modal-form input').val('');
            }else{
                alert(data);
            }
        })
        .fail(function(data) {
            console.log("error");
        })

});

$("body").on('click', ".deleteQuestion", function(){

    var questionId = $(this).attr('num');

    if (confirm('Are you sure you want to delete')) {
        $.ajax({
            url: '/deleteQuestion/'+questionId,
            type: 'GET',
        })
        .done(function() {
            $('#row-'+questionId).remove();
        })
        .fail(function() {
            console.log("error");
        })
    }

});

</script>



@endsection

