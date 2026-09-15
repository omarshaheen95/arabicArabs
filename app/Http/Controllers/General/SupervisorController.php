<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\General;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\SupervisorRequest;
use App\Interfaces\SupervisorRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupervisorController extends Controller
{

    protected $supervisorRepository;

    public function __construct(SupervisorRepositoryInterface $supervisorRepository)
    {
        $this->middleware('permission:edit supervisors')->only('forcePasswordChange');
        $this->supervisorRepository = $supervisorRepository;

        $this->middleware('permission:show supervisors')->only('index');
        $this->middleware('permission:add supervisors')->only(['create', 'store']);
        $this->middleware('permission:edit supervisors')->only(['edit', 'update']);
        $this->middleware('permission:delete supervisors')->only('destroy');
        $this->middleware('permission:export supervisors')->only('export');
        $this->middleware('permission:supervisors activation')->only(['activation']);
        $this->middleware('permission:reset supervisors passwords')->only('resetPasswords');

    }

    public function index(Request $request)
    {
        return $this->supervisorRepository->index($request);
    }

    public function create(Request $request)
    {
        return $this->supervisorRepository->create($request);
    }

    public function store(SupervisorRequest $request)
    {
        return $this->supervisorRepository->store($request);
    }

    public function edit(Request $request, $id)
    {
        return $this->supervisorRepository->edit($request, $id);

    }

    public function update(SupervisorRequest $request, $id)
    {
        return $this->supervisorRepository->update($request, $id);

    }

    public function destroy(Request $request)
    {
        return $this->supervisorRepository->destroy($request);

    }

    public function login($id)
    {
        return $this->supervisorRepository->login($id);
    }

    public function export(Request $request)
    {
        return $this->supervisorRepository->export($request);
    }

    public function activation(Request $request)
    {
        return $this->supervisorRepository->activation($request);
    }

    public function resetPasswords(Request $request)
    {
        return $this->supervisorRepository->resetPasswords($request);
    }

    public function forcePasswordChange(Request $request)
    {
        return $this->supervisorRepository->forcePasswordChange($request);
    }

}
