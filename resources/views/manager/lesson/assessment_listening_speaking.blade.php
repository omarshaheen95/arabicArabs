{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
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
    <style>
        .form-group label {
            font-size: 14px;
            font-weight: 400;
            color: #31ab3f;
        }
    </style>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.lesson.index') }}">الدروس</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($title) ? $title:'' }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">الدرس : {{$lesson->name}}
                            - {{$lesson->grade->name}}</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button"
                                        class="btn btn-danger btn-elevate btn-icon-sm add_button">
                                    <i class="la la-plus"></i>
                                    إضافة سؤال
                                </button>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ route('manager.lesson.update_assessment', [$lesson->id, $lesson->lesson_type]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        @if(isset($questions) && count($questions))
                                            @php
                                                $i = 1;
                                            @endphp
                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <label>محتوى عام :</label>
                                                    <textarea class="form-control edit" id='edit' style=""
                                                              name="content">{{$lesson->content}}</textarea>
                                                </div>
                                            </div>
                                            <div class="questions">
                                            @foreach($questions as $question)



                                                    <div class="form-group row">
                                                        <div class="col-lg-12">
                                                            <label>س {{$i}}: <a href='#'
                                                                                class='kt-font-warning delete_input'>حذف</a></label>
                                                            <input required class="form-control"
                                                                   name="old_questions[{{$question->id}}]" type="text"
                                                                   value="{{$question->content}}">
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label>الدرجة :</label>
                                                            <input type="number" required class="form-control"
                                                                   name="old_mark[{{$question->id}}]"
                                                                   value="{{$question->mark}}"/>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label>مرفق
                                                                : @if($question->getFirstMediaUrl('imageQuestion'))
                                                                    <a
                                                                        href="{{$question->getFirstMediaUrl('imageQuestion')}}"
                                                                        class="kt-font-warning"
                                                                        target="_blank">استعراض</a>  |
                                                                    <a href="#deleteModel" data-id="{{$question->id}}"
                                                                       data-toggle="modal" data-target="#deleteModel"
                                                                       class="text-warning deleteRecord">(حذف)
                                                                    </a>  @endif</label>
                                                            <input type="file"
                                                                   name="old_attachment[{{$question->id}}]"
                                                                   class="form-control">
                                                        </div>
                                                    </div>

                                                @php
                                                    $i ++;
                                                @endphp
                                            @endforeach
                                            </div>
                                        @else
                                            @for($i = 1; $i<=1;$i++)
                                                <div class="form-group row">
                                                    <div class="col-lg-12">
                                                        <label>محتوى عام :</label>
                                                        <textarea required class="form-control edit" id='edit'
                                                                  name="content"></textarea>
                                                    </div>
                                                </div>
                                                <div class="questions">

                                                    <div class="form-group row">
                                                        <div class="col-lg-7">
                                                            <label>س {{$i}}:</label>
                                                            <input required class="form-control"
                                                                   name="questions[{{$i}}]"
                                                                   type="text">
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <label>الدرجة :</label>
                                                            <input type="number" required name="mark[{{$i}}]"
                                                                   class="form-control">
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label>مرفق :</label>
                                                            <input type="file" name="attachment[{{$i}}]"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_attachment_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <h5>هل أنت متأكد من حذف السجل المحدد ؟</h5>
                            <br/>
                            <p>حذف السجل الحالي يؤدي لحذف السجلات المرتبطة به .</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-warning">حذف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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

            <script>
                $(document).ready(function () {
                    $(document).on('click', '.deleteRecord', (function () {
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_a_question_attachment', ':id')}}';
                        url = url.replace(':id', id);
                        $('#delete_attachment_form').attr('action', url);
                    }));
                    $(document).on('click', '.delete_old_input', (function () {
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_a_sort_word', ':id')}}';
                        url = url.replace(':id', id);
                        $('#delete_sort_word_form').attr('action', url);
                    }));
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
                                "<div class=\"col-lg-7\">\n" +
                                "<label>س " + y + ": <a href='#' class='kt-font-warning delete_input'>حذف</a></label></label>\n" +
                                "<input required type='text' class=\"form-control\" name=\"questions[" + y + "]\">" +
                                "</div>\n" +
                                "<div class=\"col-lg-2\">\n" +
                                "<label>الدرجة :</label>\n" +
                                "<input type=\"number\" required name=\"mark[" + y + "]\" class=\"form-control\">\n" +
                                "</div>\n" +
                                "<div class=\"col-lg-3\">\n" +
                                "<label>مرفق :</label>\n" +
                                "<input type=\"file\" name=\"attachment[" + y + "]\" class=\"form-control\">\n" +
                                "</div>\n" +
                                "</div>"
                            ); //Add field html
                        }
                    });
                    //Once remove button is clicked
                    $(document).on('click', '.delete_input', function (e) {
                        e.preventDefault();
                        $(this).parent().parent().parent('div').remove(); //Remove field html
                        // x--;
                    });
                });
            </script>
@endsection
