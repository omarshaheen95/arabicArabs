<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PackageRequest;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show packages')->only('index');
        $this->middleware('permission:add packages')->only(['create','store']);
        $this->middleware('permission:edit packages')->only(['edit','update']);
        $this->middleware('permission:delete packages')->only('destroy');
    }
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $rows = Package::query()->filter($request)->withCount(['users'])
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('name', function ($row){
                    return $row->name;
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-danger">'.t('Inactive').'</span>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show packages');
        return view('manager.package.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add package');
        return view('manager.package.edit', compact('title'));
    }

    public function store(PackageRequest $request)
    {
        $data = $request->validated();
        $data['active'] = $request->get('active', 0);
        Package::create($data);
        return redirect()->route('manager.package.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit package');
        $package = Package::query()->findOrFail($id);
        return view('manager.package.edit', compact('title', 'package'));
    }

    public function update(PackageRequest $request, $id)
    {
        $package = Package::query()->findOrFail($id);
        $data = $request->validated();
        $data['active'] = $request->get('active', 0);
        $package->update($data);
        return redirect()->route('manager.package.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        Package::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }
}
