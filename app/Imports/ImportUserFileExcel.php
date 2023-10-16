<?php

namespace App\Imports;

use App\Models\ImportStudentFile;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Validators\Failure;
HeadingRowFormatter::default('none');

class ImportUserFileExcel implements ToModel, SkipsOnFailure, SkipsOnError, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $file;
    public $abt_id;
    public $update;
    private $created_rows_count = 0;
    private $updated_rows_count = 0;
    private $failed_rows_count = 0;
    private $error = null;
    private $failures = [];
    private $request;

    public function __construct(ImportStudentFile $importStudentFile, $abt_id = null,$request = null)
    {
        $this->file = $importStudentFile;
        $this->abt_id = $abt_id;
        $this->request = $request;
    }

    public function model(array $row)
    {
        $full_name = trim(str_replace('  ', ' ', str_replace(' ', ' ', $row['Name'])));
        if (strlen($full_name) > 25) {
            $array_name = explode(' ', $full_name);
            if (count($array_name) >= 4) {
                $name = $array_name[0] . ' ' . $array_name[1] . ' ' . $array_name[count($array_name) - 2] . ' ' . $array_name[count($array_name) - 1];
            }
            elseif (count($array_name) == 3) {
                $name = $array_name[0] . ' ' . $array_name[1] . ' ' . $array_name[count($array_name) - 1];
            } else {
                $name = $array_name[0] . ' ' . $array_name[1];
                if (strlen($full_name) > 25) {
                    $name = $array_name[0];
                }
            }
        } else {
            $name = $full_name;
        }

        $grade = abs((int)filter_var($row['Grade'], FILTER_SANITIZE_NUMBER_INT));// - 1;
        if ($this->request->get('back_grade', 0) > 0) {
            $grade -= $this->request->get('back_grade', 0);
        }

        if ($grade == 0)
        {
            $grade = 13;
        }




        if (!empty($row['Email'])) {
            $email = trim(str_replace('  ', ' ', str_replace(' ', ' ', $row['Email'])));
            $user = User::query()->where('email', $email)->first();
        } else {
            $last_email = $this->request->get('last_of_email');


            $names = explode(' ', $full_name);

            $row['Email'] = strtolower($names[0]) . $last_email;

            $email = $row['Email'];

            $user = User::query()->where('email', $email)->withTrashed()->first();
            while (!is_null($user)) {
                $number = date('Y') . rand(999, 999999);
                $email = $names[0] . $number .$this->request->get('last_of_email');
                $user = User::query()->where('email', $email)->withTrashed()->first();
            }
        }


        if (!empty($row['Section']))
        {
            $section = $row['Section'];
        }else{
            $section = null;
        }



        if (!$user) {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('123456'),
                'school_id' => $this->request->get('school_id'),
                'grade_id' => $grade,//$grade,//////$grade,
                'alternate_alternate_grade_id' => isset($row['Alternate Grade']) && $row['Alternate Grade'] ? $row['Alternate Grade'] : null,
                'active' => 1,
                'year_id' => 1,
                'type' => 'member',
                'active_from' => now(),
                'active_to' => $this->request->get('active_to'),//,
                'section' => $section, //isset($row['section']) && !empty($row['section']) ? $row['section'] : 1,
                'mobile' => !empty($row['Mobile']) ? $row['Mobile'] : $this->request->get('default_mobile'),
                'country_code' => $this->request->get('country_code'),
                'short_country' => $this->request->get('short_country'),
                'package_id' => $this->request->get('package_id'),
                'import_student_file_id' => $this->file->id,
            ]);
            $this->created_rows_count++;
        }else{
            $user->update([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('123456'),
                'school_id' => $this->request->get('school_id'),
                'grade_id' => $grade,//$grade,//////$grade,
                'alternate_alternate_grade_id' => isset($row['Alternate Grade']) && $row['Alternate Grade'] ? $row['Alternate Grade'] : null,
                'active' => 1,
                'year_id' => 1,
                'type' => 'member',
                'active_from' => now(),
                'active_to' => $this->request->get('active_to'),//,
                'section' => $section, //isset($row['section']) && !empty($row['section']) ? $row['section'] : 1,
                'mobile' => !empty($row['Mobile']) ? $row['Mobile'] : $this->request->get('default_mobile'),
                'country_code' => $this->request->get('country_code'),
                'short_country' => $this->request->get('short_country'),
                'package_id' => $this->request->get('package_id'),
                'import_student_file_id' => $this->file->id,
            ]);
            $this->updated_rows_count++;
        }


        if (isset($row['Teacher'])) {
            $teacher = Teacher::query()->where('email', $row['Teacher'])->where('school_id', $this->request->get('school_id'))->first();
        } else {
            $teacher = false;
        }


        if ($teacher) {
            TeacherUser::query()->create([
                'teacher_id' => $teacher->id,
                'user_id' => $user->id,
            ]);
        }

        return $user;
    }

    /**
     * @return int
     */
    public function getUpdatedRowsCount(): int
    {
        return $this->updated_rows_count;
    }

    /**
     * @return int
     */
    public function getRowsCount(): int
    {
        return $this->created_rows_count;
    }

    /**
     * @return int
     */
    public function getFailedRowCount(): int
    {
        return $this->failed_rows_count;
    }

    /**
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getFailures(): array
    {
        return $this->failures;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures[$failure->row()][] = $failure->errors()[0];
        }
        $this->failed_rows_count++;
    }

    public function onError(\Throwable $e)
    {
        $this->error = $e->getMessage();
    }

    public function rules(): array
    {
        return [
            'Name' => 'required',
            'Email' => 'nullable',
            'Mobile' => 'nullable',
            'Grade' => 'required',
            'Alternate Grade' => 'nullable',
            'Section' => 'nullable',
            'Teacher' => 'nullable',
        ];
    }
}
