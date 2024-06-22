<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Supervisor;

use App\Exports\TeacherExport;
use App\Exports\TeacherStatisticsExport;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->withCount('students')->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('teacher', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Mobile') . ' : </span><span style="direction: ltr">' . $row->mobile . '</span></div>' .
                        '<div class="d-flex text-danger">' . '<span style="direction: ltr">' . $row->email . '</span></div>' .
                        '</div>';
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">' . t('Active') . '</span>' : '<span class="badge badge-danger">' . t('Inactive') . '</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">' . t('Approved') . '</span>' : '<span class="badge badge-warning">' . t('Under review') . '</span>';
                })
                ->addColumn('students_count', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>' .
                        '</div>';
                })
                ->addColumn('active_to', function ($row) {
                    return $row->active_to ? Carbon::parse($row->active_to)->toDateString() : '';
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->format('Y-m-d H:i') : '';
                })
                ->make();
        }
        $title = t('Show teachers');
        return view('supervisor.teacher.index', compact('title'));
    }

    public function teachersTracking(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->with(['school'])->withCount(['students'])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('teacher', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' .
                        '<div class="d-flex text-danger">' . '<span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>' .
                        '</div>';
                })
                ->addColumn('status', function ($row) {
                    $active = t('Activation') . ' ' . $row->active ? '<span class="badge badge-primary">' . t('Active') . '</span>' : '<span class="badge badge-danger">' . t('Inactive') . '</span>';
                    $approve = t('Approval') . ' ' . $row->approved ? '<span class="badge badge-primary">' . t('Approved') . '</span>' : '<span class="badge badge-warning">' . t('Under review') . '</span>';
                    return $active . '<br />' . $approve;
                })
                ->addColumn('actions', function ($row) {
                    $actions = [['key' => 'blank', 'name' => t('Report'), 'route' => route('manager.teacher.tracking_report', $row->id)],];
                    return view('general.action_menu')->with('actions', $actions);
                })
                ->make();
        }
        $title = t('Show tracking teachers');
        $schools = School::query()->get();
        return view('supervisor.teacher.tracking', compact('title', 'schools'));
    }

    public function exportTeachersExcel(Request $request)
    {
        $request->validate(['supervisor_id' => 'required']);
        return (new TeacherExport($request))->download('Teachers Information.xlsx');
    }

    public function teachersTrackingExport(Request $request)
    {
        $request->validate(['supervisor_id' => 'required']);
        return (new TeacherStatisticsExport($request, $request->get('supervisor_id')))
            ->download('Teachers statistics.xlsx');
    }

}
