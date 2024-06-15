//Custom Functions -------------------------------------------------------------------------------------------------------
const csrf = $('meta[name=csrf-token]').attr('content');


function getLessonsByGrade(on_change_name = 'grade_id') {
    if (typeof getLessonsByGradeURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            let value = $(this).val()
            $.ajax({
                type: "get",
                url: getLessonsByGradeURL,
                data: {'_token': csrf, 'grade_id': value}

            }).done(function (data) {
                if ($('select[name="lesson_id"]').length) {
                    $('select[name="lesson_id"]').html(data.html);
                }
                if ($('select[name="lesson_id[]"]').length) {
                    $('select[name="lesson_id[]"]').html(data.html);
                }
            });
        });
    }
}

function getStoriesByGrade(on_change_name = 'grade') {
    if (typeof getStoriesByGradeURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            let value = $(this).val()
            $.ajax({
                type: "get",
                url: getStoriesByGradeURL,
                data: {'_token': csrf, 'grade': value}

            }).done(function (data) {
                if ($('select[name="story_id"]').length) {
                    $('select[name="story_id"]').html(data.html);
                }
                if ($('select[name="story_id[]"]').length) {
                    $('select[name="story_id[]"]').html(data.html);
                }
            });
        });
    }
}

function getTeacherBySchool(on_change_name = 'school_id') {
    if (typeof getTeacherBySchoolURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            var id = $(this).val();
            var url = getTeacherBySchoolURL;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="teacher_id"]').html(data.html);
            });
        });
    }
}

function getSectionBySchool(on_change_name = 'school_id') {
    if (typeof getSectionBySchoolURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            var url = getSectionBySchoolURL;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="section"]').html(data.html);
            });
        });
    }
}

function getSectionByTeacher(on_change_name = 'teacher_id') {
    if (typeof getSectionByTeacherURL !== 'undefined') {

        $('select[name="teacher_id"]').change(function () {
            var id = $(this).val();
            var url = getSectionByTeacherURL;
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="section"]').html(data.html);
            });
        });
    }
}

