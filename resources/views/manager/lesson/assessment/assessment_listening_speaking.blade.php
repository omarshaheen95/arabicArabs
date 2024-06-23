@extends('manager.layout.container')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('editor/css/froala_editor.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/froala_style.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/plugins/code_view.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/plugins/image_manager.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/plugins/image.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/plugins/table.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/plugins/video.css')}}">
    <link rel="stylesheet" href="{{asset('editor/css/plugins/colors.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }
    </style>
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('manager.lesson.index') }}">{{t('Lessons')}}</a>
    </li>
    <li class="breadcrumb-item">
        {{ isset($title) ? $title:'' }}
    </li>
@endpush
@section('title')
     {{t('Lesson').': '.$lesson->name.' - '.$lesson->grade->name}}
@endsection
@section('actions')
    @can('lesson review')
    <a href="{{route('manager.lesson.review', [$lesson->id, 'test'])}}" target="_blank" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-eye"></i>
        {{t('Preview')}}
    </a>
    @endcan
@endsection
@section('content')
    <div class="row">

        <form enctype="multipart/form-data" id="form_information"
              class="form-data"
              action="{{ route('manager.lesson.update_assessment', [$lesson->id, $lesson->lesson_type]) }}"
              method="post">
            @csrf
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="fs-2 mb-2">{{t('General Content')}} :</label>
                        <textarea class="form-control edit" id='edit' style=""
                                  name="content">
                            @if(isset($questions) && count($questions))
                                {{$lesson->content}}
                            @endif
                        </textarea>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex align-items-center my-4">
                    <label class="fs-2">{{t('Questions')}} :</label>

                    <button type="button"
                            class="btn btn-secondary btn-elevate btn-icon-sm add_button ms-auto">
                        <i class="la la-plus"></i>
                        {{t('Add Question')}}
                    </button>
                </div>
                <div class="separator my-3"></div>

                <div class="questions">
                    @if(isset($questions) && count($questions))

                    @foreach($questions as $question)
                        <div class="form-group row align-items-end">
                            <div class="col-8">
                                <label class="mb-1">{{t('Q')}} {{$loop->index+1}}:</label>
                                <input required class="form-control"
                                       name="old_questions[{{$question->id}}]" type="text"
                                       value="{{$question->content}}">
                            </div>
                            <div class="col-1">
                                <label class="mb-1">{{t('Mark')}} :</label>
                                <input type="number" required class="form-control"
                                       name="old_mark[{{$question->id}}]"
                                       value="{{$question->mark}}"/>
                            </div>
                            <div class="col-lg-2">
                                <div class="d-flex">
                                    <label class="mb-2">{{t('Attachment')}} :</label>
                                    @if($question->getFirstMediaUrl('imageQuestion'))
                                        <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                            <a data-id="{{$question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                <i class="la la-close la-2"></i>
                                            </a>
                                            <a href="{{$question->getFirstMediaUrl('imageQuestion')}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                <i class="la la-eye la-2"></i>
                                            </a>
                                        </div>
                                    @endif

                                </div>
                                <input type="file" name="old_attachment[{{$question->id}}]" class="form-control">
                            </div>
                            <div class="col-1 d-flex align-items-center mt-3">
                                <button type="button"
                                        class="btn btn-danger btn-icon btn-block delete_input"><i
                                        class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @else
                        @for($i = 1; $i<=1;$i++)
                        <div class="form-group row align-items-end">
                            <div class="col-8">
                                <label class="mb-1">{{t('Q')}} {{$i}}:</label>
                                <input required class="form-control" name="questions[{{$i}}]" type="text">
                            </div>
                            <div class="col-1">
                                <label class="mb-1">{{t('Mark')}} :</label>
                                <input type="number" required class="form-control"
                                       name="mark[{{$i}}]"
                                />
                            </div>
                            <div class="col-lg-2">
                                <div class="d-flex">
                                    <label class="mb-2">{{t('Attachment')}} :</label>
                                </div>
                                <input type="file" name="attachment[{{$i}}]" class="form-control">
                            </div>
                            <div class="col-1 d-flex align-items-center mt-3">
                                <button type="button"
                                        class="btn btn-danger btn-icon btn-block delete_input"><i
                                        class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>
                        @endfor
                    @endif
                </div>

            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2">{{t('Save')}}</button>
                </div>
            </div>

        </form>


        @endsection

        @section('script')
            <script type="text/javascript" src="{{asset('editor/js/froala_editor.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/align.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/code_beautifier.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/code_view.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/draggable.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/image.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/image_manager.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/link.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/lists.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/paragraph_format.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/paragraph_style.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/table.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/video.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/url.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/entities.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/colors.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/font_size.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/font_family.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/word_paste.min.js')}}"></script>
            <script>
                const CSRF = "{{csrf_token()}}";
                const UPLOAD_ROUTE = "{{route('uploadImageLesson')}}";
            </script>
            <script type="text/javascript" src="{{asset('editor/js/plugins/editor.js')}}"></script>
            <script src="{{asset('assets_v1/js/jquery-validation/dist/jquery.validate.js')}}"></script>
            <script src="{{asset('assets_v1/js/jquery-validation/dist/additional-methods.js')}}"></script>
            @if(app()->getLocale()=='ar')
                <script src="{{asset('assets_v1/js/jquery-validation/dist/localization/messages_ar.js')}}"></script>
            @endif
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.deleteRecord', (function () {
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_a_question_attachment', ':id')}}';
                        url = url.replace(':id', id);
                        $('#delete_attachment_form').attr('action', url);
                    }));

                 $(document).on('click', '.deleteRecord', (function () {
                                        let parent = $(this).parent()
                                        let id = $(this).data("id");
                                        let url = '{{route('manager.lesson.remove_a_question_attachment', ':id')}}';
                                        url = url.replace(':id', id);
                                        showAlert("{{t('Delete Attachment')}}","{{t('Are you sure to for deleting process?')}}",'warning',
                                        true,true,function (callback) {
                                                if (callback){
                                                    showLoadingModal()
                                                    $.ajax({
                                                        url: url,
                                                        type: 'post',
                                                        data: {'_token':"{{csrf_token()}}"},
                                                        success: function(response){
                                                            hideLoadingModal()
                                                            parent.remove()
                                                            toastr.success(response.message);
                                                        },
                                                        error(error){
                                                            hideLoadingModal()
                                                            toastr.error(error.responseJSON.message);
                                                        }
                                                    });
                                                }
                                            })
                                    }));

                 validateAndSubmit('form-data')

                    var x = 1; //Initial field counter is 1
                    var y = 1; //Initial field counter is 1

                    var maxField = 10 //Input fields increment limitation
                    var addButton = $('.add_button'); //Add button selector
                    //Once add button is clicked
                    $(addButton).click(function () {
                        var row_id = $(this).attr('data-id');
                        var wrapper_row = $('.questions'); //Input field wrapper
                        x = wrapper_row.children().length;
                        y = wrapper_row.children().length;
                        //Check maximum number of input fields
                        if (x < maxField) {
                            x++; //Increment field counter
                            y++; //Increment field counter.
                            $(wrapper_row).append(
                                "<div class=\"form-group row\">\n" +
                                "<div class=\"col-8\">\n" +
                                "<label class="mb-2">{{t('Q')}} " + y + ": </label></label>\n" +
                                "<input required type='text' class=\"form-control\" name=\"questions[" + y + "]\">" +
                                "</div>\n" +
                                "<div class=\"col-1\">\n" +
                                "<label class="mb-2">{{t('Mark')}} :</label>\n" +
                                "<input type=\"number\" required name=\"mark[" + y + "]\" class=\"form-control\">\n" +
                                "</div>\n" +
                                "<div class=\"col-2\">\n" +
                                "<label class="mb-2">{{t('Attachment')}} :</label>\n" +
                                "<input type=\"file\" name=\"attachment[" + y + "]\" class=\"form-control\">\n" +
                                "</div>\n" +
                                "<div class=\"col-1 d-flex align-items-center mt-3\">" +
                                "<button type=\"button\" class=\"btn btn-danger btn-icon btn-block delete_input\">"+
                                "<i class=\"fa fa-close\"></i>"+
                                "</button>"+
                                "</div>"+
                                "</div>"

                            ); //Add field html
                        }
                    });
                    //Once remove button is clicked
                    $(document).on('click', '.delete_input', function (e) {
                        e.preventDefault();
                        $(this).parent().parent('div').remove(); //Remove field html
                        // x--;
                    });
                });
            </script>
@endsection
