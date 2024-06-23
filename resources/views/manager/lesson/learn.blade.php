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
        <a href="{{ route('manager.lesson.index') }}">{{t('Lesson')}}</a>
    </li>
    <li class="breadcrumb-item">
        {{ isset($title) ? $title:'' }}
    </li>
@endpush
@section('title')
    {{ isset($title) ? $title:'' }}
@endsection
@section('actions')
    @can('lesson review')
        <a href="{{route('manager.lesson.review', [$lesson->id, 'learn'])}}" target="_blank" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-eye"></i>
            {{t('Preview')}}
        </a>
    @endcan
@endsection
@section('content')
    <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
          action="{{ route('manager.lesson.update_learn', $lesson->id) }}"
          method="post">
        {{ csrf_field() }}
            <div class="row">
                <label class="col-12 mb-3 fs-2">{{t('Voice')}} :</label>
                <div class="col-3">
                    <input class="form-control" name="audio" type="file">
                </div>
                @if($lesson->getFirstMediaUrl('audioLessons'))
                <div class="col-9 row">
                    <audio class="col-6" style="max-height: 45px" src="{{$lesson->getFirstMediaUrl('audioLessons')}}" controls></audio>
                    <a class="btn btn-danger btn-icon deleteLessonAudioRecord w-50px" data-id="{{$lesson->id}}"><i class="fa fa-times"></i>
                    </a>
                </div>
                @endif
                <div class="separator separator-dashed my-5"></div>
            </div>

            <div class="row">
                <label class="col-12 mb-3 fs-2">{{t('Videos')}} :</label>
                @foreach($lesson->getMedia('videoLessons') as $video)
                    <div class="col-4 d-flex flex-column">
                        <label class="fw-bold">{{$loop->index+1}} :</label>
                        <video style="width: 100%" src="{{$video->getUrl()}}" controls></video>
                        <div class="d-flex gap-2 mt-2">
                            <input type="file" name="old_videos[{{$video->id}}]" class="form-control">
                            <a class="btn btn-danger btn-icon deleteRecord w-50px" data-id="{{$video->id}}"><i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>

                @endforeach
                <div class="separator separator-dashed my-5"></div>
                <div class="row">
                    <a type="button" id="add_label_new_video" class="btn btn-secondary add_button fw-bold">
                       <i class="fa fa-upload"></i> {{t('Add New Video')}}
                    </a>
                    <div id="videos_area" class="row">

                    </div>
                    <div class="separator separator-dashed my-3"></div>

                </div>

            </div>
            <div class="form-group row mt-5">
                <div class="col-lg-12">
                    <label class="col-12 mb-3 fs-2">{{t('Content')}} :</label>
                    <textarea class="form-control summernote edit" id='edit'
                              style="margin-top: 30px;"
                              name="content">{{ isset($lesson) ? $lesson->content : old("content") }}</textarea>
                </div>
            </div>
        <div class="row my-5">
            <div class="separator separator-content my-4"></div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit"
                        class="btn btn-primary mr-2">{{isset($lesson)?t('Update'):t('Save')}}</button>
            </div>
        </div>
    </form>

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
                key: "uXD2lA5D6C4F4G3A3konmA2A-9oC-7H-7ibC4bvddtD3jefpF1F1E1G4F1C11B8C2E5D3==",
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
                        'buttons': ['alignLeft', 'alignCenter', 'paragraphStyle', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'formatOLSimple', 'lineHeight', 'outdent', 'indent', 'quote']
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
        $(document).on('click', '.deleteLessonAudioRecord', (function () {
            let parent = $(this).parent()
            let id = $(this).data("id");
            let url = '{{ route("manager.lesson.remove_lesson_audio", ":id") }}';
            url = url.replace(':id', id);
            showAlert("{{t('Delete Voice')}}","{{t('Are you sure to for deleting process?')}}",'warning',
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

        $(document).on('click', '.deleteRecord', (function () {
            let parent = $(this).parent().parent()
            let id = $(this).data("id");
            let url = '{{route('manager.lesson.remove_video_attachment', ':id')}}';
            url = url.replace(':id', id);
            showAlert("{{t('Delete Video')}}","{{t('Are you sure to for deleting process?')}}",'warning',
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

        $(document).ready(function () {
            var x = 1; //Initial field counter is 1
            var y = 1; //Initial field counter is 1

            var maxField = 10 //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            //Once add button is clicked
            $(addButton).click(function () {
                var row_id = "videos_area";
                var wrapper_row = $('#videos_area'); //Input field wrapper
                x = wrapper_row.children().length;
                y = wrapper_row.children().length;
                //Check maximum number of input fields
                if (x < maxField) {
                    x++; //Increment field counter
                    y++; //Increment field counter.
                    $(wrapper_row).append(
                        "<div class=\"col-lg-4 mt-3\">\n" +
                        "<label class="mb-2">{{t('Video')}}  " + y + " : <a href='#' class='text-danger delete_input'>{{t('Delete')}}</a></label>\n" +
                        "<input required class=\"form-control\" name=\"videos[]\" type=\"file\">\n"
                    ); //Add field html
                }
            });
            //Once remove button is clicked
            $(document).on('click', '.delete_input', function (e) {
                e.preventDefault();
                $(this).parent().parent('div').remove(); //Remove field html
                // x--;
            });
        })
    </script>
@endsection
