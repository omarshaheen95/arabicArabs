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
            $rows = Teacher::query()->withCount('students')->search($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('active', function ($row) {
                    return $row->active_status;
                })
                ->addColumn('status', function ($row) {
                    return $row->approved_status;
                })
                ->addColumn('school', function ($row) {
                    return $row->school->name;
                })
                ->make();
        }
        $title = t('Show teachers');
        return view('supervisor.teacher.index', compact('title'));
    }

    public function teacherExport(Request $request)
    {
        $user = Auth::guard('supervisor')->user();
        return (new TeacherExport($request, $user->school_id))
            ->download('Teachers Information.xlsx');
    }

    public function teachersStatistics(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->withCount('students')->search($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('supervisor.teacher.statistics_report', $row->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
                })
                ->make();
        }
        $title = "إحصائيات المعلمين";
        return view('supervisor.teacher.statistics', compact('title'));
    }

    public function teachersStatisticsExport(Request $request)
    {
        return (new TeacherStatisticsExport($request, Auth::guard('supervisor')->user()->school_id))
            ->download('Teachers statistics.xlsx');
    }

}
