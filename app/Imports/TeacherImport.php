<?php

namespace App\Imports;

use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;
HeadingRowFormatter::default('none');
class TeacherImport implements ToModel, SkipsOnFailure, SkipsOnError, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $row_num;
    public $request;
    public $created_file;
    private $failures = [];
    private $created_rows_count = 0;
    private $updated_rows_count = 0;
    private $deleted_rows_count = 0;
    private $failed_rows_count = 0;
    private $duplicateEmails = [];
    private $error = null;

    public function __construct(Request $request, $created_file)
    {
        $this->request = $request;
        $this->row_num = 1;
        $this->created_file = $created_file;
    }

    public function model(array $row)
    {
        if (isset($row['Email']) && is_null($row['Email']))
        {
            $row['Email'] = $this->generateEmail($row);
        }else{
            $row['Email'] = strtolower($row['Email']);
        }
        $teacher = Teacher::query()->where('email', strtolower($row['Email']))->first();
        if ($teacher && $teacher->school_id != $this->created_file->school_id)
        {
            $row['Email'] = $this->generateEmail($row);
            $teacher = Teacher::query()->where('email', strtolower($row['Email']))->first();
            while ($teacher && ($teacher->school_id != $this->created_file->school_id))
            {
                $row['Email'] = $this->generateEmail($row);
                $teacher = Teacher::query()->where('email', strtolower($row['Email']))->first();
            }
        }
        if ($teacher) {
            $this->updated_rows_count++;
            $teacher->update([
                'name' => $row['Name'],
                'email' => strtolower($row['Email']),
                'password' => bcrypt('123456'),
                'school_id' => $this->request->get('school_id'),
                'active' => 1,
                'approved' => 1,
                'mobile' => isset($row['Mobile']) ? $row['Mobile']:'0500000000',
            ]);
        }else{
            $teacher = Teacher::query()->create([
                'name' => $row['Name'],
                'email' => strtolower($row['Email']),
                'password' => bcrypt('123456'),
                'school_id' => $this->request->get('school_id'),
                'active' => 1,
                'approved' => 1,
                'mobile' => isset($row['Mobile']) ? $row['Mobile']:'0500000000',
                'import_file_id' => $this->created_file->id,
            ]);
            $this->created_rows_count++;
        }
        return $teacher;
    }
    public function rules(): array
    {
        return [
            'Name' => 'required',
            'Email' => 'required',
            'Mobile' => 'nullable',
        ];
    }

    public function getUpdatedRowsCount(): int
    {
        return $this->updated_rows_count;
    }
    public function getFailedRowCount(): int
    {
        return $this->failed_rows_count;
    }
    public function getDeletedRowsCount(): int
    {
        return $this->deleted_rows_count;
    }
    public function getRowsCount(): int
    {
        return $this->created_rows_count;
    }

    public function getError()
    {
        return $this->error;
    }
    public function getFailures(): array
    {
        return $this->failures;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures[$failure->row()][] = $failure->errors()[0];
        }
        $this->failed_rows_count++;    }


    public function onError(Throwable $e)
    {
        $this->error = $e->getMessage();
    }

    private function generateEmail($row)
    {
        $row['Name'] = str_replace('  ', ' ', trim($row['Name']));
        //explode $row['Name'] to get first name and last name
        $name = explode(' ', $row['Name']);
        $first_name = $name[0];
        $last_name = $name[count($name) - 1];
        //check if first name and last name exists and check sum length of them  more than 25 characters
        if (count($name) > 2 && (strlen($first_name) + strlen($last_name) >= 25)) {
            $email = $first_name . $last_name . '-' . rand(1, 1000) . $this->request->get('last_of_email');
        } elseif (count($name) > 1 && (strlen($first_name) + strlen($last_name) >= 25)) {
            $email = $name[0] . $name[1] . '-' . rand(1, 1000) . $this->request->get('last_of_email');
        } else {
            $email = $name[0] . '-' . rand(1, 1000) . $this->request->get('last_of_email');
        }
        return $email;
    }

}
