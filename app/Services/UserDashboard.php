<?php

namespace App\Services;

use App\Models\AchievementLevel;
use App\Models\Lesson;
use App\Models\LoginSession;
use App\Models\User;
use App\Models\UserAchievementLevel;
use App\Models\UserAssignment;
use App\Models\UserStoryAssignment;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboard
{

    public function getData()
    {
        $user = Auth::guard('web')->user();

//        // Get user's achievement data
//        $data['achievementData'] = $this->getUserAchievementData($user->id);
//
//        // Get recent activities
//        $data['recentActivities'] = $this->getRecentActivities($user->id);
//
//        // Get weekly/monthly stats
//        $data['activityStats'] = $this->getActivityStats($user->id);

        $data['user_achievement_level'] = $this->userAchievementLevel($user->id);

        $data['assignments_counts'] = $this->assignmentCounts($user->id);

        $data['latest_lesson'] = $this->getLatestLesson($user->id);

        $data['active_days_count'] = $this->getActiveDaysInMonth($user->id);

        $data['week_days'] = $this->getWeekDaysDetailed();

        $data['streak'] = $this->getStreakData($user->id);

        //notification
        $data['notifications'] = $this->getNotifications();
        $data['unread_notifications_count'] = $this->getNotificationsCount();
        return $data;
    }

    private function userAchievementLevel($userId)
    {
        // Get current active level
        $currentLevel = UserAchievementLevel::with('achievementLevel')
            ->where('user_id', $userId)
            ->where('achieved', false)
            ->first();

        //create level if user not have level
        if (!$currentLevel) {
            $currentLevel = UserAchievementLevel::create([
                'user_id' => $userId,
                'achieved' => false,
                'achievement_level_id' => AchievementLevel::query()->first()->id,
            ]);
            $currentLevel->load('achievementLevel');
        }
        return $currentLevel;
    }

    private function assignmentCounts($userId)
    {
        // Get uncompleted assignments counts
        $uncompleted_lessons = UserAssignment::query()
            ->where('user_id', $userId)
            ->where('completed', 0)
            ->count();

        $uncompleted_stories = UserStoryAssignment::query()
            ->where('user_id', $userId)
            ->where('completed', 0)
            ->has('story')
            ->count();
        return ['lessons_count' => $uncompleted_lessons, 'stories_count' => $uncompleted_stories];
    }

    private function getWeekDaysDetailed(): array
    {
        $days = [];
        $today = Carbon::now();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::SATURDAY);
        $user = Auth::guard('web')->user();

        // Get all login dates for this week
        $loginDates = LoginSession::where('status', 'success')->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->whereBetween('created_at', [
                $startOfWeek->copy()->startOfDay(),
                $startOfWeek->copy()->addDays(6)->endOfDay()
            ])
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->pluck('date')
            ->toArray();

        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $dateString = $currentDay->format('Y-m-d');

            $days[] = [
                'name' => $currentDay->locale('ar')->dayName, // Full day name in Arabic
                'day' => $currentDay->locale('ar')->dayName, // Full day name in Arabic
                'short' => $currentDay->locale('ar')->minDayName, // Short day name in Arabic
                'number' => $currentDay->dayOfWeek, // 0-6 (Sun-Sat)
                'date' => $dateString, // Full date
                'day_of_month' => $currentDay->format('j'), // Day of month (1-31)
                'active' => in_array($dateString, $loginDates)
            ];
        }

        return $days;
    }

    private function getActiveDaysInMonth($userId)
    {
        return LoginSession::where('status', 'success')->where('model_id', $userId)
            ->where('model_type', User::class)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->distinct()
            ->count(DB::raw('DATE(created_at)'));
    }

    private function getLatestLesson($userId)
    {
        $latest_lesson = UserTracker::with('lesson.grade')->where('user_id', $userId)->latest()->first();

        if (!$latest_lesson) {
            return route('lessons.levels');
        }

        // Optimized: get distinct types without loading unnecessary relations
        $lesson_tracker_types = UserTracker::where('user_id', $userId)
            ->where('lesson_id', $latest_lesson->lesson_id)
            ->distinct()
            ->pluck('type')
            ->toArray();

        if (!in_array('learn', $lesson_tracker_types)) {
            return route('lesson.lesson-index', ['id' => $latest_lesson->lesson_id, 'key' => 'learn']);
        } elseif (!in_array('practise', $lesson_tracker_types)) {
            return route('lesson.lesson-index', ['id' => $latest_lesson->lesson_id, 'key' => 'training']);
        } elseif (!in_array('test', $lesson_tracker_types)) {
            return route('lesson.lesson-index', ['id' => $latest_lesson->lesson_id, 'key' => 'test']);
        } else {
            $nextLesson = Lesson::query()
                ->where('grade_id', $latest_lesson->lesson->grade_id)
                ->where('id', '>', $latest_lesson->lesson->id)
                ->orderBy('id')
                ->first();
            if ($nextLesson) {
                return route('lesson.lesson-index', ['id' => $nextLesson->id, 'key' => 'learn']);
            } else {
                return route('lessons.levels');
            }
        }
    }


    /**
     * Get user's current streak data
     */
    private function getStreakData($userId)
    {
        $currentStreak = $this->calculateCurrentStreak($userId);
        $longestStreak = $this->calculateLongestStreak($userId);

        return [
            'current' => $currentStreak,
            'longest' => $longestStreak,
            'weekly_milestone' => $currentStreak >= 7,
            'monthly_milestone' => $currentStreak >= 30,
        ];
    }

    /**
     * Calculate current consecutive login streak
     */
    public function calculateCurrentStreak($userId)
    {
        $today = Carbon::today();

        // Fetch all login dates in one query (last 366 days to be safe)
        $loginDates = LoginSession::where('status', 'success')->where('model_id', $userId)
            ->where('model_type', User::class)
            ->where('created_at', '>=', $today->copy()->subDays(366))
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        if (empty($loginDates)) {
            return 0;
        }

        $todayStr = $today->format('Y-m-d');
        $yesterdayStr = $today->copy()->subDay()->format('Y-m-d');

        // Check if user logged in today or yesterday
        $hasLoginToday = in_array($todayStr, $loginDates);
        $hasLoginYesterday = in_array($yesterdayStr, $loginDates);

        // If no login today or yesterday, streak is broken
        if (!$hasLoginToday && !$hasLoginYesterday) {
            return 0;
        }

        // Start checking from today or yesterday
        $checkDate = $hasLoginToday ? $today->copy() : $today->copy()->subDay();
        $streak = 0;

        // Count consecutive days backwards using the fetched dates
        while ($streak < 366) {
            $checkDateStr = $checkDate->format('Y-m-d');

            if (in_array($checkDateStr, $loginDates)) {
                $streak++;
                $checkDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Calculate longest streak ever
     */
    private function calculateLongestStreak($userId)
    {
        // Get all unique login dates
        $loginDates = LoginSession::where('status', 'success')->where('model_id', $userId)
            ->where('model_type', User::class)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date);
            });

        if ($loginDates->isEmpty()) {
            return 0;
        }

        $longestStreak = 1;
        $currentStreak = 1;

        for ($i = 1; $i < $loginDates->count(); $i++) {
            $diff = $loginDates[$i]->diffInDays($loginDates[$i - 1]);

            if ($diff == 1) {
                // Consecutive day
                $currentStreak++;
                $longestStreak = max($longestStreak, $currentStreak);
            } else {
                // Streak broken
                $currentStreak = 1;
            }
        }

        return $longestStreak;
    }


    /**
     * Handle User Notifications
     */
    private function getNotifications()
    {
        $user = Auth::guard('web')->user();
        // Get user notifications
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? 'No message',
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                    'icon_type' => $notification->data['other']['icon_type'] ?? 'default',
                    'url' => $notification->data['url'] ?? null,
                ];
            });


    }

    private function getNotificationsCount()
    {
        $user = Auth::guard('web')->user();
        return $user->unreadNotifications()->count();
    }


//    private function getUserAchievementData($userId)
//    {
//        // Get current active level
//        $currentLevel = UserAchievementLevel::with('achievementLevel')
//            ->where('user_id', $userId)
//            ->where('achieved', false)
//            ->first();
//
//        if (!$currentLevel) {
//            $currentLevel = UserAchievementLevel::create([
//                'user_id' => $userId,
//                'achieved' => false,
//                'achievement_level_id' => 1,
//            ])  ;
//        }
//
//        // Get all achieved levels
//        $achievedLevels = UserAchievementLevel::with('achievementLevel')
//            ->where('user_id', $userId)
//            ->where('achieved', true)
//            ->orderBy('achieved_at', 'asc')
//            ->get();
//
//        // Get all available levels for progression view
//        $allLevels = AchievementLevel::orderBy('required_points', 'asc')->get();
//
//        // Calculate total points earned
//        $totalPointsEarned = UserAchievementLevel::where('user_id', $userId)
//            ->sum('points');
//
//        // Get current level progress
//        $currentLevelProgress = 0;
//        $nextLevel = null;
//        if ($currentLevel) {
//            $currentLevelProgress = ($currentLevel->points / $currentLevel->achievementLevel->required_points) * 100;
//            $nextLevel = AchievementLevel::where('required_points', '>', $currentLevel->achievementLevel->required_points)
//                ->orderBy('required_points', 'asc')
//                ->first();
//        }
//
//        // Calculate daily points (last 7 days) - optimized with single query using UNION
//        $sevenDaysAgo = Carbon::now()->subDays(7);
//
//        $dailyPointsRaw = DB::table(DB::raw("(
//            SELECT DATE(created_at) as date, points
//            FROM user_trackers
//            WHERE user_id = ? AND created_at >= ? AND points IS NOT NULL
//            UNION ALL
//            SELECT DATE(created_at) as date, points
//            FROM user_tracker_stories
//            WHERE user_id = ? AND created_at >= ? AND points IS NOT NULL
//        ) as combined"))
//            ->selectRaw('date, SUM(points) as total_points')
//            ->groupBy('date')
//            ->orderBy('date')
//            ->setBindings([$userId, $sevenDaysAgo, $userId, $sevenDaysAgo])
//            ->get();
//
//        $dailyPoints = $dailyPointsRaw->pluck('total_points', 'date');
//
//        return [
//            'currentLevel' => $currentLevel,
//            'achievedLevels' => $achievedLevels,
//            'allLevels' => $allLevels,
//            'totalPointsEarned' => $totalPointsEarned,
//            'currentLevelProgress' => round($currentLevelProgress, 1),
//            'nextLevel' => $nextLevel,
//            'dailyPoints' => $dailyPoints,
//            'totalAchievedLevels' => $achievedLevels->count(),
//            'pointsToNextLevel' => $currentLevel ?
//                ($currentLevel->achievementLevel->required_points - $currentLevel->points) : 0
//        ];
//    }
//
//    private function getRecentActivities($userId)
//    {
//        // Get recent lesson activities
//        $lessonActivities = UserTracker::with('lesson.level')
//            ->where('user_id', $userId)
//            ->latest()
//            ->limit(5)
//            ->get()
//            ->map(function ($tracker) {
//                return [
//                    'type' => 'lesson',
//                    'activity_type' => $tracker->type,
//                    'name' => $tracker->lesson->name,
//                    'level' => $tracker->lesson->level->name,
//                    'points' => $tracker->points,
//                    'date' => $tracker->created_at,
//                    'icon' => $this->getActivityIcon($tracker->type)
//                ];
//            });
//
//        // Get recent story activities
//        $storyActivities = UserTrackerStory::with('story')
//            ->where('user_id', $userId)
//            ->latest()
//            ->limit(5)
//            ->get()
//            ->map(function ($tracker) {
//                return [
//                    'type' => 'story',
//                    'activity_type' => $tracker->type,
//                    'name' => $tracker->story->name,
//                    'level' => $tracker->story->grade_name,
//                    'points' => $tracker->points,
//                    'date' => $tracker->created_at,
//                    'icon' => $this->getActivityIcon($tracker->type)
//                ];
//            });
//
//        return $lessonActivities->merge($storyActivities)
//            ->sortByDesc('date')
//            ->take(8)
//            ->values();
//    }
//
//    private function getActivityStats($userId)
//    {
//        $today = Carbon::today();
//        $weekStart = Carbon::now()->startOfWeek();
//        $monthStart = Carbon::now()->startOfMonth();
//
//        // Fetch all data in 2 queries instead of 12
//        $lessonData = UserTracker::where('user_id', $userId)
//            ->where('created_at', '>=', $monthStart)
//            ->selectRaw('created_at, points')
//            ->get();
//
//        $storyData = UserTrackerStory::where('user_id', $userId)
//            ->where('created_at', '>=', $monthStart)
//            ->selectRaw('created_at, points')
//            ->get();
//
//        // Combine both datasets
//        $allData = $lessonData->concat($storyData);
//
//        // Calculate stats for each period
//        $todayEnd = $today->copy()->endOfDay();
//        $now = Carbon::now();
//
//        return [
//            'today' => [
//                'points' => $allData->whereBetween('created_at', [$today, $todayEnd])->sum('points'),
//                'activities' => $allData->whereBetween('created_at', [$today, $todayEnd])->count()
//            ],
//            'week' => [
//                'points' => $allData->whereBetween('created_at', [$weekStart, $now])->sum('points'),
//                'activities' => $allData->whereBetween('created_at', [$weekStart, $now])->count()
//            ],
//            'month' => [
//                'points' => $allData->whereBetween('created_at', [$monthStart, $now])->sum('points'),
//                'activities' => $allData->whereBetween('created_at', [$monthStart, $now])->count()
//            ]
//        ];
//    }
//
//    private function getPointsInPeriod($userId, $start, $end)
//    {
//        $lessonPoints = UserTracker::where('user_id', $userId)
//            ->whereBetween('created_at', [$start, $end])
//            ->sum('points');
//
//        $storyPoints = UserTrackerStory::where('user_id', $userId)
//            ->whereBetween('created_at', [$start, $end])
//            ->sum('points');
//
//        return $lessonPoints + $storyPoints;
//    }
//
//    private function getActivitiesInPeriod($userId, $start, $end)
//    {
//        $lessonActivities = UserTracker::where('user_id', $userId)
//            ->whereBetween('created_at', [$start, $end])
//            ->count();
//
//        $storyActivities = UserTrackerStory::where('user_id', $userId)
//            ->whereBetween('created_at', [$start, $end])
//            ->count();
//
//        return $lessonActivities + $storyActivities;
//    }
//
//    private function getActivityIcon($type)
//    {
//        $icons = [
//            'learn' => '📚',
//            'practise' => '✏️',
//            'test' => '📝',
//            'play' => '🎮',
//            'watching' => '👁️',
//            'reading' => '📖'
//        ];
//
//        return $icons[$type] ?? '📌';
//    }
}
