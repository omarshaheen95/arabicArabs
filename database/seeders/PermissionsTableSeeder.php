<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
//        $permissions = [
//            ['name' => 'show users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'add users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'edit users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'review users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'users story review', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'users login', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'delete users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'restore deleted users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'export users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'show deleted users', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'assign teacher', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'unassign teacher', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'users activation', 'guard_name' => 'manager', 'group' => 'users'],
//            ['name' => 'update users grade', 'guard_name' => 'manager', 'group' => 'users'],
//
//
//            ['name' => 'show supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
//            ['name' => 'add supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
//            ['name' => 'edit supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
//            ['name' => 'delete supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
//            ['name' => 'export supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
//            ['name' => 'supervisors login', 'guard_name' => 'manager', 'group' => 'supervisors'],
//            ['name' => 'supervisors activation', 'guard_name' => 'manager', 'group' => 'supervisors'],
//
//            ['name' => 'show managers', 'guard_name' => 'manager', 'group' => 'managers'],
//            ['name' => 'add managers', 'guard_name' => 'manager', 'group' => 'managers'],
//            ['name' => 'edit managers', 'guard_name' => 'manager', 'group' => 'managers'],
//            ['name' => 'delete managers', 'guard_name' => 'manager', 'group' => 'managers'],
//            ['name' => 'export managers', 'guard_name' => 'manager', 'group' => 'managers'],
//            ['name' => 'edit managers permissions', 'guard_name' => 'manager', 'group' => 'managers'],
//
//
//
//            ['name' => 'show schools', 'guard_name' => 'manager', 'group' => 'schools'],
//            ['name' => 'add schools', 'guard_name' => 'manager', 'group' => 'schools'],
//            ['name' => 'edit schools', 'guard_name' => 'manager', 'group' => 'schools'],
//            ['name' => 'delete schools', 'guard_name' => 'manager', 'group' => 'schools'],
//            ['name' => 'export schools', 'guard_name' => 'manager', 'group' => 'schools'],
//            ['name' => 'school login', 'guard_name' => 'manager', 'group' => 'schools'],
//            ['name' => 'school activation', 'guard_name' => 'manager', 'group' => 'schools'],
//
//
//            ['name' => 'show teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'add teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'edit teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'delete teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'export teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'teacher login', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'teachers activation', 'guard_name' => 'manager', 'group' => 'teachers'],
//            ['name' => 'teacher users unsigned', 'guard_name' => 'manager', 'group' => 'teachers'],
//
//
//
//            ['name' => 'show activity logs', 'guard_name' => 'manager', 'group' => 'activity_logs'],
//            ['name' => 'delete activity logs', 'guard_name' => 'manager', 'group' => 'activity_logs'],
//
//            ['name' => 'show settings', 'guard_name' => 'manager', 'group' => 'settings'],
//            ['name' => 'edit settings', 'guard_name' => 'manager', 'group' => 'settings'],
//
//            ['name' => 'show translation', 'guard_name' => 'manager', 'group' => 'translation'],
//            ['name' => 'edit translation', 'guard_name' => 'manager', 'group' => 'translation'],
//
//            ['name' => 'show statistics', 'guard_name' => 'manager', 'group' => 'dashboard'],
//            ['name' => 'show login sessions', 'guard_name' => 'manager', 'group' => 'login_sessions'],
//
//            ['name' => 'import files', 'guard_name' => 'manager', 'group' => 'import_files'],
//            ['name' => 'delete import files', 'guard_name' => 'manager', 'group' => 'import_files'],
//
//            ['name' => 'show years', 'guard_name' => 'manager', 'group' => 'years'],
//            ['name' => 'add years', 'guard_name' => 'manager', 'group' => 'years'],
//            ['name' => 'edit years', 'guard_name' => 'manager', 'group' => 'years'],
//            ['name' => 'delete years', 'guard_name' => 'manager', 'group' => 'years'],
//
//            ['name' => 'show packages', 'guard_name' => 'manager', 'group' => 'packages'],
//            ['name' => 'add packages', 'guard_name' => 'manager', 'group' => 'packages'],
//            ['name' => 'edit packages', 'guard_name' => 'manager', 'group' => 'packages'],
//            ['name' => 'delete packages', 'guard_name' => 'manager', 'group' => 'packages'],
//
//            ['name' => 'teacher tracking', 'guard_name' => 'manager', 'group' => 'teacher_tracking'],
//            ['name' => 'teacher tracking report', 'guard_name' => 'manager', 'group' => 'teacher_tracking'],
//
//
//            ['name' => 'show lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'add lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'edit lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'delete lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'edit lesson learn', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'lesson review', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'export lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//
//            ['name' => 'show lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'edit lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'delete lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],
//
//            ['name' => 'show lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'edit lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'delete lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'show hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'add hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'delete hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//            ['name' => 'export hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
//
//            ['name' => 'show stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'add stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'edit stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'delete stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'edit story assessment', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'show hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'add hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'delete hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
//            ['name' => 'export hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
//
//            ['name' => 'show lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
//            ['name' => 'add lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
//            ['name' => 'delete lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
//            ['name' => 'export lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
//
//            ['name' => 'show story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
//            ['name' => 'add story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
//            ['name' => 'delete story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
//            ['name' => 'export story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
//
//            ['name' => 'show user works', 'guard_name' => 'manager', 'group' => 'marking'],
//            ['name' => 'marking user works', 'guard_name' => 'manager', 'group' => 'marking'],
//            ['name' => 'delete user works', 'guard_name' => 'manager', 'group' => 'marking'],
//            ['name' => 'export user works', 'guard_name' => 'manager', 'group' => 'marking'],
//
//            ['name' => 'show user records', 'guard_name' => 'manager', 'group' => 'marking'],
//            ['name' => 'marking user records', 'guard_name' => 'manager', 'group' => 'marking'],
//            ['name' => 'delete user records', 'guard_name' => 'manager', 'group' => 'marking'],
//            ['name' => 'export user records', 'guard_name' => 'manager', 'group' => 'marking'],
//
//            ['name' => 'show lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
//            ['name' => 'delete lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
//            ['name' => 'lesson tests certificate', 'guard_name' => 'manager', 'group' => 'users_tests'],
//            ['name' => 'export lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
//
//            ['name' => 'show story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
//            ['name' => 'delete story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
//            ['name' => 'story tests certificate', 'guard_name' => 'manager', 'group' => 'users_tests'],
//            ['name' => 'export story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
//
//            ['name' => 'show motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
//            ['name' => 'delete motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
//            ['name' => 'add motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
//            ['name' => 'export motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
//
//        ];

        $manager = [
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
            ['name' => 'assign users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'unassign users', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'users activation', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'update users grade', 'guard_name' => 'manager', 'group' => 'users'],
            ['name' => 'reset users passwords', 'guard_name' => 'manager', 'group' => 'users'],
//
//
            ['name' => 'show supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'add supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'edit supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'edit supervisors permissions', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'delete supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'export supervisors', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'supervisors login', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'supervisors activation', 'guard_name' => 'manager', 'group' => 'supervisors'],
            ['name' => 'reset supervisors passwords', 'guard_name' => 'manager', 'group' => 'supervisors'],

            ['name' => 'show managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'add managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'edit managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'delete managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'export managers', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'edit managers permissions', 'guard_name' => 'manager', 'group' => 'managers'],
            ['name' => 'reset managers passwords', 'guard_name' => 'manager', 'group' => 'managers'],


            ['name' => 'show schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'add schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'edit schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'edit schools permissions', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'delete schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'export schools', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'school login', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'school activation', 'guard_name' => 'manager', 'group' => 'schools'],
            ['name' => 'reset schools passwords', 'guard_name' => 'manager', 'group' => 'schools'],

            ['name' => 'show teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'add teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'edit teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'edit teachers permissions', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'delete teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'export teachers', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'teacher login', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'teachers activation', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'teacher users unsigned', 'guard_name' => 'manager', 'group' => 'teachers'],
            ['name' => 'reset teachers passwords', 'guard_name' => 'manager', 'group' => 'teachers'],

            ['name' => 'teacher tracking', 'guard_name' => 'manager', 'group' => 'teacher_tracking'],
            ['name' => 'teacher tracking report', 'guard_name' => 'manager', 'group' => 'teacher_tracking'],

            ['name' => 'show activity logs', 'guard_name' => 'manager', 'group' => 'activity_logs'],
            ['name' => 'delete activity logs', 'guard_name' => 'manager', 'group' => 'activity_logs'],

            ['name' => 'show settings', 'guard_name' => 'manager', 'group' => 'settings'],
            ['name' => 'edit settings', 'guard_name' => 'manager', 'group' => 'settings'],

            ['name' => 'show translation', 'guard_name' => 'manager', 'group' => 'translation'],
            ['name' => 'edit translation', 'guard_name' => 'manager', 'group' => 'translation'],

            ['name' => 'show statistics', 'guard_name' => 'manager', 'group' => 'dashboard'],
            ['name' => 'show login sessions', 'guard_name' => 'manager', 'group' => 'login_sessions'],
            ['name' => 'export login sessions', 'guard_name' => 'manager', 'group' => 'login_sessions'],

            ['name' => 'import files', 'guard_name' => 'manager', 'group' => 'import_files'],
            ['name' => 'delete import files', 'guard_name' => 'manager', 'group' => 'import_files'],

            ['name' => 'show years', 'guard_name' => 'manager', 'group' => 'years'],
            ['name' => 'add years', 'guard_name' => 'manager', 'group' => 'years'],
            ['name' => 'edit years', 'guard_name' => 'manager', 'group' => 'years'],
            ['name' => 'delete years', 'guard_name' => 'manager', 'group' => 'years'],

            ['name' => 'show levels', 'guard_name' => 'manager', 'group' => 'levels'],
            ['name' => 'add levels', 'guard_name' => 'manager', 'group' => 'levels'],
            ['name' => 'edit levels', 'guard_name' => 'manager', 'group' => 'levels'],
            ['name' => 'delete levels', 'guard_name' => 'manager', 'group' => 'levels'],
            ['name' => 'export levels', 'guard_name' => 'manager', 'group' => 'levels'],

            ['name' => 'show packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'add packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'edit packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'export packages', 'guard_name' => 'manager', 'group' => 'packages'],
            ['name' => 'delete packages', 'guard_name' => 'manager', 'group' => 'packages'],


            ['name' => 'show lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'add lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'export lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lesson content', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'edit lesson assessment', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'lesson review', 'guard_name' => 'manager', 'group' => 'lessons'],

            ['name' => 'show and edit lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete lesson training', 'guard_name' => 'manager', 'group' => 'lessons'],


            ['name' => 'show hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'hide lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'delete hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],
            ['name' => 'export hidden lessons', 'guard_name' => 'manager', 'group' => 'lessons'],

            ['name' => 'show stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'add stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'edit stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'delete stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'export stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'edit story assessment', 'guard_name' => 'manager', 'group' => 'stories'],

            ['name' => 'show hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'hide stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'delete hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],
            ['name' => 'export hidden stories', 'guard_name' => 'manager', 'group' => 'stories'],

            ['name' => 'show lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
            ['name' => 'add lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
            ['name' => 'delete lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],
            ['name' => 'export lesson assignments', 'guard_name' => 'manager', 'group' => 'lesson_assignments'],

            ['name' => 'show user lesson assignments', 'guard_name' => 'manager', 'group' => 'user_lesson_assignments'],
            ['name' => 'edit user lesson assignments', 'guard_name' => 'manager', 'group' => 'user_lesson_assignments'],
            ['name' => 'delete user lesson assignments', 'guard_name' => 'manager', 'group' => 'user_lesson_assignments'],
            ['name' => 'export user lesson assignments', 'guard_name' => 'manager', 'group' => 'user_lesson_assignments'],

            ['name' => 'show story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
            ['name' => 'add story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
            ['name' => 'delete story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],
            ['name' => 'export story assignments', 'guard_name' => 'manager', 'group' => 'story_assignments'],

            ['name' => 'show user story assignments', 'guard_name' => 'manager', 'group' => 'user_story_assignments'],
            ['name' => 'edit user story assignments', 'guard_name' => 'manager', 'group' => 'user_story_assignments'],
            ['name' => 'delete user story assignments', 'guard_name' => 'manager', 'group' => 'user_story_assignments'],
            ['name' => 'export user story assignments', 'guard_name' => 'manager', 'group' => 'user_story_assignments'],


            ['name' => 'show user works', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'marking user works', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'delete user works', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'export user works', 'guard_name' => 'manager', 'group' => 'marking'],

            ['name' => 'show user records', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'marking user records', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'delete user records', 'guard_name' => 'manager', 'group' => 'marking'],
            ['name' => 'export user records', 'guard_name' => 'manager', 'group' => 'marking'],

            ['name' => 'show lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'correcting lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'delete lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'lesson tests certificate', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'export lesson tests', 'guard_name' => 'manager', 'group' => 'users_tests'],

            ['name' => 'show story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'correcting story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'delete story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'story tests certificate', 'guard_name' => 'manager', 'group' => 'users_tests'],
            ['name' => 'export story tests', 'guard_name' => 'manager', 'group' => 'users_tests'],

            ['name' => 'show roles', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'add roles', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'edit roles', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'delete roles', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'show permissions', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'add permissions', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'edit permissions', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'delete permissions', 'guard_name' => 'manager', 'group' => 'role_and_permission'],
            ['name' => 'users roles and permissions', 'guard_name' => 'manager', 'group' => 'role_and_permission'],


            ['name' => 'show motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
            ['name' => 'delete motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
            ['name' => 'add motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],
            ['name' => 'export motivational certificate', 'guard_name' => 'manager', 'group' => 'motivational_certificate'],

            ['name' => 'usage report', 'guard_name' => 'manager', 'group' => 'reports'],

            ['name' => 'show achievement levels', 'guard_name' => 'manager', 'group' => 'achievement_levels'],
            ['name' => 'add achievement levels', 'guard_name' => 'manager', 'group' => 'achievement_levels'],
            ['name' => 'edit achievement levels', 'guard_name' => 'manager', 'group' => 'achievement_levels'],
            ['name' => 'delete achievement levels', 'guard_name' => 'manager', 'group' => 'achievement_levels'],


        ];

        $school = [

            ['name' => 'show users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'add users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'edit users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'review users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'users story review', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'users login', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'delete users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'export users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'assign users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'unassign users', 'guard_name' => 'school', 'group' => 'users'],
            ['name' => 'reset users passwords', 'guard_name' => 'school', 'group' => 'users'],

            ['name' => 'show teachers', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'add teachers', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'edit teachers', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'edit teachers permissions', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'delete teachers', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'export teachers', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'teacher login', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'teachers activation', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'teacher users unsigned', 'guard_name' => 'school', 'group' => 'teachers'],
            ['name' => 'reset teachers passwords', 'guard_name' => 'school', 'group' => 'teachers'],

            ['name' => 'show supervisors', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'add supervisors', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'edit supervisors', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'edit supervisors permissions', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'delete supervisors', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'export supervisors', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'supervisors login', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'supervisors activation', 'guard_name' => 'school', 'group' => 'supervisors'],
            ['name' => 'reset supervisors passwords', 'guard_name' => 'school', 'group' => 'supervisors'],

            ['name' => 'teacher tracking', 'guard_name' => 'school', 'group' => 'teacher_tracking'],
            ['name' => 'teacher tracking report', 'guard_name' => 'school', 'group' => 'teacher_tracking'],


            ['name' => 'show hidden lessons', 'guard_name' => 'school', 'group' => 'lessons'],
            ['name' => 'hide lessons', 'guard_name' => 'school', 'group' => 'lessons'],
            ['name' => 'delete hidden lessons', 'guard_name' => 'school', 'group' => 'lessons'],
            ['name' => 'export hidden lessons', 'guard_name' => 'school', 'group' => 'lessons'],


            ['name' => 'show hidden stories', 'guard_name' => 'school', 'group' => 'stories'],
            ['name' => 'hide stories', 'guard_name' => 'school', 'group' => 'stories'],
            ['name' => 'delete hidden stories', 'guard_name' => 'school', 'group' => 'stories'],
            ['name' => 'export hidden stories', 'guard_name' => 'school', 'group' => 'stories'],

            ['name' => 'show lesson assignments', 'guard_name' => 'school', 'group' => 'lesson_assignments'],
            ['name' => 'add lesson assignments', 'guard_name' => 'school', 'group' => 'lesson_assignments'],
            ['name' => 'delete lesson assignments', 'guard_name' => 'school', 'group' => 'lesson_assignments'],
            ['name' => 'export lesson assignments', 'guard_name' => 'school', 'group' => 'lesson_assignments'],

            ['name' => 'show user lesson assignments', 'guard_name' => 'school', 'group' => 'user_lesson_assignments'],
            ['name' => 'edit user lesson assignments', 'guard_name' => 'school', 'group' => 'user_lesson_assignments'],
            ['name' => 'delete user lesson assignments', 'guard_name' => 'school', 'group' => 'user_lesson_assignments'],
            ['name' => 'export user lesson assignments', 'guard_name' => 'school', 'group' => 'user_lesson_assignments'],


            ['name' => 'show story assignments', 'guard_name' => 'school', 'group' => 'story_assignments'],
            ['name' => 'add story assignments', 'guard_name' => 'school', 'group' => 'story_assignments'],
            ['name' => 'delete story assignments', 'guard_name' => 'school', 'group' => 'story_assignments'],
            ['name' => 'export story assignments', 'guard_name' => 'school', 'group' => 'story_assignments'],

            ['name' => 'show user story assignments', 'guard_name' => 'school', 'group' => 'user_story_assignments'],
            ['name' => 'edit user story assignments', 'guard_name' => 'school', 'group' => 'user_story_assignments'],
            ['name' => 'delete user story assignments', 'guard_name' => 'school', 'group' => 'user_story_assignments'],
            ['name' => 'export user story assignments', 'guard_name' => 'school', 'group' => 'user_story_assignments'],


            ['name' => 'show user works', 'guard_name' => 'school', 'group' => 'marking'],
            ['name' => 'marking user works', 'guard_name' => 'school', 'group' => 'marking'],
            ['name' => 'delete user works', 'guard_name' => 'school', 'group' => 'marking'],
            ['name' => 'export user works', 'guard_name' => 'school', 'group' => 'marking'],

            ['name' => 'show user records', 'guard_name' => 'school', 'group' => 'marking'],
            ['name' => 'marking user records', 'guard_name' => 'school', 'group' => 'marking'],
            ['name' => 'delete user records', 'guard_name' => 'school', 'group' => 'marking'],
            ['name' => 'export user records', 'guard_name' => 'school', 'group' => 'marking'],

            ['name' => 'show lesson tests', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'correcting lesson tests', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'delete lesson tests', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'lesson tests certificate', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'export lesson tests', 'guard_name' => 'school', 'group' => 'users_tests'],

            ['name' => 'show story tests', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'correcting story tests', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'delete story tests', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'story tests certificate', 'guard_name' => 'school', 'group' => 'users_tests'],
            ['name' => 'export story tests', 'guard_name' => 'school', 'group' => 'users_tests'],

            ['name' => 'show motivational certificate', 'guard_name' => 'school', 'group' => 'motivational_certificate'],
            ['name' => 'delete motivational certificate', 'guard_name' => 'school', 'group' => 'motivational_certificate'],
            ['name' => 'add motivational certificate', 'guard_name' => 'school', 'group' => 'motivational_certificate'],
            ['name' => 'export motivational certificate', 'guard_name' => 'school', 'group' => 'motivational_certificate'],

            ['name' => 'usage report', 'guard_name' => 'school', 'group' => 'reports'],

        ];

        $teacher = [
            ['name' => 'show users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'add users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'edit users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'review users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'users story review', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'users login', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'assign users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'unassign users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'export users', 'guard_name' => 'teacher', 'group' => 'users'],
            ['name' => 'reset users passwords', 'guard_name' => 'teacher', 'group' => 'users'],


            ['name' => 'show statistics', 'guard_name' => 'teacher', 'group' => 'dashboard'],


            ['name' => 'show lesson assignments', 'guard_name' => 'teacher', 'group' => 'lesson_assignments'],
            ['name' => 'add lesson assignments', 'guard_name' => 'teacher', 'group' => 'lesson_assignments'],
            ['name' => 'delete lesson assignments', 'guard_name' => 'teacher', 'group' => 'lesson_assignments'],
            ['name' => 'export lesson assignments', 'guard_name' => 'teacher', 'group' => 'lesson_assignments'],

            ['name' => 'show user lesson assignments', 'guard_name' => 'teacher', 'group' => 'user_lesson_assignments'],
            ['name' => 'edit user lesson assignments', 'guard_name' => 'teacher', 'group' => 'user_lesson_assignments'],
            ['name' => 'delete user lesson assignments', 'guard_name' => 'teacher', 'group' => 'user_lesson_assignments'],
            ['name' => 'export user lesson assignments', 'guard_name' => 'teacher', 'group' => 'user_lesson_assignments'],


            ['name' => 'show story assignments', 'guard_name' => 'teacher', 'group' => 'story_assignments'],
            ['name' => 'add story assignments', 'guard_name' => 'teacher', 'group' => 'story_assignments'],
            ['name' => 'delete story assignments', 'guard_name' => 'teacher', 'group' => 'story_assignments'],
            ['name' => 'export story assignments', 'guard_name' => 'teacher', 'group' => 'story_assignments'],

            ['name' => 'show user story assignments', 'guard_name' => 'teacher', 'group' => 'user_story_assignments'],
            ['name' => 'edit user story assignments', 'guard_name' => 'teacher', 'group' => 'user_story_assignments'],
            ['name' => 'delete user story assignments', 'guard_name' => 'teacher', 'group' => 'user_story_assignments'],
            ['name' => 'export user story assignments', 'guard_name' => 'teacher', 'group' => 'user_story_assignments'],

            ['name' => 'show user works', 'guard_name' => 'teacher', 'group' => 'marking'],
            ['name' => 'marking user works', 'guard_name' => 'teacher', 'group' => 'marking'],
            ['name' => 'delete user works', 'guard_name' => 'teacher', 'group' => 'marking'],
            ['name' => 'export user works', 'guard_name' => 'teacher', 'group' => 'marking'],

            ['name' => 'show user records', 'guard_name' => 'teacher', 'group' => 'marking'],
            ['name' => 'marking user records', 'guard_name' => 'teacher', 'group' => 'marking'],
            ['name' => 'delete user records', 'guard_name' => 'teacher', 'group' => 'marking'],
            ['name' => 'export user records', 'guard_name' => 'teacher', 'group' => 'marking'],

            ['name' => 'show lesson tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'correcting lesson tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'delete lesson tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'lesson tests certificate', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'export lesson tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],

            ['name' => 'show story tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'correcting story tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'delete story tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'story tests certificate', 'guard_name' => 'teacher', 'group' => 'users_tests'],
            ['name' => 'export story tests', 'guard_name' => 'teacher', 'group' => 'users_tests'],

            ['name' => 'show motivational certificate', 'guard_name' => 'teacher', 'group' => 'motivational_certificate'],
            ['name' => 'delete motivational certificate', 'guard_name' => 'teacher', 'group' => 'motivational_certificate'],
            ['name' => 'add motivational certificate', 'guard_name' => 'teacher', 'group' => 'motivational_certificate'],
            ['name' => 'export motivational certificate', 'guard_name' => 'teacher', 'group' => 'motivational_certificate'],


        ];

        $supervisor = [
            ['name' => 'show users', 'guard_name' => 'supervisor', 'group' => 'users'],
            ['name' => 'edit users', 'guard_name' => 'supervisor', 'group' => 'users'],
            ['name' => 'review users', 'guard_name' => 'supervisor', 'group' => 'users'],
            ['name' => 'users story review', 'guard_name' => 'supervisor', 'group' => 'users'],
            ['name' => 'export users', 'guard_name' => 'supervisor', 'group' => 'users'],
            ['name' => 'show statistics', 'guard_name' => 'supervisor', 'group' => 'dashboard'],

            ['name' => 'show teachers', 'guard_name' => 'supervisor', 'group' => 'teachers'],
            ['name' => 'export teachers', 'guard_name' => 'supervisor', 'group' => 'teachers'],
            ['name' => 'teacher tracking', 'guard_name' => 'supervisor', 'group' => 'teacher_tracking'],
            ['name' => 'teacher tracking report', 'guard_name' => 'supervisor', 'group' => 'teacher_tracking'],

            ['name' => 'show user records', 'guard_name' => 'supervisor', 'group' => 'marking'],
            ['name' => 'export user records', 'guard_name' => 'supervisor', 'group' => 'marking'],
            ['name' => 'show user works', 'guard_name' => 'supervisor', 'group' => 'marking'],
            ['name' => 'export user works', 'guard_name' => 'supervisor', 'group' => 'marking'],

            ['name' => 'show lesson tests', 'guard_name' => 'supervisor', 'group' => 'users_tests'],
            ['name' => 'export lesson tests', 'guard_name' => 'supervisor', 'group' => 'users_tests'],

            ['name' => 'show story tests', 'guard_name' => 'supervisor', 'group' => 'users_tests'],
            ['name' => 'export story tests', 'guard_name' => 'supervisor', 'group' => 'users_tests'],


            ['name' => 'show lesson assignments', 'guard_name' => 'supervisor', 'group' => 'lesson_assignments'],
            ['name' => 'export lesson assignments', 'guard_name' => 'supervisor', 'group' => 'lesson_assignments'],

            ['name' => 'show user lesson assignments', 'guard_name' => 'supervisor', 'group' => 'user_lesson_assignments'],
            ['name' => 'export user lesson assignments', 'guard_name' => 'supervisor', 'group' => 'user_lesson_assignments'],

            ['name' => 'show story assignments', 'guard_name' => 'supervisor', 'group' => 'story_assignments'],
            ['name' => 'export story assignments', 'guard_name' => 'supervisor', 'group' => 'story_assignments'],

            ['name' => 'show user story assignments', 'guard_name' => 'supervisor', 'group' => 'user_story_assignments'],
            ['name' => 'export user story assignments', 'guard_name' => 'supervisor', 'group' => 'user_story_assignments'],

        ];

        $permissions = array_merge($manager, $school, $teacher, $supervisor);

//        // Disable foreign key checks
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        Permission::truncate();
//        // Enable foreign key checks
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //insert permissions
        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate($permission, $permission);
        }

//        $manager_role = Role::updateOrCreate(['name' => 'Manager','guard_name' => 'manager']);
//        $manager_role->syncPermissions(collect($manager)->pluck('name'));
//
//        $school_role = Role::updateOrCreate(['name' => 'School','guard_name' => 'school']);
//        $school_role->syncPermissions(collect($school)->pluck('name'));

//        $teacher_role =  Role::updateOrCreate(['name' => 'Teacher','guard_name' => 'teacher']);
//        $teacher_role->syncPermissions(collect($teacher)->pluck('name'));
//
//        $supervisor_role = Role::updateOrCreate(['name' => 'Supervisor','guard_name' => 'supervisor']);
//        $supervisor_role->syncPermissions(collect($supervisor)->pluck('name'));
//
//
        Cache::forget('spatie.permission.cache');
//
//        $manager = \App\Models\Manager::where('email', 'it@abt-assessments.com')
//            ->first();
//        if ($manager) {
//            $manager->givePermissionTo(Permission::query()->where('guard_name', 'manager')->get());
//        }else{
//            $manager = \App\Models\Manager::firstOrCreate([
//                'email' => 'it@abt-assessments.com',
//            ], [
//                'name' => 'Omar Shaheen',
//                'email' => 'it@abt-assessments.com',
//                'password' => bcrypt('123456'),
//            ]);
//
//            $manager->givePermissionTo(Permission::all());
//        }




    }

}
