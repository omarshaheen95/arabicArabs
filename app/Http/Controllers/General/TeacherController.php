<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\TeacherRequest;
use App\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\Request;


class TeacherController extends Controller
{
    protected $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->middleware('permission:edit teachers')->only('forcePasswordChange');
        $this->teacherRepository = $teacherRepository;
        $this->middleware('permission:show teachers')->only('index');
        $this->middleware(['permission:add teachers'])->only(['create', 'store']);
        $this->middleware('permission:edit teachers')->only(['edit', 'update']);
        $this->middleware('permission:delete teachers')->only('destroy');
        $this->middleware('permission:export teachers')->only('exportTeachersExcel');
        $this->middleware('permission:teacher login')->only('login');
        $this->middleware('permission:teachers activation')->only('activation');
        $this->middleware('permission:teacher users unsigned')->only('deleteStudents');
        $this->middleware('permission:teacher tracking')->only(['teachersTracking', 'teachersTrackingExport']);
        $this->middleware('permission:teacher tracking report')->only(['teachersTrackingReport']);
        $this->middleware('permission:reset teachers passwords')->only(['resetPasswords']);
    }

    public function index(Request $request)
    {
        return $this->teacherRepository->index($request);
    }

    public function create()
    {
        return $this->teacherRepository->create();
    }

    public function store(TeacherRequest $request)
    {
        return $this->teacherRepository->store($request);
    }

    public function edit($id)
    {
        return $this->teacherRepository->edit($id);
    }

    public function update(TeacherRequest $request, $id)
    {
        return $this->teacherRepository->update($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->teacherRepository->destroy($request);
    }

    public function activation(Request $request)
    {
        return $this->teacherRepository->activation($request);

    }

    public function exportTeachersExcel(Request $request)
    {
        return $this->teacherRepository->exportTeachersExcel($request);
    }

    public function login($id)
    {
        return $this->teacherRepository->login($id);
    }

    public function deleteStudents(Request $request)
    {
        return $this->teacherRepository->deleteStudents($request);
    }

    public function teachersTracking(Request $request)
    {
        return $this->teacherRepository->teachersTracking($request);
    }

    public function teachersTrackingExport(Request $request)
    {
        return $this->teacherRepository->teachersTrackingExport($request);
    }

    public function teachersTrackingReport(Request $request, $id)
    {
        return $this->teacherRepository->teachersTrackingReport($request, $id);

    }

    public function resetPasswords(Request $request)
    {
        return $this->teacherRepository->resetPasswords($request);
    }

    public function forcePasswordChange(Request $request)
    {
        return $this->teacherRepository->forcePasswordChange($request);
    }

}
