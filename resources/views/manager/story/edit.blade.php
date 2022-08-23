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
            <a href="{{ route('manager.story.index') }}">القصص</a>
        </li>
        <li class="breadcrumb-item">
            {{ $title }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ $title }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ isset($story) ? route('manager.story.update', $story->id): route('manager.story.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($story))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">صورة</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">رفع صورة</button>
                                                    <input name="image" class="imgInp" id="imgInp" type="file" />
                                                </div>
                                                <img id="blah" @if(!isset($story) || is_null($story->image)) style="display:none" @endif src="{{ isset($story) && !is_null($story->image)  ? $story->image:'' }}" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">الاسم</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name" type="text" value="{{ isset($story->name) ? $story->name : old("name") }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">الصف</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <select class="form-control" name="grade">
                                                <option value="1" {{isset($story) && $story->grade == 1 ? 'selected':''}}>Grade 1</option>
                                                <option value="2" {{isset($story) && $story->grade == 2 ? 'selected':''}}>Grade 2</option>
                                                <option value="3" {{isset($story) && $story->grade == 3 ? 'selected':''}}>Grade 3</option>
                                                <option value="4" {{isset($story) && $story->grade == 4 ? 'selected':''}}>Grade 4</option>
                                                <option value="5" {{isset($story) && $story->grade == 5 ? 'selected':''}}>Grade 5</option>
                                                <option value="6" {{isset($story) && $story->grade == 6 ? 'selected':''}}>Grade 6</option>
                                                <option value="7" {{isset($story) && $story->grade == 7 ? 'selected':''}}>Grade 7</option>
                                                <option value="8" {{isset($story) && $story->grade == 8 ? 'selected':''}}>Grade 8</option>
                                                <option value="9" {{isset($story) && $story->grade == 9 ? 'selected':''}}>Grade 9</option>
{{--                                                <option value="10" {{isset($story) && $story->grade == 10 ? 'selected':''}}>Grade 10</option>--}}
{{--                                                <option value="11" {{isset($story) && $story->grade == 11 ? 'selected':''}}>Grade 11</option>--}}
{{--                                                <option value="12" {{isset($story) && $story->grade == 12 ? 'selected':''}}>Grade 12</option>--}}
                                            </select>
                                        </div>
                                    </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">فيديو
                                        @if(isset($story) && $story->video)
                                            <br />
                                        <a href="{{$story->video}}" class="text-warning">معاينة</a>
                                        @endif
                                    </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="video" type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">المحتوى
                                    </label>
                                    <div class="col-lg-9">
                                        <textarea class="form-control summernote edit" id='edit' style="margin-top: 30px;" name="content">{{ isset($story->content) ? $story->getOriginal('content') : old("content") }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">تفعيل</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" {{isset($story) && $story->active ? 'checked':''}} value="1" name="active">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger">{{ isset($story) ? "تحديث":"حفظ" }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script> <script type="text/javascript"
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\StoryRequest::class, '#form_information'); !!}
@endsection
