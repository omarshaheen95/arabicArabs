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
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $rows = Package::query()->latest()->search($request);
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('active', function ($row) {
                    return $row->active_status;
                })
                ->addColumn('actions', function ($row) {
                    $edit_url = route('manager.package.edit', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url'));
                })
                ->make();
        }
        $title = "قائمة الباقات";
        return view('manager.package.index', compact('title'));
    }

    public function create()
    {
        $title = "إضافة باقة";
        return view('manager.package.edit', compact('title'));
    }

    public function store(PackageRequest $request)
    {
        $data = $request->validated();
        $data['active'] = $request->get('active', 0);
        Package::create($data);
        return redirect()->route('manager.package.index')->with('message', self::ADDMESSAGE);
    }

    public function edit($id)
    {
        $title = "تعديل باقة";
        $package = Package::query()->findOrFail($id);
        return view('manager.package.edit', compact('title', 'package'));
    }

    public function update(PackageRequest $request, $id)
    {
        $package = Package::query()->findOrFail($id);
        $data = $request->validated();
        $data['active'] = $request->get('active', 0);
        $package->update($data);
        return redirect()->route('manager.package.index')->with('message', self::EDITMESSAGE);
    }

    public function destroy($id)
    {
        $package = Package::query()->findOrFail($id);
        $package->delete();
        return redirect()->route('manager.package.index')->with('message', self::DELETEMESSAGE);
    }
}
