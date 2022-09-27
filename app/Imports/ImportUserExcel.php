<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ImportUserExcel implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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

        $grade = abs((int)filter_var($row['grade'], FILTER_SANITIZE_NUMBER_INT));// - 1;
//        if ($grade > 10 && $grade != 15) {
//            $grade = 10;
//        }


//        $email_array = explode('@', $row['name']);
//        $row['email'] = isset($email_array[1]) ? $email_array[1]:$email_array[0];

        if (isset($row['email']) && !empty($row['email'])) {
            $email = $row['email'];
            $user = User::query()->where('email', $email)->first();
        } else {
            $last_email = $this->req->get('last_of_email');

            $emails = explode(' ', $row['name']);

            $row['email'] = strtolower($emails[0]) . $last_email;

            $email = $row['email'];

            $user = User::query()->where('email', $email)->first();
            if ($user) {
                $row['email'] = strtolower($emails[0]) . '-' . strtolower($emails[1]) . $last_email;
                $email = $row['email'];
            }


        }

//        if ($row['grade'] == 15) {
//            $section = 'KG2 YEAR 1';
//        } else {
//            $section = ($row['grade'] + 1) . $row['section'];
//        }

        if (isset($row['section']) && !empty($row['section']))
        {
            $section = $row['section'];
        }else{
            $section = null;
        }

        /*if ($user) {
            $user->update([
                'name' => $row['name'],
                'email' => $email,
                'password' => bcrypt('123456'),
                'school_id' => $this->req->$this->get('school_id),
                'grade' => $grade,//$grade,//////$grade,
                'alternate_grade' => isset($row['alternate_grade']) && $row['alternate_grade'] ? $row['alternate_grade'] : null,
                'active' => 1,
                'type' => 'member',
                'active_from' => now(),
                'active_to' => $this->req->get('active_to'),
                'year_learning' => isset($row['years_learning']) & !empty($row['years_learning']) ? $row['years_learning'] : 1, //$grade,//
                'section' => isset($row['section']) ? $row['section'] : $row['grade'],
                'mobile' => isset($row['mobile']) & !empty($row['mobile']) ? $row['mobile'] : $this->req->get('default_mobile'),
                'country_code' => $this->req->get('country_code'),
                'short_country' => $this->req->get('short_country'),
                'package_id' => $this->req->get('package_id'),
            ]);
        } else {
            $user = User::query()->create([
                'name' => $row['name'],
                'email' => $email,
                'password' => bcrypt('123456'),
                'school_id' => $this->req->$this->get('school_id),
                'grade' => $grade,//$grade,//////$grade,
                'alternate_grade' => isset($row['alternate_grade']) && $row['alternate_grade'] ? $row['alternate_grade'] : null,
                'active' => 1,
                'type' => 'member',
                'active_from' => now(),
                'active_to' => $this->req->get('active_to'),//Carbon::now()->addDays(60),
                'year_learning' => $row['years_learning'] ?? 1, //$grade,//
                'section' => isset($row['section']) ? $row['section'] : $row['grade'],
                'mobile' => isset($row['mobile']) & !empty($row['mobile']) ? $row['mobile'] : $this->req->get('default_mobile'),
                'country_code' => $this->req->get('country_code'),
                'short_country' => $this->req->get('short_country'),
                'package_id' => $this->req->get('package_id'),
            ]);
        }*/


        if ($user) {
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                $email = strtolower($row['email']);
                $email = date('Y') . $email;//.$last_email;
                $user = User::query()->where('email', $email)->first();
                if ($user) {
                    $email = strtolower($row['email']);
                    $email = date('Y') . date('m') . $email;//.$last_email;
                    $user = User::query()->where('email', $email)->first();

                    if ($user) {
                        $email = strtolower($row['email']);
                        $email = date('Y') . '-' . rand(1, 999) . $email;//.$last_email;
                        $user = User::query()->where('email', $email)->first();


                        if ($user) {
                            $this->duplicateEmails[] = $email;
                            $user->update([
                                'name' => $row['name'],
                                'email' => $email,
                                'password' => bcrypt('123456'),
                                'school_id' => $this->req->get('school_id'),
                                'grade_id' => $grade,//$grade,//////$grade,
                                'alternate_alternate_grade_id' => isset($row['alternate_grade']) && $row['alternate_grade'] ? $row['alternate_grade'] : null,
                                'active' => 1,
                                'year_id' => 1,
                                'type' => 'member',
                                'active_from' => now(),
                                'active_to' => $this->req->get('active_to'),//,
                                'section' => $section,//isset($row['section']) && !empty($row['section']) ? $row['section'] : 1,
                                'mobile' => isset($row['mobile']) && !empty($row['mobile']) ? $row['mobile'] : $this->req->get('default_mobile'),
                                'country_code' => $this->req->get('country_code'),
                                'short_country' => $this->req->get('short_country'),
                                'package_id' => $this->req->get('package_id'),
                            ]);
                        } else {
                            $user = false;

                        }
                    } else {
                        $user = false;
                    }
                } else {
                    $user = false;

                }
            } else {
                $user = false;
            }


        } else {
            $user = false;

        }


        if (!$user) {
            $user = User::query()->create([
                'name' => $row['name'],
                'email' => $email,
                'password' => bcrypt('123456'),
                'school_id' => $this->req->get('school_id'),
                'grade_id' => $grade,//$grade,//////$grade,
                'alternate_grade_id' => isset($row['alternate_grade']) && $row['alternate_grade'] ? $row['alternate_grade'] : null,
                'active' => 1,
                'year_id' => 1,
                'type' => 'member',
                'active_from' => now(),
                'active_to' => $this->req->get('active_to'),//,
                'section' => $section, //isset($row['section']) && !empty($row['section']) ? $row['section'] : 1,
                'mobile' => isset($row['mobile']) && !empty($row['mobile']) ? $row['mobile'] : $this->req->get('default_mobile'),
                'country_code' => $this->req->get('country_code'),
                'short_country' => $this->req->get('short_country'),
                'package_id' => $this->req->get('package_id'),
            ]);
        }


        if (isset($row['teacher'])) {
            $teacher = Teacher::query()->where('email', $row['teacher'])->where('school_id', $this->req->get('school_id'))->first();
        } else {
            $teacher = false;
        }


        if ($teacher) {
            TeacherUser::query()->create([
                'teacher_id' => $teacher->id,
                'user_id' => $user->id,
            ]);
        }
        $this->row_num++;
        return $user;
    }

    public function onFailure(Failure ...$failures)
    {
        // TODO: Implement onFailure() method.
    }

    public function getDuplicateEmails(): array
    {
        return $this->duplicateEmails;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
//            'first_name' => 'required',
//            'last_name' => 'required',
//            'email' => 'required',
            'grade' => 'required',
//            'year' => 'required',
//            'phone' => 'required',
//            'years_learning' => 'nullable',
        ];
    }
}
