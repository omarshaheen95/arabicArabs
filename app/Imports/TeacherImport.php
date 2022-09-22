<?php

namespace App\Imports;

use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class TeacherImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $row_num;
    public $req;
    private $duplicateEmails = [];

    public function __construct(Request $request)
    {
        $this->req = $request;
        $this->row_num = 1;
    }

    public function model(array $row)
    {
        return Teacher::query()->updateOrCreate([
            'email' => strtolower($row['email']),
        ],[
            'name' => $row['name'],
            'email' => strtolower($row['email']),
            'password' => bcrypt('123456'),
            'school_id' => $this->req->get('school_id'),
            'active' => 1,
            'approved' => 1,
            'mobile' => isset($row['mobile']) ? $row['mobile']:'0500000000',
        ]);
    }

    public function onFailure(Failure ...$failures)
    {
        // TODO: Implement onFailure() method.
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
//            'mobile' => 'required',
        ];
    }
}
