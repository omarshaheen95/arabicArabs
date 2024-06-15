@extends('manager.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('manager.story_assignment.store') }}" method="post">
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
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Students Grade') }}</label>
                <select class="form-select students_grade" data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="students_grade" id="students_grade">
                    <option></option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
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
                <label class="form-label">{{ t('Learning Years') }}</label>
                <select class="form-select learning_years" data-control="select2"
                        data-placeholder="{{t('Select Years')}}"
                        data-allow-clear="true" name="years_learning[]" id="years_learning" multiple>
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
                <label class="form-label">{{ t('Story Grade') }}</label>
                <select class="form-select assignment_grade" data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="grade" id="assignment_grade">
                    <option></option>
                    @foreach(storyGradesSys() as $key => $grade)
                        <option value="{{ $key }}">{{ $grade }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Story') }}</label>
                <select class="form-select " data-control="select2" multiple
                        data-placeholder="{{t('Select Story')}}"
                        data-allow-clear="true"
                        name="story_id[]" id="">
                    <option></option>
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\StoryAssignmentRequest::class, '#form_information'); !!}

    <script>
        var getTeacherBySchool = '{{ route("manager.getTeacherBySchool", ":id") }}';
        var getSectionBySchool = '{{ route("manager.getSectionBySchool", ":id") }}';
        var getSectionByTeacher = '{{ route("manager.getSectionByTeacher", ":id") }}';

        onSelectAllClick('section')

        $('input[name="deadline"]').flatpickr();



        $('select[name="students_grade"], #learning_years').change(function () {
            var id = $(this).val();
            var teacher = $('select[name="teacher_id"]').val();
            if (teacher) {
                getStudentsData(teacher, id);
            }
        });

        $('select[name="section[]"]').change(function () {
            var grade = $('select[name="students_grade"]').val();
            var teacher = $('select[name="teacher_id"]').val();
            if(teacher && grade)
            {
                getStudentsData(teacher, grade);
            }
        });

        getStoriesByGrade("{{route('manager.getStoriesByGrade')}}");


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
                var grade = $('select[name="students_grade"]').val();
                if (grade) {
                    getStudentsData(id, grade)
                }
            });



        });
        getStoriesByGrade()

    </script>

@endsection
