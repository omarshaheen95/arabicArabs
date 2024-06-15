@extends('manager.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('manager.lesson_assignment.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('School') }}</label>
                <select class="form-select" data-control="select2"
                        data-placeholder="{{t('Select School')}}"
                        data-allow-clear="true" name="school_id" id="school_id">
                    <option></option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{$school->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Teacher') }}</label>
                <select class="form-select" data-control="select2"
                        data-placeholder="{{t('Select Teacher')}}"
                        data-allow-clear="true" name="teacher_id" id="teacher_id">
                    <option></option>
                </select>
            </div>
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
                <label class="form-label">{{ t('Learning Years') }}</label>
                <select class="form-select" data-control="select2"
                        data-placeholder="{{t('Select Years')}}"
                        data-allow-clear="true" name="year_learning[]" id="year_learning" multiple>
                    <option></option>
                    @foreach(range(0,12) as $year)
                        <option value="{{$year}}" selected>{{$year}}</option>
                    @endforeach
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

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\AssignmentRequest::class, '#form_information'); !!}

    <script>
        var getTeacherBySchool = '{{ route("manager.getTeacherBySchool", ":id") }}';
        var getSectionBySchool = '{{ route("manager.getSectionBySchool", ":id") }}';
        var getSectionByTeacher = '{{ route("manager.getSectionByTeacher", ":id") }}';


        onSelectAllClick('section')

        $('input[name="deadline"]').flatpickr();


        $('select[name="grade_id"], #year_learning').change(function () {
            var id = $(this).val();
            var teacher = $('select[name="teacher_id"]').val();
            $.ajax({
                type: "get",
                url: "{{route('manager.getLessonsByGrade')}}",
                data: {'_token': "{{csrf_token()}}", 'grade_id': id}

            }).done(function (data) {
                    $('select[name="lesson_id[]"]').html(data.html);
            });
            if (teacher) {
                getStudentsData(teacher, id);
            }
        });

        $('select[name="section[]"]').change(function () {
            var grade = $('select[name="grade_id"]').val();
            var teacher = $('select[name="teacher_id"]').val();
            if(teacher && grade)
            {
                getStudentsData(teacher, grade);
            }
        });


        onSelectAllClick('assignment_students')

        function getStudentsData(teacher, grade) {
            if (grade) {
                var students_url = '{{ route("manager.getStudentsByGrade", ":id") }}';
                students_url = students_url.replace(':id', grade);
                $.ajax({
                    type: "get",
                    url: students_url,
                    data: {
                        teacher_id: teacher,
                        learning_years: $("#learning_years").val(),
                        section:$('#section').val()
                    }
                }).done(function (student_data) {
                    $('select[name="students[]"]').html(student_data.html);
                });
            }

        }


        $('select[name="school_id"]').change(function () {
            var id = $(this).val();
            var url = getTeacherBySchool;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="teacher_id"]').html(data.html);
            });
            var url = getSectionBySchool;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="section[]"]').html(data.html);
            });
        });

        $('select[name="teacher_id"]').change(function () {
            var id = $(this).val();
            var url = getSectionByTeacher + "?multiple=1&selected=0";
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="section[]"]').html('<option value="all">{{t("All")}}</option>'+data.html);
                var grade = $('select[name="grade_id"]').val();
                if (grade) {
                    getStudentsData(id, grade)
                }
            });



        });

    </script>

@endsection
