<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Validators\Failure;

HeadingRowFormatter::default('none');

class UserImport implements ToModel, SkipsOnFailure, SkipsOnError, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    public $row_num;
    public $request;
    private $failures = [];
    private $created_rows_count = 0;
    private $updated_rows_count = 0;
    private $deleted_rows_count = 0;
    private $failed_rows_count = 0;
    private $created_file;
    private $error = null;

    private $duplicateEmails = [];

    public function __construct(Request $request, $created_file)
    {
        $this->request = $request;
        $this->created_file = $created_file;
        $this->row_num = 1;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($this->created_file->process_type == 'delete') {
            $user = User::query()->where('email', $row['Email'])->where('school_id', $this->created_file->school_id)->first();
            if ($user) {
                $user->delete();
                $this->deleted_rows_count++;
            }
        } elseif ($this->created_file->process_type == 'update') {
            $user = User::query()->where('email', $row['Email'])->where('school_id', $this->created_file->school_id)->first();
            if ($user) {
                $user = $this->updateUser($this->created_file, $user, $row);
            }
        } else {
            if (isset($row['Email']) && !is_null($row['Email'])) {
                $user = User::query()->where('email', $row['Email'])->first();
                if ($user && ($user->school_id == $this->created_file->school_id)) {
                    $user = $this->updateUser($this->created_file, $user, $row);
                } else if ($user) {
                    $email = $this->generateEmail($row);
                    $row['Email'] = $email;
                    $user = $this->createUser($this->created_file, $row);
                } else {
                    $row['Email'] = str_replace(' ', '', trim($row['Email']));
                    $user = $this->createUser($this->created_file, $row);
                }
            } else {
                $email = $this->generateEmail($row);
                $row['Email'] = $email;
//                \Log::alert($row);
                $user = $this->createUser($this->created_file, $row);
            }
        }

        return $user;
    }

    public function getUpdatedRowsCount(): int
    {
        return $this->updated_rows_count;
    }

    public function getDeletedRowsCount(): int
    {
        return $this->deleted_rows_count;
    }

    /**
     * @return int
     */
    public function getRowsCount(): int
    {
        return $this->created_rows_count;
    }

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

    /**
     * @return int
     */
    public function getFailedRowCount(): int
    {
        return $this->failed_rows_count;
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
        if ($this->created_file->process_type == 'delete') {
            return [
                'Email' => 'required',
            ];
        } elseif ($this->created_file->process_type == 'update') {
            return [
                'Name' => 'sometimes',
                'Email' => 'sometimes',
                'Mobile' => 'sometimes',
                'Password' => 'sometimes',
                'Grade' => 'required|exists:grades,id',
                'Alternative Grade' => 'nullable|exists:grades,id',
                'Section' => 'sometimes',
                'Learning Years' => 'sometimes|in:' . implode(',', range(0,12)),
                'Active' => 'sometimes|in:1,0',
                'Student ID' => 'sometimes',
                'Teacher' => 'sometimes',
            ];
        } else {
            return [
                'Student ID' => 'required',
                'Name' => 'required',
                'Email' => 'nullable',
                'Mobile' => 'nullable',
                'Password' => 'nullable',
                'Grade' => 'required|exists:grades,id',
                'Alternative Grade' => 'nullable|exists:grades,id',
                'Section' => 'nullable',
                'Learning Years' => 'required',
                'Active' => 'nullable|in:1,0',
                'Teacher' => 'nullable',
            ];
        }
    }

    private function generateEmail($row)
    {
        $row['Name'] = str_replace('  ', ' ', trim($row['Name']));
        //explode $row['Name'] to get first name and last name
        $name = explode(' ', $row['Name']);
        $first_name = $name[0];
        $last_name = $name[count($name) - 1];


        if ($this->request->get('email_structure', 'use_name') == 'use_student_id' && isset($row['Student ID']) && !is_null($row['Student ID'])) {
            $returned_email = strtolower($row['Student ID']) . $this->request->get('last_of_email');
        } else {
            //check if first name and last name exists and check sum length of them  more than 25 characters
            if (count($name) > 2 && (strlen($first_name) + strlen($last_name) <= 25)) {
                $email = $first_name . $last_name . '-' . rand(1, 1000) . $this->request->get('last_of_email');
            } else {
                $email = $name[0] . '-' . rand(1, 1000) . $this->request->get('last_of_email');
            }
            $returned_email = strtolower($email);
        }
        $user = User::query()->where('email', $returned_email)->first();
        while ($user) {
            if (count($name) > 2 && (strlen($first_name) + strlen($last_name) <= 25)) {
                $email = $first_name . $last_name . '-' . rand(1, 1000) . $this->request->get('last_of_email');
            } else {
                $email = $name[0] . '-' . rand(1, 1000) . $this->request->get('last_of_email');
            }
            $returned_email = strtolower($email);
            $user = User::query()->where('email', $returned_email)->first();
        }
        return $returned_email;
    }

    private
    function checkUserNameLength($name)
    {
        $name = str_replace('  ', ' ', trim($name));
        //check name less than or equal 30
        if (strlen($name) <= 30) {
            return $name;
        } else {
            //explode name by space and get first name and last name
            $name = explode(' ', $name);
            $first_name = $name[0];
            $last_name = $name[count($name) - 1];
            //check if first name and last name exists and check sum length of them  not more than 25 characters
            if (count($name) > 2 && (strlen($first_name . ' ' . $name[1] . ' ' . $last_name) <= 25)) {
                return $first_name . ' ' . $name[1] . ' ' . $last_name;
            } elseif (count($name) > 1 && (strlen($first_name) + strlen($last_name) <= 25)) {
                return $name[0] . ' ' . $name[1];
            } else {
                return implode(' ', $name);
            }
        }

    }

    private
    function createUser($file_data, $row)
    {
        $row['Password'] = isset($row['Password']) && !is_null($row['Password']) ? $row['Password'] : '123456';
        $row['Active'] = isset($row['Active']) && !is_null($row['Active']) && in_array($row['Active'], [0, 1]) ? $row['Active'] : 1;
        $grade = $row['Grade'];
        $alternative_grade = isset($row['Alternative Grade']) && !is_null($row['Alternative Grade']) ? $row['Alternative Grade'] : null;

        $grade = abs((int)filter_var($row['Grade'], FILTER_SANITIZE_NUMBER_INT));// - 1;
        if ($this->created_file->other_data['back_grade'] > 0 ) {
            $grade -= $this->created_file->other_data['back_grade'];
        }

        if ($grade == 0)
        {
            $grade = 13;
        }

        $user = User::query()->create([
            'school_id' => $file_data->school_id,
            'name' => $this->checkUserNameLength($row['Name']),
            'email' => strtolower($row['Email']),
            'mobile' => isset($row['Mobile']) && !is_null($row['Mobile']) ? $row['Mobile'] : null,
            'password' => bcrypt($row['Password']),
            'type' => 'member',
            'grade_id' => $grade,
            'alternate_grade_id' => $alternative_grade,
            'section' => isset($row['Section']) && !is_null($row['Section']) ? $row['Section'] : null,
            'year_learning' => isset($row['Learning Years']) && !is_null($row['Learning Years']) ? $row['Learning Years'] : 1,
            'package_id' => $file_data->other_data['package_id'] ?: null,
            'year_id' => $file_data->other_data['year_id'] ?: null,
            'active' => $row['Active'],
            'active_to' => $file_data->other_data['active_to'],
            'active_from' => Carbon::now(),
            'id_number' => isset($row['Student ID']) && !is_null($row['Student ID']) ? $row['Student ID'] : null,
            'import_file_id' => $file_data->id,
            'country_code' => $this->created_file->other_data['country_code'],
            'short_country' => $this->created_file->other_data['short_country'],
        ]);
        //check row if has teacher
        if (isset($row['Teacher']) && !is_null($row['Teacher'])) {
            $teacher = Teacher::query()->where('email', $row['Teacher'])->where('school_id', $file_data->school_id)->first();
            if ($teacher) {
                $user->teacherUser()->create([
                    'teacher_id' => $teacher->id,
                ]);
            }
        }
        $this->created_rows_count++;
        return $user;
    }

    private function updateUser($file_data, $user, $row)
    {
        $grade = isset($row['Grade']) && !is_null($row['Grade']) ? $row['Grade'] : $user->grade;
        $alternative_grade = isset($row['Alternative Grade']) && !is_null($row['Alternative Grade']) ? $row['Alternative Grade'] : $user->alternate_grade;

        $grade = abs((int)filter_var($row['Grade'], FILTER_SANITIZE_NUMBER_INT));// - 1;
        if ($this->created_file->other_data['back_grade'] > 0 ) {
            $grade -= $this->created_file->other_data['back_grade'];
        }

        if ($grade == 0)
        {
            $grade = 13;
        }

        $data = [
            'name' => isset($row['Name']) && !is_null($row['Name']) ? $this->checkUserNameLength($row['Name']) : $user->name,
            'mobile' => isset($row['Mobile']) && !is_null($row['Mobile']) ? $row['Mobile'] : $user->mobile,
            'password' => isset($row['Password']) && !is_null($row['Password']) ? bcrypt($row['Password']) : $user->password,
            'grade_id' => $grade,
            'alternate_grade_id' => $alternative_grade,
            'section' => isset($row['Section']) && !is_null($row['Section']) ? $row['Section'] : $user->section,
            'year_learning' => isset($row['Learning Years']) && !is_null($row['Learning Years']) ? $row['Learning Years'] : $user->year_learning,
            'package_id' => $this->created_file->other_data['package_id'] ?: $user->package_id,
            'year_id' => $this->created_file->other_data['year_id'] ?: $user->year_id,
            'active' => isset($row['Active']) && !is_null($row['Active']) && in_array($row['Active'], [0, 1]) ? $row['Active'] : $user->active,
            'active_to' => $this->created_file->other_data['active_to'],
            'active_from' => Carbon::now(),
            'id_number' => isset($row['Student ID']) && !is_null($row['Student ID']) ? $row['Student ID'] : $user->id_number,
            'country_code' => $this->created_file->other_data['country_code'],
            'short_country' => $this->created_file->other_data['short_country'],
            'import_file_id' => $this->created_file->id,
        ];
        $user->update($data);
        //check row if has teacher
        if (isset($row['Teacher']) && !is_null($row['Teacher'])) {
            $teacher = Teacher::query()->where('email', $row['Teacher'])->where('school_id', $file_data->school_id)->first();
            if ($teacher) {
                if ($user->teacher) {
                    $user->teacherUser()->delete();
                }
                $user->teacherUser()->create([
                    'teacher_id' => $teacher->id,
                ]);
            }
        }
        $this->updated_rows_count++;
        return $user;
    }
}
