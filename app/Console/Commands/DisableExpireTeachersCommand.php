<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Console\Commands;

use App\Models\Teacher;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class DisableExpireTeachersCommand extends Command
{
    protected $signature = 'disable:expire-teachers';

    protected $description = 'Disable Expire Teachers';

    public function handle(): void
    {
        //get teacher does not have user active_to <= Carbon::now()->subDays(7)
//        $teachers = Teacher::query()
//            ->where('id', '!=', 1375)
//            ->where(function (Builder $query){
//            $query->whereDoesntHave('users', function ($query) {
//                $query->whereDate('active_to', '>=', now()->subDays(7));
//            })->orWhereDoesntHave('users');
//        })->where('approved', 1)->update([
//                'approved' => 0,
//                'active' => 0,
//        ]);

//        Log::alert('Expired Teachers : '. $teachers->count());


    }

}
