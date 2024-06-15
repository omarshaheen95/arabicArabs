<?php

namespace Database\Seeders;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * php artisan cache:forget spatie.permission.cache
     *
     * php artisan db:seed --class=PermissionsTableSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Factory $cache)
    {
        $permissions = [
            ['name' => 'show users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'add users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'edit users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'review users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'users story review', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'users login', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'delete users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'restore deleted users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'export users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'show deleted users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'assign teacher', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'unassign teacher', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'users activation', 'guard_name' => 'manager', 'group' => 'users'],


            ['name' => 'show supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'add supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'edit supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'delete supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'export supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'supervisors login', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'supervisors activation', 'guard_name' => 'manager', 'group' => 'supervisors'],

            ['name' => 'show managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'add managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'edit managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'delete managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'export managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'edit managers permissions', 'guard_name' => 'manager', 'group' => 'managers'],



            ['name' => 'show schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'add schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'edit schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'delete schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'export schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'school login', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'school activation', 'guard_name' => 'manager', 'group' => 'schools'],


            ['name' => 'show teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'add teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'edit teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'delete teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'export teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'teacher login', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'teachers activation', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'teacher users unsigned', 'guard_name' => 'manager', 'group' => 'teachers'],



            ['name' => 'show activity logs', 'guard_name' => 'manager', 'group' => 'activity_logs'],
            ['name' => 'delete activity logs', 'guard_name' => 'manager', 'group' => 'activity_logs'],

            ['name' => 'show settings', 'guard_name' => 'manager', 'group' => 'settings'],
            ['name' => 'edit settings', 'guard_name' => 'manager', 'group' => 'settings'],

            ['name' => 'show translation', 'guard_name' => 'manager', 'group' => 'translation'],
            ['name' => 'edit translation', 'guard_name' => 'manager', 'group' => 'translation'],

            ['name' => 'show statistics', 'guard_name' => 'manager', 'group' => 'dashboard'],
            ['name' => 'show login sessions', 'guard_name' => 'manager', 'group' => 'login sessions'],

            ['name' => 'import files', 'guard_name' => 'manager', 'group' => 'import files'],
            ['name' => 'delete import files', 'guard_name' => 'manager', 'group' => 'import files'],

            ['name' => 'show years', 'guard_name' => 'manager', 'group' => 'years'],
            ['name' => 'add years', 'guard_name' => 'manager', 'group' => 'years'],
            ['name' => 'edit years', 'guard_name' => 'manager', 'group' => 'years'],
            ['name' => 'delete years', 'guard_name' => 'manager', 'group' => 'years'],

            ['name' => 'show packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'add packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'edit packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'delete packages', 'guard_name' => 'manager', 'group' => 'packages'],

            ['name' => 'teacher tracking', 'guard_name' => 'manager', 'group' => 'teacher tracking'],
            ['name' => 'teacher tracking report', 'guard_name' => 'manager', 'group' => 'teacher tracking'],


            ['name' => 'show lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'add lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lesson learn', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'lesson review', 'guard_name' => 'manager', 'group' => 'lessons'],

            ['name' => 'show lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],

            ['name' => 'show lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'show hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'add hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'export hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],

            ['name' => 'show stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'add stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'edit stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'delete stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'edit story assessment', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'show hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'add hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'delete hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'export hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],

            ['name' => 'show lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson assignments'],
            ['name' => 'add lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson assignments'],
            ['name' => 'delete lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson assignments'],
            ['name' => 'export lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson assignments'],

            ['name' => 'show story assignments', 'guard_name' => 'manager', 'group' => 'story assignments'],
            ['name' => 'add story assignments', 'guard_name' => 'manager', 'group' => 'story assignments'],
            ['name' => 'delete story assignments', 'guard_name' => 'manager', 'group' => 'story assignments'],
            ['name' => 'export story assignments', 'guard_name' => 'manager', 'group' => 'story assignments'],

            ['name' => 'show user works', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'marking user works', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'delete user works', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'export user works', 'guard_name' => 'manager', 'group' => 'marking'],

            ['name' => 'show user records', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'marking user records', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'delete user records', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'export user records', 'guard_name' => 'manager', 'group' => 'marking'],

            ['name' => 'show lesson tests', 'guard_name' => 'manager', 'group' => 'users tests'],
            ['name' => 'delete lesson tests', 'guard_name' => 'manager', 'group' => 'users tests'],
            ['name' => 'lesson tests certificate', 'guard_name' => 'manager', 'group' => 'users tests'],
            ['name' => 'export lesson tests', 'guard_name' => 'manager', 'group' => 'users tests'],

            ['name' => 'show story tests', 'guard_name' => 'manager', 'group' => 'users tests'],
            ['name' => 'delete story tests', 'guard_name' => 'manager', 'group' => 'users tests'],
            ['name' => 'story tests certificate', 'guard_name' => 'manager', 'group' => 'users tests'],
            ['name' => 'export story tests', 'guard_name' => 'manager', 'group' => 'users tests'],

        ];

        //insert permissions
        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate($permission, $permission);
        }


        $cache->forget('spatie.permission.cache');

        $manager = \App\Models\Manager::firstOrCreate([
            'email' => 'it@abt-assessments.com',
        ], [
            'name' => 'Omar Shaheen',
            'email' => 'it@abt-assessments.com',
            'password' => bcrypt('123456'),
        ]);

        $manager->givePermissionTo(Permission::all());


    }

}
