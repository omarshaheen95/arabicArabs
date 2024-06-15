<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\LessonRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\MatchResult;
use App\Models\Option;
use App\Models\OptionResult;
use App\Models\QMatch;
use App\Models\Question;
use App\Models\SortResult;
use App\Models\SortWord;
use App\Models\TMatch;
use App\Models\TOption;
use App\Models\TQuestion;
use App\Models\TrueFalse;
use App\Models\TrueFalseResult;
use App\Models\TSortWord;
use App\Models\TTrueFalse;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\Models\Media;
use Yajra\DataTables\DataTables;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show lessons')->only('index');
        $this->middleware('permission:add lessons')->only(['create','store']);
        $this->middleware('permission:edit lessons')->only(['update','edit']);
        $this->middleware('permission:delete lessons')->only('destroy');
        $this->middleware('permission:lesson review')->only('lessonReview');

        $this->middleware('permission:edit lesson learn')->only([
            'lessonLearn','updateLessonLearn','deleteLessonAudio','deleteLessonVideo'
        ]);
    }
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $rows = Lesson::query()->with('grade')->filter($request)
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade->name;
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">' . t('Active') . '</span>' : '<span class="badge badge-danger">' . t('Inactive') . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Lessons');
        $grades = Grade::query()->get();
        $lesson_types = Lesson::lessonTypes();
        $section_types = Lesson::sectionTypes();
        return view('manager.lesson.index', compact('title', 'grades','lesson_types','section_types'));
    }

    public function create()
    {
        $title = t('Add Lesson');
        $grades = Grade::query()->get();
        return view('manager.lesson.edit', compact('title', 'grades'));
    }

    public function store(LessonRequest $request)
    {
        if (in_array($request->get('lesson_type'), ['reading', 'listening']) && $request->get('grade_id') != 13) {
            $request->validate([
                'section_type' => 'required_if:lesson_type,reading,listening',
            ]);
        }
        $data = $request->validated();

        $data['active'] = $request->get('active', false);
        $lesson = Lesson::query()->create($data);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $lesson
                ->addMediaFromRequest('image')
                ->toMediaCollection('imageLessons');
        }

        return redirect()->route('manager.lesson.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit Lesson');
        $lesson = Lesson::query()->with(['media'])->findOrFail($id);
        $grades = Grade::query()->get();
        return view('manager.lesson.edit', compact('lesson', 'grades', 'title'));
    }

    public function update(LessonRequest $request, $id)
    {
        if (in_array($request->get('lesson_type'), ['reading', 'listening']) && $request->get('grade_id') != 13) {
            $request->validate([
                'section_type' => 'required_if:lesson_type,reading,listening',
            ]);
        }
        $lesson = Lesson::query()->findOrFail($id);
        $data = $request->validated();
        $data['active'] = $request->get('active', false);
        if (!in_array($request->get('lesson_type'),['grammar', 'dictation', 'rhetoric'])){
            $data['level']=null;
        }
        $lesson->update($data);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $lesson
                ->addMediaFromRequest('image')
                ->toMediaCollection('imageLessons');
        }
        return redirect()->route('manager.lesson.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        Lesson::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Successfully Deleted'));
    }


    //Learn
    public function lessonLearn($id)
    {
        $lesson = Lesson::query()->with(['media'])->findOrFail($id);
        $title = t('Learning And Training');
        return view('manager.lesson.learn', compact('lesson', 'title'));
    }

    public function updateLessonLearn(Request $request, $id)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $lesson->update([
            'content' => $request->get('content', false),
        ]);
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $lesson
                ->addMediaFromRequest('audio')
                ->toMediaCollection('audioLessons');
        }
        //upload array of videos
        if ($request->hasFile('videos') && $request->file('videos')) {
            foreach ($request->file('videos') as $video) {
                $lesson->addMedia($video)->toMediaCollection('videoLessons');
            }
        }
        //add new video and delete old
        if ($request->hasFile('old_videos') && $request->file('old_videos')) {
            foreach ($request->file('old_videos') as $id=>$video) {
                $this->deleteLessonVideo($id);
                $lesson->addMedia($video)->toMediaCollection('videoLessons');
            }
        }

        //get all videos
        return redirect()->route('manager.lesson.learn', $lesson->id)->with('message', t('Successfully Updated'));
    }

    public function deleteLessonAudio($id)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $lesson->clearMediaCollection('audioLessons');
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function deleteLessonVideo($video_id)
    {
        $video = Media::query()->findOrFail($video_id);
        $video->delete();
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function uploadImageLesson(Request $request)
    {
        if ($request->hasFile('imageFile')) {
            $image = asset($this->uploadFile($request->file('imageFile'), 'lesson_images'));
            return response()->json(["link" => $image]);
        } else {
            return false;
        }
    }

    public function lessonReview($id, $key)
    {
        $lesson = Lesson::query()->with(['grade', 'media'])->findOrFail($id);

        switch ($key) {
            case 'learn':
                return view('general.user.lesson.preview.learn', compact('lesson'));
            case 'training':
                $preview=true;
                $type = 'training';
                $questions = TQuestion::with(['trueFalse','matches.media','options','sortWords','media'])
                    ->whereIn('type',[1,2,3,4])->where('lesson_id', $id)->get();
                return view('general.user.lesson.preview.assessment_and_training', compact('questions', 'lesson','preview','type'));

            case 'test':
                $preview=true;
                $type = 'assessment';
                $questions = Question::with(['trueFalse','matches.media','options','sortWords','media'])->where('lesson_id', $id)->get();
                if ($lesson->lesson_type == 'writing') {
                    return view('general.user.lesson.preview.writing_test', compact('questions', 'lesson'));

                }
                if ($lesson->lesson_type == 'speaking') {
                    return view('general.user.lesson.preview.speaking_test', compact('questions', 'lesson'));

                }
                return view('general.user.lesson.preview.assessment_and_training', compact('questions', 'lesson','preview','type'));
            default:
                return redirect()->route('manager.home');
        }
    }

    public function reCorlessonTest(Request $request)
    {

        $tests = UserTest::query()
            ->whereRelation('lesson', 'grade_id', 13)
            ->whereHas('lesson', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->whereIn('lesson_type', ['reading', 'listening']);
            })
            ->get();
//        dd($tests->count());
        foreach ($tests as $test) {

            $questions = Question::query()->with(['lesson'])->where('lesson_id', $test->lesson_id)->get();

            $total = 0;
            $tf_total = 0;
            $o_total = 0;
            $m_total = 0;
            $s_total = 0;

            foreach ($questions as $question) {
                if ($question->type == 1) {
                    $student_result = TrueFalseResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
                        ->first();
                    $main_result = TrueFalse::query()->where('question_id', $question->id)->first();
                    if (isset($student_result) && isset($main_result) && optional($student_result)->result == optional($main_result)->result) {
                        $total += $question->mark;
                        $tf_total += $question->mark;
                        \Log::warning('TF-QM : ' . $question->mark);
                    }
                }

                if ($question->type == 2) {
                    $student_result = OptionResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
                        ->first();
                    if ($student_result) {
                        $main_result = Option::query()->find($student_result->option_id);
                    }

                    if (isset($student_result) && isset($main_result) && optional($main_result)->result == 1) {
                        $total += $question->mark;
                        $o_total += $question->mark;
                        \Log::warning('C-QM : ' . $question->mark);
                    }

                }

                $match_mark = 0;
                if ($question->type == 3) {
                    $match_results = MatchResult::query()->where('user_test_id', $test->id)->where('question_id', $question->id)
                        ->get();
                    $main_mark = $question->mark / $question->matches()->count();
                    foreach ($match_results as $match_result) {
                        $match_mark += $match_result->match_id == $match_result->result_id ? $main_mark : 0;
                    }
                    $total += $match_mark;
                    $m_total += $match_mark;
                    \Log::warning('M-QM : ' . $question->mark);
                }

                if ($question->type == 4) {
                    $sort_words = SortWord::query()->where('question_id', $question->id)->get()->pluck('id')->all();
                    $student_sort_words = SortResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
                        ->get();
                    if (count($student_sort_words)) {
                        $student_sort_words = $student_sort_words->pluck('sort_word_id')->all();
                        if ($student_sort_words === $sort_words) {
                            $total += $question->mark;
                            $s_total += $question->mark;
//                        Log::warning('S-QM : '.$question->mark);
                        }

                    }
                }
            }

            $mark = $test->lesson->success_mark;


            $test->update([
                'approved' => 1,
                'total' => $total,
                'status' => $total >= $mark ? 'Pass' : 'Fail',
            ]);


            $student_tests = UserTest::query()
//            ->where('total', '>=', $mark)
                ->where('user_id', $test->user_id)
//            ->where('total', '<=', $total)
                ->where('lesson_id', $test->lesson_id)
                ->orderByDesc('total')->get();


            if (optional($student_tests->first())->total >= $mark) {
                UserTest::query()->where('user_id', $test->user_id)
                    ->where('lesson_id', $test->lesson_id)
                    ->where('id', '<>', $student_tests->first()->id)->update([
                        'approved' => 0,
                    ]);
                UserTest::query()->where('user_id', $test->user_id)
                    ->where('lesson_id', $test->lesson_id)
                    ->where('id', $student_tests->first()->id)->update([
                        'approved' => 1,
                    ]);
            }


        }


        return 'test corrected Successfully';
    }

    public function copyLessons()
    {

        set_time_limit(600);
//        Lesson::query()->whereIn('grade_id', [2,3,4,5,6,7,8,9,10,11,12])->whereIn('lesson_type', ['grammar', 'dictation', 'rhetoric'])
//            ->delete();
//
        return 'lesson deleted successfully';
        $grades = [ 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $levels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $lessons_ids = [
//            1518,
            1504
//            1519, 1520, 1521,
//            1525, 1526, 1527, 1528,
//            1532, 1533, 1534, 1535,
//            1554, 1555, 1556, 1557, 1558, 1559, 1560, 1561
        ];
//        dd($levels);
        $lessons = Lesson::query()
            ->with([
                'media',
                'questions', 'questions.trueFalse', 'questions.matches', 'questions.sortWords', 'questions.options',
                't_questions', 't_questions.trueFalse', 't_questions.matches', 't_questions.sortWords', 't_questions.options'
            ])
            ->where('grade_id', 1)
            //'grammar',
            //'dictation',
            //, 'rhetoric'
            ->whereIn('lesson_type', ['rhetoric'])
//            ->whereIn('id', $lessons_ids)
            ->get();
//        dd($lessons->count());
//        dd($lessons->pluck('grade_id'));
//        foreach ($lessons as $lesson)
//        {
//            $other_lessons = Lesson::query()
//                ->where('name', $lesson->name)
//                ->where('lesson_type', $lesson->lesson_type)
//                ->where('id', '<>', $lesson->id)
//                ->get();
//
//            foreach ($other_lessons as $other_lesson)
//            {
//                $mediaItems = $lesson->getMedia('audioLessons');
//                foreach ($mediaItems as $media) {
//                    $media->copy($other_lesson, 'audioLessons');
//                }
//                $mediaItems = $lesson->getMedia('videoLessons');
//                foreach ($mediaItems as $media) {
//                    $media->copy($other_lesson, 'videoLessons');
//                }
//            }
//
//        }
//        return 'lesson copied successfully';
//        return 'lesson copied successfully';
//        return 'lesson copied successfully';
        foreach ($grades as $grade) {
//            $grade_lessons = $lessons->where('grade_id', $grade);
            foreach ($lessons as $lesson) {
//                foreach ($levels as $level) {
//                    if ($level != $grade) {
                        $n_lesson = $lesson->replicate();
                        $n_lesson->grade_id = $grade;
                        $n_lesson->level = $lesson->level;
                        $n_lesson->save();

                        $mediaItems = $lesson->getMedia('imageLessons');
                        foreach ($mediaItems as $media) {
                            $media->copy($n_lesson, 'imageLessons');
                        }
                        $mediaItems = $lesson->getMedia('audioLessons');
                        foreach ($mediaItems as $media) {
                            $media->copy($n_lesson, 'audioLessons');
                        }
                        $mediaItems = $lesson->getMedia('videoLessons');
                        foreach ($mediaItems as $media) {
                            $media->copy($n_lesson, 'videoLessons');
                        }
//                        $lesson->update([
//                            'level' => $lesson->grade_id,
//                        ]);
                        foreach ($lesson->questions as $question) {
                            $n_question = $question->replicate();
                            $n_question->lesson_id = $n_lesson->id;
                            $n_question->save();
                            if ($question->type == 1) {
                                //T & F
                                $n_t_f = $question->trueFalse->replicate();
                                $n_t_f->question_id = $n_question->id;
                                $n_t_f->save();
                            }

                            if ($question->type == 2) {
                                foreach ($question->options as $option) {
                                    $n_option = $option->replicate();
                                    $n_option->question_id = $n_question->id;
                                    $n_option->save();
                                }
                            }

                            if ($question->type == 3) {
                                foreach ($question->matches as $option) {
                                    $n_option = $option->replicate();
                                    $n_option->question_id = $n_question->id;
                                    $n_option->save();
                                }
                            }

                            if ($question->type == 4) {
                                foreach ($question->sortWords as $option) {
                                    $n_option = $option->replicate();
                                    $n_option->question_id = $n_question->id;
                                    $n_option->save();
                                }
                            }
                        }
                        foreach ($lesson->t_questions as $question) {
                            $n_question = $question->replicate();
                            $n_question->lesson_id = $n_lesson->id;
                            $n_question->save();
                            if ($question->type == 1 && $question->trueFalse) {
                                //T & F
                                $n_t_f = $question->trueFalse->replicate();
                                $n_t_f->t_question_id = $n_question->id;
                                $n_t_f->save();
                            }

                            if ($question->type == 2) {
                                foreach ($question->options as $option) {
                                    $n_option = $option->replicate();
                                    $n_option->t_question_id = $n_question->id;
                                    $n_option->save();
                                }
                            }

                            if ($question->type == 3) {
                                foreach ($question->matches as $option) {
                                    $n_option = $option->replicate();
                                    $n_option->t_question_id = $n_question->id;
                                    $n_option->save();
                                }
                            }

                            if ($question->type == 4) {
                                foreach ($question->sortWords as $option) {
                                    $n_option = $option->replicate();
                                    $n_option->t_question_id = $n_question->id;
                                    $n_option->save();
                                }
                            }
                        }
//                    }
//                }
            }
        }

        return 'lesson copied successfully';
    }

    public function getLessonsMedia()
    {
        //get lessons audio and check if it is exist and is valid
        $lessons = Lesson::query()->where('lesson_type', 'listening')->with(['media' => function ($query) {
            $query->where('collection_name', 'audioLessons');
        }])->get();
        $wrong_lessons = [];
        foreach ($lessons as $lesson)
        {
//            $mediaItems = $lesson->getMedia('audioLessons');
            //get first file and check if it is exist and is valid mp3 file
//            $media = $mediaItems->first();
//            if ($media && $media->hasGeneratedConversion('mp3')) {
//                $media->delete();
                $wrong_lessons[] = ['lesson' => $lesson->id, 'media' => $lesson->getMedia('audioLessons')];
//            }
        }
        return $wrong_lessons;
    }


}
