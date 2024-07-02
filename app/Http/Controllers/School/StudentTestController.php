<?php

namespace App\Http\Controllers\School;

use App\Exports\StudentLessonExport;
use App\Exports\StudentStoryRecordExport;
use App\Exports\StudentStoryTestExport;
use App\Exports\StudentTestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UpdateUserRecordRequest;
use App\Models\Grade;
use App\Models\Question;
use App\Models\School;
use App\Models\StoryQuestion;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\UserLesson;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentTestController extends Controller
{
    public function lessonsIndex(Request $request)
    {
        if (request()->ajax())
        {
            $rows = UserTest::with(['user.school','user.grade','lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('School').':</span>'.$row->user->school->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">'.t('Submitted At').':</span>'.$row->created_at->format('Y-m-d H:i').'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row) {
                    $html =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->lesson->grade->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Lesson').':</span>'.$row->lesson->name.'</div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass'?'<span class="badge badge-primary">'.$row->status.'</span>':'<span class="badge badge-danger">'.$row->status.'</span>';
                    $html =  '<div class="d-flex flex-column justify-content-center">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->total_per.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$status.'</span></div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    //add created_at to the action buttons
                    return $row->action_buttons;
                })

                ->make();
        }
        $title = t('Show students lessons tests');
        $grades = Grade::query()->get();
        $teachers = Teacher::query()->filter($request)->get();

        return view('school.students_tests.lessons', compact('title', 'grades','teachers'));
    }

    public function lessonsShow(Request $request,$id){
        $student_test = UserTest::query()->with(['lesson','user'])->where('id',$id)->first();
        if ($student_test){
            $questions = Question::with([
                'trueFalse','options','matches','sortWords',
                'true_false_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'option_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'match_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'sort_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },
            ])->where('lesson_id',$student_test->lesson_id)->get();
            // dd($questions->toArray());

            $lesson = $student_test->lesson;
            $user = $student_test->user;
            return view('general.user.test_preview.test',compact('questions','student_test','lesson','user'));
        }
        return redirect()->route('school.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }

    public function lessonsCertificate(Request $request,$id)
    {
        $title = 'Student test result';
        $student_test = UserTest::query()->with(['lesson.grade'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route('manager.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.new_certificate', compact('student_test', 'title'));
    }

    public function lessonsExportStudentsTestsExcel(Request $request)
    {
        return (new StudentTestExport($request))->download('Students tests.xlsx');
    }



    public function storiesIndex(Request $request)
    {
        if (request()->ajax())
        {
            $rows = StudentStoryTest::with(['user','user.grade','story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">'.t('Submitted At').':</span>'.$row->created_at->format('Y-m-d H:i').'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $html =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Level').':</span>'.$row->story->grade_name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Story').':</span>'.$row->story->name.'</div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass'?'<span class="badge badge-primary">'.$row->status.'</span>':'<span class="badge badge-danger">'.$row->status.'</span>';
                    $html =  '<div class="d-flex flex-column justify-content-center">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->total_per.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$status.'</span></div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })

                ->make();
        }
        $title = t('Show students stories tests');
        $grades = Grade::query()->get();
        $teachers = Teacher::query()->filter($request)->get();

        return view('school.students_tests.stories', compact('title','grades','teachers'));
    }

    public function storiesShow(Request $request,$id){
        $student_test = StudentStoryTest::query()->with(['story','user'])->where('id',$id)->first();
        if ($student_test){
            $questions = StoryQuestion::with([
                'trueFalse','options','matches','sort_words',
                'true_false_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },'option_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },'match_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },'sort_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },
            ])->where('story_id',$student_test->story_id)->get();
            // dd($questions->toArray());

            $story = $student_test->story;
            $user = $student_test->user;
//            dd($questions);
            return view('general.user.test_preview.story_test',compact('questions','student_test','story','user'));
        }
        return redirect()->route('school.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }

    public function storiesCertificate(Request $request,$id)
    {
        $title = 'Student test result';
        $student_test = StudentStoryTest::query()->with(['story'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route('school.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.story.new_certificate', compact('student_test', 'title'));
    }

    public function exportStoriesTestsExcel(Request $request)
    {
        return (new StudentStoryTestExport($request))->download('Students tests.xlsx');
    }




    public function storiesRecordsIndex(Request $request)
    {
        if (request()->ajax())
        {
            $rows = StoryUserRecord::with(['user.school','user.grade','story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $html =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->story->grade_name.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Story').':</span>'.$row->story->name.'</div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html =  '<div class="d-flex flex-column justify-content-center">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->status_name_class.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->created_at->format('Y-m-d H:i').'</span></div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })

                ->make();
        }
        $title = t('Show students stories records');
        $grades = Grade::query()->get();
        $teachers = Teacher::query()->filter($request)->get();
        return view('school.stories_records.index', compact('title','grades','teachers'));
    }

    public function exportStoriesRecordsExcel(Request $request)
    {
        return (new StudentStoryRecordExport($request))->download('Students stories records.xlsx');
    }

    public function studentLessonIndex(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserLesson::query()
                ->with(['user.grade', 'user.teacher', 'lesson.grade'])
                ->filter($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('school', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span class="ps-1"> ' . $teacher . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('student', function ($row) {
                    $section = !is_null($row->user->section) ? $row->user->section : '<span class="text-danger">-</span>';

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->user->grade->name . '</span> : ' . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row){
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Lesson') . ' </span> : ' . '<span class="ps-1"> ' . $row->lesson->name . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Status') . ' </span> : ' . '<span class="ps-1"> ' . $row->status_name_class . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Submitted At') . ' </span> : ' . '<span class="ps-1"> ' . $row->created_at->format('Y-m-d H:i') . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->make();
        }
        $title = t('Students works');
        $teachers = Teacher::query()->filter($request)->get();
        $grades = Grade::query()->get();
        return view('school.students_works.index', compact('title', 'teachers','grades'));
    }
    public function studentLessonExport(Request $request)
    {
        $school = Auth::guard('school')->user();
        $file_name = $school->name . " Students Lesson Works.xlsx";
        return (new StudentLessonExport($request))->download($file_name);
    }
}
