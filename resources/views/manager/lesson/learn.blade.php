{{--Dev Omar Shaheen
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
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($title) ? $title:'' }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ route('manager.lesson.update_learn', $lesson->id) }}"
                      method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">محتوى صوتي

                                        @if($lesson->getFirstMediaUrl('audioLessons'))
                                            <a href="{{$lesson->getFirstMediaUrl('audioLessons')}}" class="kt-font-warning" target="_blank">استعراض</a>
                                            |
                                            <a href="#deleteLessonAudioModel" data-id="{{$lesson->id}}" data-toggle="modal" data-target="#deleteLessonAudioModel"  class="kt-font-warning deleteLessonAudioRecord" target="_blank">حذف</a>

                                        @endif
                                    </label>
                                    <div class="col-lg-9 col-xl-6 col-form-labe">
                                        <input class="form-control" name="audio" type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label>المحتوى </label>
                                        <textarea class="form-control summernote edit" id='edit' style="margin-top: 30px;" name="content">{{ isset($lesson) ? $lesson->content : old("content") }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">حفظ</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteLessonAudioModel" tabindex="-1" role="dialog" aria-labelledby="deleteLessonAudioModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="remove_lesson_audio_form">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <h5>هل أنت متأكد من حذف السجل المحدد ؟</h5>
                        <br />
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
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{{--    {!! JsValidator::formRequest(\App\Http\Requests\Manager\LessonRequest::class, '#form_information') !!}--}}
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
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
        (function () {
            const editorInstance = new FroalaEditor('.edit', {
                key: "aLF3c1B10D6D5D3F2F2C-7jjhB6iB-11kiC-7A3md1C-13mD6F5F4B3E1B9A6C3F5F6==",
                fontFamilySelection: true,
                heightMin: 500,
                paragraphStyles: {
                    leftDirection: 'Left Direction',
                    rightDirection: 'Right Direction'
                },
                toolbarButtons: {
                    'moreText': {
                        'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
                    },
                    'moreParagraph': {
                        'buttons': ['alignLeft', 'alignCenter', 'paragraphStyle',  'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat',  'formatOLSimple','lineHeight', 'outdent', 'indent', 'quote']
                    },
                    'moreRich': {
                        'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
                    },
                    'moreMisc': {
                        'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
                    }
                },
                fontFamily: {
                    'Arial,Helvetica,sans-serif': 'Arial',
                    'Georgia,serif': 'Georgia',
                    'Impact,Charcoal,sans-serif': 'Impact',
                    'Tahoma,Geneva,sans-serif': 'Tahoma',
                    "'Times New Roman',Times,serif": 'Times New Roman',
                    'Verdana,Geneva,sans-serif': 'Verdana'
                },
                enter: FroalaEditor.ENTER_P,
                placeholderText: null,

                colorsStep: 6,
                colorsText: [
                    '#15E67F', '#E3DE8C', '#D8A076', '#D83762', '#76B6D8', 'REMOVE',
                    '#1C7A90', '#249CB8', '#4ABED9', '#FBD75B', '#FBE571', '#FFFFFF', '#000000'
                ],
                toolbarButtonsXS: [['undo', 'redo'], ['bold', 'italic', 'underline']],
                fontSize: ['8', '10', '12', '14', '18', '22', '24', '28', '32', '36', '40', '44', '48', '52', '56', '60', '96'],
                // Set the image upload parameter.
                imageUploadParam: 'imageFile',

                // Set the image upload URL.
                imageUploadURL: '{{route('uploadImageLesson')}}',

                // Additional upload params.
                imageUploadParams: {
                    "_token": "{{ csrf_token() }}",
                },

                // Set request type.
                imageUploadMethod: 'POST',

                // Set max image size to 5MB.
                imageMaxSize: 5 * 1024 * 1024,

                // Allow to upload PNG and JPG.
                imageAllowedTypes: ['jpeg', 'jpg', 'png'],

                events: {
                    initialized: function () {
                        const editor = this
                        this.el.closest('form').addEventListener('submit', function (e) {
                            console.log(editor.$oel.val());
                        })
                    },
                    'image.beforeUpload': function (images) {

                        console.log("// Return false if you want to stop the image upload.");
                    },
                    'image.uploaded': function (response) {

                        console.log("// Image was uploaded to the server.");
                    },
                    'image.inserted': function ($img, response) {

                        console.log("// Image was inserted in the editor.");
                    },
                    'image.replaced': function ($img, response) {

                        console.log("// Image was replaced in the editor.");
                    },
                    'image.error': function (error, response) {
                        // Bad link.
                        if (error.code == 1) {
                            console.log(error + " - " + error.code);
                        }

                        // No link in upload response.
                        else if (error.code == 2) {
                            console.log(error + " - " + error.code);
                        }

                        // Error during image upload.
                        else if (error.code == 3) {
                            console.log(error + " - " + error.code);
                        }

                        // Parsing response failed.
                        else if (error.code == 4) {
                            console.log(error + " - " + error.code);
                        }

                        // Image too text-large.
                        else if (error.code == 5) {
                            console.log(error + " - " + error.code);
                        }

                        // Invalid image type.
                        else if (error.code == 6) {
                            console.log(error + " - " + error.code);
                        }

                        // Image can be uploaded only to same domain in IE 8 and IE 9.
                        else if (error.code == 7) {
                            console.log(error + " - " + error.code);
                        }

                        // Response contains the original server response to the request if available.
                    }
                }
            })
        })()
    </script>
    <script>
        $(document).on('click','.deleteLessonAudioRecord',(function(){
            var id = $(this).data("id");
            var url = '{{ route("manager.lesson.remove_lesson_audio", ":id") }}';
            url = url.replace(':id', id );
            $('#remove_lesson_audio_form').attr('action',url);
        }));
    </script>
@endsection
