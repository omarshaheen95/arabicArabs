//Custom Functions -------------------------------------------------------------------------------------------------------
const csrf = $('meta[name=csrf-token]').attr('content');


function getLessonsByGrade(on_change_name = 'grade_id',callback=null) {
    if (typeof getLessonsByGradeURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            let value = $(this).val()
            $.ajax({
                type: "get",
                url: getLessonsByGradeURL,
                data: {'_token': csrf, 'grade_id': value}

            }).done(function (data) {
                if (typeof callback === 'function') {
                    callback(true);
                }
                if ($('select[name="lesson_id"]').length) {
                    $('select[name="lesson_id"]').html(data.html);
                    $('select[name="lesson_id"]').trigger('change');

                }
                if ($('select[name="lesson_id[]"]').length) {
                    $('select[name="lesson_id[]"]').html(data.html);
                    $('select[name="lesson_id[]"]').trigger('change');

                }
            });
        });
    }
}

function getStoriesByGrade(on_change_name = 'grade',callback=null) {
    if (typeof getStoriesByGradeURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            let value = $(this).val()
            $.ajax({
                type: "get",
                url: getStoriesByGradeURL,
                data: {'_token': csrf, 'grade': value}

            }).done(function (data) {
                if (typeof callback === 'function') {
                    callback(true);
                }
                if ($('select[name="story_id"]').length) {
                    $('select[name="story_id"]').html(data.html);
                    $('select[name="story_id"]').trigger('change');

                }
                if ($('select[name="story_id[]"]').length) {
                    $('select[name="story_id[]"]').html(data.html);
                    $('select[name="story_id[]"]').trigger('change');

                }
            });
        });
    }
}

function getTeacherBySchool(on_change_name = 'school_id',callback=null) {
    if (typeof getTeacherBySchoolURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            var id = $(this).val();
            var url = getTeacherBySchoolURL;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                if (typeof callback === 'function') {
                    callback(true);
                }
                $('select[name="teacher_id"]').html(data.html);
                $('select[name="teacher_id"]').trigger('change');

            });
        });
    }
}

function getSectionBySchool(on_change_name = 'school_id',callback=null) {
    if (typeof getSectionBySchoolURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            var url = getSectionBySchoolURL;
            var id = $(this).val();
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                if (typeof callback === 'function') {
                    callback(true);
                }
                if ($('select[name="section"]').length) {
                    $('select[name="section"]').html(data.html);
                    $('select[name="section"]').trigger('change');

                }
                if ($('select[name="section[]"]').length) {
                    $('select[name="section[]"]').html(data.html);
                    $('select[name="section[]"]').trigger('change');

                }
            });
        });
    }
}

function getSectionByTeacher(on_change_name = 'teacher_id',callback=null) {
    if (typeof getSectionByTeacherURL !== 'undefined') {

        $('select[name="teacher_id"]').change(function () {
            var id = $(this).val();
            var url = getSectionByTeacherURL;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                if (typeof callback === 'function') {
                    callback(true);
                }
                if ($('select[name="section"]').length) {
                    $('select[name="section"]').html(data.html);
                    $('select[name="section"]').trigger('change');

                }
                if ($('select[name="section[]"]').length) {
                    $('select[name="section[]"]').html(data.html);
                    $('select[name="section[]"]').trigger('change');

                }
            });
        });
    }
}

