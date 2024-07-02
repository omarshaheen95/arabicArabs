<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Supervisor;

use App\Classes\GeneralFunctions;
use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\School;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::query()->with(['package','grade', 'teacher'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('school', function ($row) {
                    $teacher = optional($row->teacher)->name ? optional($row->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $package = optional($row->package)->name;
                    $gender = !is_null($row->gender) ? $row->gender : '<span class="text-danger">-</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span> ' . $teacher . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('package', function ($row) {
                    return optional($row->package)->name;
                })
                ->addColumn('student', function ($row) {
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->grade->name . '</span> : ' . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('dates', function ($row) {
                    $register_date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $active_to = $row->active_to ? optional($row->active_to)->format('Y-m-d') : t('unpaid');
                    $last_login = $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                    if ($row->active == 0) {
                        $status = '<span class="text-danger">' . t('Suspend') . '</span>';
                    } elseif ($row->active == 1 && !is_null($row->active_to) && optional($row->active_to)->format('Y-m-d') <= now()) {
                        $status = '<span class="text-danger">' . t('Expired') . '</span>';
                    } elseif ($row->active == 1 && !is_null($row->active_to) && optional($row->active_to)->format('Y-m-d') > now()) {
                        $status = '<span class="text-success">' . t('Active') . '</span>';
                    } else {
                        $status = '<span class="text-warning">' . t('Unknown') . '</span>';
                    }

                    if ($row->active_to) {
                        $active_to = optional($row->active_to)->format('Y-m-d') <= now() ? '<span class="text-danger">' . optional($row->active_to)->format('Y-m-d') . '</span>' : '<span class="text-success">' . optional($row->active_to)->format('Y-m-d') . '</span>';
                    }
                    $data = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Register Date') . '</span> : ' . $register_date . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Active To') . '</span> : ' . $active_to . '-' . $status . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Last Login') . '</span> : ' . $last_login . '</div>' .
                        '</div>';
                    return $data;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Users');
        $packages = Package::query()->get();
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query){
            $query->where('supervisor_id', Auth::guard('supervisor')->id());
        })->get();
        return view('supervisor.user.index', compact('title', 'packages', 'teachers'));
    }

    public function exportStudentsExcel(Request $request)
    {
        $file_name = "Students Information.xlsx";
        if ($request->get('school_id', false)) {
            $school = School::query()->findOrFail($request->get('school_id'));
            $file_name = $school->name . " Students Information.xlsx";
        }
        return (new StudentInformation($request))->download($file_name);
    }


    public function review(Request $request, $id)
    {
        $user = User::with('grade','teacher','alternateGrade')->whereHas('school.supervisors', function (Builder $query){
            $query->where('id', Auth::guard('supervisor')->user()->id);
        })->findOrFail($id);

        if (request()->ajax()) {
            $rows = UserTracker::query()->where('user_id', $id)->has('lesson')->with(['lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('type', function ($row) {
                    return t(ucfirst($row->type));
                })
                ->addColumn('lesson', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->lesson->grade_name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . $row->lesson->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('time_spent', function ($row) {
                    if (!is_null($row->start_at) && !is_null($row->end_at)) {
                        $time1 = new \DateTime($row->start_at);
                        $time2 = new \DateTime($row->end_at);
                        $interval = $time1->diff($time2);
                        return $interval->format('%i ' . t('minute(s)'));
                    } else {
                        return '-';
                    }
                })
                ->make();
        }

        $title = t('Student Lessons review');
        $teacher = $user->teacher;

        $grades = Grade::query()->get();
        $tests = UserTest::query()->where('user_id', $id)->count();
        $passed_tests = UserTest::query()->where('user_id', $id)->where('total', '>=', 40)->count();


        $user_tracker = UserTracker::query()->where('user_id', $id)->get();
        $total = $user_tracker->where('user_id', $id)->count();
        $data['learn'] = $user_tracker->where('user_id', $id)->where('type', 'learn')->count();
        $data['practise'] = $user_tracker->where('user_id', $id)->where('type', 'practise')->count();
        $data['test'] = $user_tracker->where('user_id', $id)->where('type', 'test')->count();
        $data['play'] = $user_tracker->where('user_id', $id)->where('type', 'play')->count();

        $data['learn_avg'] = $total && $data['learn'] ? round(($data['learn'] / $total) * 100, 2) : 0;
        $data['practise_avg'] = $total && $data['practise'] ? round(($data['practise'] / $total) * 100, 2) : 0;
        $data['test_avg'] = $total && $data['test'] ? round(($data['test'] / $total) * 100, 2) : 0;
        $data['play_avg'] = $total && $data['play'] ? round(($data['play'] / $total) * 100, 2) : 0;

        $data['passed_tests'] = UserTest::query()->where('user_id', $id)->where('status', 'Pass')->count();
        $data['failed_tests'] = UserTest::query()->where('user_id', $id)->where('status', 'Fail')->count();
        $guard = 'supervisor';

        return view('general.user.user_lesson_review', compact('user', 'teacher',
            'tests', 'passed_tests', 'title', 'data', 'guard','grades'));

    }

    public function storyReview(Request $request, $id)
    {
        $user = User::with('grade','teacher','alternateGrade')->whereHas('school.supervisors', function (Builder $query){
            $query->where('id', Auth::guard('supervisor')->user()->id);
        })->findOrFail($id);

        if (request()->ajax()) {
            $rows = UserTrackerStory::query()->where('user_id', $id)->has('story')->with(['story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('type', function ($row) {
                    return t(ucfirst($row->type));
                })
                ->addColumn('story', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Level') . ':</span>' . $row->story->grade_name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('time_spent', function ($row) {
                    if (!is_null($row->start_at) && !is_null($row->end_at)) {
                        $time1 = new \DateTime($row->start_at);
                        $time2 = new \DateTime($row->end_at);
                        $interval = $time1->diff($time2);
                        return $interval->format('%i ' . t('minute(s)'));
                    } else {
                        return '-';
                    }
                })
                ->make();
        }
        $title = t('Student story review');
        $teacher = $user->teacher;


        $tests = StudentStoryTest::query()->where('user_id', $id)->get();

        $user_tracker_stories = UserTrackerStory::query()->where('user_id', $id)->get();

        $total = $user_tracker_stories->where('user_id', $id)->count();
        $data['watching'] = $user_tracker_stories->where('type', 'watching')->count();
        $data['reading'] = $user_tracker_stories->where('type', 'reading')->count();
        $data['test'] = $user_tracker_stories->where('type', 'test')->count();
        $data['watching_avg'] = $total && $data['watching'] ? round(($data['watching'] / $total) * 100, 2) : 0;
        $data['reading_avg'] = $total && $data['reading'] ? round(($data['reading'] / $total) * 100, 2) : 0;
        $data['test_avg'] = $total && $data['test'] ? round(($data['test'] / $total) * 100, 2) : 0;
        $data['passed_tests'] = $tests->where('status', 'Pass')->count();
        $data['failed_tests'] = $tests->where('status', 'Fail')->count();

        $guard = 'manager';
        $tests = $tests->count();

        return view('general.user.user_story_review', compact('user', 'teacher',
            'tests', 'title', 'data', 'total', 'guard'));

    }

    public function report(Request $request, $id)
    {
        $request['teacher_id'] = Auth::guard('supervisor')->user()->teachers->pluck('id');
        $general = new GeneralFunctions();
        return $general->userReport($request,$id);
    }



    public function studentsCards(Request $request)
    {
        $request->validate(['teacher_id'=>'required']);
        $students = User::query()->filter($request)->get()->chunk(6);
        $qr = 1;//isset($request['qr-code']);
        $student_login_url = config('app.url') . '/login';
        $school = School::find($request->get('school_id'));

        $title = $school ? $school->name . ' | ' . t('Students Cards') : t('Students Cards');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'qr', 'title'));
    }


}
