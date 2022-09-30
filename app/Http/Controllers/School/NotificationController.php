<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\School;
use App\Models\Teacher;
use App\Notifications\ApproveTeacherNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function index()
    {
        $title = t('Show Notifications');
        if (request()->ajax()) {
            $user = Auth::guard('school')->user();
            $notifications = Notification::query()
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', School::class)
                ->latest();

            return DataTables::make($notifications)
                ->escapeColumns([])
                ->addColumn('created_at', function ($notification) {
                    return Carbon::parse($notification->created_at)->toDayDateTimeString();
                })
                ->addColumn('title', function ($notification) {
                    return $notification->title;
                })
                ->addColumn('status', function ($notification) {
                    return is_null($notification->read_at) ? t('Unseen'):t('Seen');
                })

                ->addColumn('actions', function ($notification) {
                    return $notification->action_buttons;
                })
                ->make();
        }
        return view('school.notification.index', compact('title'));
    }

    public function show($id)
    {
        $title = t('Show Notification');
        $user = Auth::guard('school')->user();
        $notification = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', School::class)
            ->find($id);
        if (in_array($notification->type, [ApproveTeacherNotification::class]))
        {
            if(!$notification->seen && $notification->notifiable_id != 0) {
                $notification->update([
                    'read_at' => now(),
                ]);
            }
            $teacher = Teacher::query()->where('id', $notification['data']['others']['id'])->first();
            if ($teacher)
            {
                return redirect()->route('school.teacher.edit', $teacher->id);
            }else{
                return $this->redirectWith(false, 'school.teacher.index', 'Teacher not found');
            }
        }



        return redirect()->back()->with('message', t('notification not found'))->with('m-class', 'error');
    }

    public function destroy($id)
    {
        $user = Auth::guard('school')->user();
        $notification = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', School::class)
            ->find($id);
        $notification->delete();
        return redirect()->back()->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }

    public function readAll()
    {
        $user = Auth::user();
        $notifications = Notification::query()
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', School::class)
            ->update(['read_at' => now()]);
        return redirect()->back()->with('message', t('Successfully raed all'))->with('m-class', 'success');
    }
}
