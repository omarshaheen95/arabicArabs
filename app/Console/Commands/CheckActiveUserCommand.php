<?php

namespace App\Console\Commands;

use App\Models\TeacherStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckActiveUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Expired Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $users = User::query()
//            ->where('active_to', '<=', Carbon::now()->subDays(90))
//            ->with(['teacherUser'])
//            ->limit(1000)
//            ->get();//->get();
//        Log::alert('Expired User : '. $users->count());
//        $teachers = collect();
//        foreach($users as $user)
//        {
//            if ($user->teacherUser)
//            {
//                $teachers->push($user->teacherUser->teacher_id);
//            }
//            $user->forceDelete();
//        }
//        $teachers = $teachers->unique()->values()->all();
//        foreach ($teachers as $teacher)
//        {
//            updateTeacherStatistics($teacher);
//        }
//        Log::alert('Teachers : '. count($teachers));
        return true;
    }
}
