<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Story;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getLessonsByGrade(Request $request)
    {
        $lessons = Lesson::query()->filter($request)->get();
        $html = '<option><option/>';
        foreach ($lessons as $lesson) {
            $html .= '<option value="'.$lesson->id.'">'.$lesson->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }
    public function getStoriesByGrade(Request $request)
    {
        $stories = Story::query()->filter($request)->get();
        $html = '<option><option/>';
        foreach ($stories as $story) {
            $html .= '<option value="'.$story->id.'">'.$story->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getTeacherBySchool(Request $request,$id)
    {
        $rows = Teacher::query()->where('school_id', $id)->get();
        $html = '<option><option/>';
        foreach ($rows as $row) {
            $html .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getSectionBySchool(Request $request,$id)
    {
        $rows = \App\Models\User::query()
            ->where('school_id', $id)
            ->whereNotNull('section')
            ->select('section')
            ->orderBy('section')
            ->get()
            ->pluck('section')->unique()->values();
        $html = '<option><option/>';
        foreach ($rows as $row) {
            $html .= '<option value="'.$row.'">'.$row.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getSectionByTeacher(Request $request,$id)
    {
        $rows = \App\Models\User::query()
            ->whereHas('teacherUser', function (Builder $query) use ($id) {
                $query->where('teacher_id', $id);
            })
            ->whereNotNull('section')
            ->select('section')
            ->orderBy('section')
            ->get()
            ->pluck('section')->unique()->values();
        $request->get('multiple', 0) ? $html = '':$html = '<option><option/>';
        $selected = $request->get('selected', 0) ? 'selected':'';
        foreach ($rows as $row) {

            $html .= '<option value="'.$row.'" '.$selected .'>'.$row.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getStudentsByGrade(Request $request, $id)
    {
        $rows = User::query()->filter($request)
            ->where(function (Builder $query) use ($id) {
                $query->where('grade_id', $id)->orWhere('alternate_grade_id', $id);
            })
            ->latest()->get();
        if ($rows->count() == 0){
            $html = '';
        }else {
            $html = '<option value="all" selected>' . t('All') . '</option>';
            foreach ($rows as $row) {
                $html .= '<option value="' . $row->id . '" selected>' . $row->name . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }
}
