@extends('teacher.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('teacher.lesson_assignment.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-lg-6 mb-2">
                <label class="form-label">{{t('Grade')}} :</label>
                <select name="grade_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                        data-allow-clear="true">
                    <option></option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{$grade->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Section') }}</label>
                <select class="form-select" name="section[]" id="section"
                        data-control="select2" data-placeholder="{{t('Select Section')}}" data-allow-clear="true"
                        multiple>
                    <option value="all">{{t('All')}}</option>
                    @foreach(teacherSections() as $section)
                        <option value="{{$section}}">{{$section}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 mb-2">
                <label class="form-label">{{ t('Lesson') }}</label>
                <select class="form-select assignment_lesson" data-control="select2" multiple
                        data-placeholder="{{t('Select Lesson')}}"
                        data-allow-clear="true"
                        name="lesson_id[]" id="assignment_lesson">
                    <option></option>
                </select>
            </div>

            <div class="form-group col-12 mb-2">
                <label class="form-label">{{ t('Students') }}</label>
                <select class="form-control assignment_students" data-control="select2"
                        data-placeholder="{{t('Select Students')}}"
                        data-allow-clear="true" name="students[]" id="assignment_students" multiple>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Deadline') }}</label>
                <input type="text" name="deadline" class="form-control date" placeholder="{{t('DeadLine')}}">
            </div>
            <div class="form-group col-6">
                <label class="form-label">{{ t('Exclude students who have completed the assignment before') }}:</label>
                <div class="d-flex gap-2 border-secondary" style="border: 1px solid;border-radius: 5px;padding: 9px">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="radio" value="1" name="exclude_students"/>
                        <label class="form-check-label" for="flexRadioDefault">
                            {{t('Yes')}}
                        </label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="radio" value="2" name="exclude_students" checked/>
                        <label class="form-check-label" for="flexRadioDefault">
                            {{t('No')}}
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center col-6 pt-4 gap-3">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="tasks_assignment" checked/>
                    <label class="form-check-label text-dark">
                        {{ t('Tasks assignment') }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="test_assignment" checked/>
                    <label class="form-check-label text-dark">
                        {{ t('Test assignment') }}
                    </label>
                </div>


            </div>


            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
            </div>
        </div>
    </form>

@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Teacher\LessonAssignmentRequest::class, '#form_information'); !!}

    <script>

        onSelectAllClick('section')

        $('input[name="deadline"]').flatpickr();


        $('select[name="grade_id"]').change(function () {
            var id = $(this).val();
            var teacher = $('select[name="teacher_id"]').val();
            $.ajax({
                type: "get",
                url: "{{route('teacher.getLessonsByGrade')}}",
                data: {'_token': "{{csrf_token()}}", 'grade_id': id}

            }).done(function (data) {
                    $('select[name="lesson_id[]"]').html(data.html);
            });
                getStudentsData(teacher, id);
        });

        $('select[name="section[]"]').change(function () {
            var grade = $('select[name="grade_id"]').val();
            var teacher = $('select[name="teacher_id"]').val();
            if(grade)
            {
                getStudentsData(teacher, grade);
            }
        });


        onSelectAllClick('assignment_students')

        function getStudentsData(teacher, grade) {
            if (grade) {
                var students_url = '{{ route("teacher.getStudentsByGrade", ":id") }}';
                students_url = students_url.replace(':id', grade);
                $.ajax({
                    type: "get",
                    url: students_url,
                    data: {
                        teacher_id: "{{request()->get('teacher_id')}}",
                        section:$('#section').val()
                    }
                }).done(function (student_data) {
                    $('select[name="students[]"]').html(student_data.html);
                });
            }

        }

        getLessonsByGrade()

    </script>

@endsection
