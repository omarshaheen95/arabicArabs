<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
        ];

        foreach ($grades as $grade)
        {
            Grade::query()->firstOrCreate([
                'grade_number' => $grade,
            ],[
                'name' => "الصف $grade",
                'grade_number' => $grade,
                'reading' => 1,
                'writing' => 1,
                'listening' => 1,
                'speaking' => 1,
                'grammar' => 0,
                'active' => 1,
                'ordered' => 1,
            ]);
        }
    }
}
