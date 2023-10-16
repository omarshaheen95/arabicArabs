<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ImportStudentFileRequest;
use App\Imports\ImportUserExcel;
use App\Imports\ImportUserFileExcel;
use App\Models\ImportStudentFile;
use App\Models\Package;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = ImportStudentFile::with(['school'])->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('original_file_name', function ($row) {
                    return '<a href="' . asset($row->file_path) . '" target="_blank"><span class="font-weight-bold">' . $row->original_file_name . '</span></a>';
                })
                ->addColumn('school', function ($row) {
                    return optional($row->school)->name;
                })
                ->addColumn('status', function ($row) {

                    if ($row->status == 'Errors') {
                        return '<a href="' . route('manager.import_users_files.show', [$row->id]) . '"><span class="badge badge-danger">' . t('Error') . '</span></a>';
                    } elseif ($row->status == 'Failures') {
                        return '<a href="' . route('manager.import_users_files.show', [$row->id]) . '"><span class="badge badge-danger">' . t('Failures') . '</span></a>';
                    } elseif ($row->status == 'New') {
                        return '<a><span class="badge badge-info">' . t('New') . '</span></a>';
                    } elseif ($row->status == 'Uploading') {
                        return '<a><span class="badge badge-warning">' . t('Uploading') . '</span></a>';
                    } elseif ($row->status == 'Completed') {
                        return '<a><span class="badge badge-success">' . t('Completed') . '</span></a>';
                    } else {
                        return '<a><span class="font-weight-bold">' . t($row->status) . '</span></a>';
                    }
                })
                ->addColumn('with_abt_id', function ($row) {
                    if ($row->with_abt_id == 1) {
                        return t('With ABT ID');
                    } else {
                        return t('Without ABT ID');
                    }
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('List Import Students File');
        $schools = School::query()->get();
        $statuses = [
            'New',
            'Uploading',
            'Completed',
            'Failures',
            'Errors',
        ];
        return view('manager.import.index', compact('title', 'schools', 'statuses'));
    }

    public function create()
    {
        $title = 'Add Import Students File';
        $schools = School::query()->get();
        $packages = Package::query()->get();
        $note = t('The student file format must be one of these formats') . ' : xlsx';
        return view('manager.import.edit', compact('title', 'schools', 'note', 'packages'));
    }

    public function store(ImportStudentFileRequest $request)
    {
        $data = $request->validated();
        $with_abt_id = $request->get('with_abt_id', false);
        $abt_id = null;
//        if ($with_abt_id) {
//            $abt_id
////            $abt_id = Student::query()->max('abt_id');
//        } else {
//            $abt_id = null;
//        }
        $file = $request->file('file');

        //upload file
        $upload_file = uploadFile($file, '/student-import-files');
        //save file data
        $create_file = ImportStudentFile::query()->create([
            'school_id' => $data['school_id'],
            'original_file_name' => $file->getClientOriginalName(),
            'file_name' => $upload_file['name'],
            'file_path' => $upload_file['path'],
            'status' => 'New',
            'with_abt_id' => $with_abt_id, //1=>New
        ]);

        //import students
        $student_import = new ImportUserFileExcel($create_file, $abt_id, $request);
        \Maatwebsite\Excel\Facades\Excel::import($student_import, public_path($create_file->file_path));

        $file_data = [
            'created_rows_count' => $student_import->getRowsCount(),
            'updated_rows_count' => $student_import->getUpdatedRowsCount(),
            'failed_rows_count' => $student_import->getFailedRowCount(),
        ];
        if ($student_import->getError()) {
            $file_data['status'] = 'Errors'; //Error
            $file_data['error'] = $student_import->getError();
            $create_file->update($file_data);
            return redirect()->route('manager.student-import-files.index')->withErrors([$student_import->getExceptionMessage()]);
        }

        if ($student_import->getFailures()) {
            $file_data['status'] = 'Failures'; //Failures
            $file_data['failures'] = $student_import->getFailures();
        } else {
            $file_data['status'] = 'Completed'; //Complete
        }

        $create_file->update($file_data);
        return redirect()->route('manager.import_users_files.index')->with('message', t('Students Successfully imported'))->with('m-class', 'success');
    }

    public function show($id)
    {
        $file = ImportStudentFile::query()->findOrFail($id);
        $title = 'Show File Errors';
        return view('manager.import.show', compact('file', 'title'));
    }

    public function destroy(Request $request, $id)
    {
        $file = ImportStudentFile::query()->where('id', $id)->firstOrFail();
        $deleteStudents = $request->get('delete_students', false);
        if ($deleteStudents) {
            $file->update([
                'delete_with_user' => 1,
            ]);
            User::query()->where('import_student_file_id', $file->id)->delete();
        } else {
            User::query()->where('import_student_file_id', $file->id)->update([
                'import_student_file_id' => null,
            ]);
        }
        $file->delete();
        return $this->redirectWith(true, null, 'Successfully Deleted');
    }
}
