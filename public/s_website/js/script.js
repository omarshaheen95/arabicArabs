
  $(window).on('load', function () {
    $(".loader").fadeOut("slow");
  });


if($('html').height()<=$(window).height()){
  $('body').height($(window).height());
}


  $(".answer-box input").change(function () {
      $rr = $(this).attr('question-grop-name');
      $('input[question-grop-name = ' + $rr + ']').parent('label').removeClass('active');
      $(this).parent('label').addClass('active');
      var ans = $(this).attr('data-ans');
      $('audio').each(function () {
          this.pause(); // Stop playing
          this.currentTime = 0; // Reset time
      });
      if (ans === 'true') {
          $('#ture_audio')[0].currentTime = 0;
          $('#ture_audio')[0].play();
          console.log('play');
      } else {
          $('#false_audio')[0].currentTime = 0;
          $('#false_audio')[0].play();
      }
  });

  const equals = (a, b) =>
      a.length === b.length &&
      a.every((v, i) => v === b[i]);

  $('.checkAnswer').click(function (){
      var btn = $(this);
      var btnKey = btn.attr('data-id');

      var result_box = $('#match_'+btnKey+'_result li');
      var answer_box = $('#match_'+btnKey+'_answer li');

      var answer_array = [];
      var answer_array_sorted = [];

      if (result_box.length > 0)
      {
          $('#false_audio')[0].currentTime = 0;
          $('#false_audio')[0].play();
          console.log('play');
      }else{
          answer_box.each(function( index ) {
              answer_array_sorted.push($( this ).attr('data-id'));
              answer_array.push($( this ).attr('data-id'));
          });
          answer_array_sorted.sort(function(a, b) {
              return a - b;
          });

          if (equals(answer_array, answer_array_sorted))
          {
              // console.log(answer_array);
              // console.log(answer_array_sorted);
              $('#ture_audio')[0].currentTime = 0;
              $('#ture_audio')[0].play();
          }else{
              $('#false_audio')[0].currentTime = 0;
              $('#false_audio')[0].play();
              // console.log(answer_array);
              // console.log(answer_array_sorted);
          }
      }

      // console.log(result_box.length);
      // console.log(answer_box.length);
  });
  $('.checkSort').click(function (){
      var btn = $(this);
      var btnKey = btn.attr('data-id');

      var answer_box = $('#sort_'+btnKey+'_answer li');
      var result_box = $('#sort_'+btnKey+'_result li');

      var answer_array = [];
      var answer_array_sorted = [];

      if (answer_box.length > 0)
      {
          $('#false_audio')[0].currentTime = 0;
          $('#false_audio')[0].play();
          // console.log('play');
      }else{
          result_box.each(function( index ) {
              answer_array_sorted.push($( this ).attr('data-id'));
              answer_array.push($( this ).attr('data-id'));
          });
          answer_array_sorted.sort(function(a, b) {
              return a - b;
          });
          console.log(answer_array);
          console.log(answer_array_sorted);

          if (equals(answer_array, answer_array_sorted))
          {
              // console.log(answer_array);
              // console.log(answer_array_sorted);
              $('#ture_audio')[0].currentTime = 0;
              $('#ture_audio')[0].play();
          }else{
              $('#false_audio')[0].currentTime = 0;
              $('#false_audio')[0].play();
              // console.log(answer_array);
              // console.log(answer_array_sorted);
          }
      }

      // console.log(result_box.length);
      // console.log(answer_box.length);
  });


  $(document).on('click','.sortable1 li', function (){
      var ele = $(this);
      var next_answer_box_length = ele.parent().parent().parent().find('.sortable2 li').length+1;
      var next_answer_box = ele.parent().parent().parent().find('.sortable2');
      ele.find('input').val(next_answer_box_length);
      ele.clone().appendTo(next_answer_box);
      ele.remove();
  });

  $(document).on('click','.sortable2 li', function (){
      console.log('test');
      var ele = $(this);
      var next_answer_box = ele.parent().parent().parent().parent().parent().parent().parent().find('.sortable1');
      ele.find('input').val("");
      ele.clone().appendTo(next_answer_box);
      ele.remove();
  });

$('#questionNumber').html($('.question-list .question-item.active').attr('id'));
$('#numberQuestions').html($('.question-list .question-item').length);

$("#nextQuestion").click(function(){

  if($('.question-list .question-item').length -1 >= $('.question-list .question-item.active').attr('id')){

  $questionNumber1 = $('.question-list .question-item.active').attr('id');
  $questionNumber1 = parseInt($questionNumber1);
  $questionNumberNext = $questionNumber1 + 1;
  // alert($questionNumberNext);
  $('.question-list .question-item').removeClass('active');
  $('#'+$questionNumberNext).addClass('active');
  $('#questionNumber').html($('.question-list .question-item.active').attr('id'));

  if ($('.question-list .question-item').length == $('.question-list .question-item.active').attr('id')){
    $('.endExam').removeClass('d-none');
    $('#nextQuestion').addClass('d-none');
  }

  $('#questionListLink .questionListLinkItem').removeClass('active');
  $('#questionListLink [data-id='+$questionNumberNext+']').addClass('active');

}
else{
  alert('There are no questions');
}

if($('.question-list .question-item.active').attr('id') != 0 ){
$('#previousQuestion').removeClass('d-none');
}

});
$("#previousQuestion").click(function(){

  if($('.question-list .question-item.active').attr('id') > 1 ){

  $questionNumber2 = $('.question-list .question-item.active').attr('id');
  $questionNumber2 = parseInt($questionNumber2);
  $questionNumberPrevious = $questionNumber2 - 1;

  // alert($questionNumberPrevious);
  $('.question-list .question-item').removeClass('active');
  $('#'+ $questionNumberPrevious).addClass('active');
  $('#questionNumber').html($('.question-list .question-item.active').attr('id'));

  if ($('.question-list .question-item').length != $('.question-list .question-item.active').attr('id')){
    $('.endExam').addClass('d-none');
    $('#nextQuestion').removeClass('d-none');
  }


  $('#questionListLink .questionListLinkItem').removeClass('active');
  $('#questionListLink [data-id='+$questionNumberPrevious+']').addClass('active');

}

if($('.question-list .question-item.active').attr('id') == 1 ){
  $('#previousQuestion').addClass('d-none');
  }

});

$iii = 0;
$(".question-list .question-item").each(function(){
  $iii++;
  $('#questionListLink').append('<li class="list-inline-item"><div class="bg-light py-2 px-3 questionListLinkItem" data-id="'+ $iii +'">'+ $iii +'</div></li>');
});

$('#questionListLink [data-id=1]').addClass('active');

$(document).on('click','.questionListLinkItem', function(){
  $goId = $(this).attr('data-id');
  $('.question-list .question-item').removeClass('active');
  $('#'+$goId).addClass('active');

  $('#questionNumber').html($('.question-list .question-item.active').attr('id'));

  $('#questionListLink .questionListLinkItem').removeClass('active');
  $(this).addClass('active');

  if ($('.question-list .question-item').length == $('.question-list .question-item.active').attr('id')){
    $('.endExam').removeClass('d-none');
    $('#nextQuestion').addClass('d-none');
  }else{
    $('.endExam').addClass('d-none');
    $('#nextQuestion').removeClass('d-none');
  }

  if($('.question-list .question-item.active').attr('id') == 1 ){
    $('#previousQuestion').addClass('d-none');
  }else{
    $('#previousQuestion').removeClass('d-none');
  }

});





  $(document).ready(function() {
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $(input).parent().parent().children('img').show();
                  $(input).parent().parent().children('img').attr('src', e.target.result);
              };
              reader.readAsDataURL(input.files[0]);
          }
      }
      $(".imgInp").change(function () {
          readURL(this);
      });
  });
