<?php

namespace App\Http\Controllers;

use App\Events\ContactUsEvent;
use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use App\Models\Package;
use App\Models\Page;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home()
    {
        $packages = Package::query()->where('active', 1)->get();
        $users_count = User::query()->count();
        return view('welcome', compact('packages', 'users_count'));
    }

    public function page($key)
    {
        $page = Page::query()->where('key', $key)->where('page_type', 'web')->firstOrFail();
        $title = $page->name;
        return view('page', compact('page', 'title'));
    }

    public function uploadRecode(Request $request)
    {
        if ($request->hasFile('audio_data'))
        {
            $file = $this->uploadImage($request->file('audio_data'), 'audio_records');
        }
        return redirect()->to('/record');
    }

    public function schools(Request $request)
    {
        $data = [];

        if($request->has('q') && $request->get('q', false)){
            $search = $request->q;
            $data = School::query()->select("id","name")
                ->where('name','LIKE',"%$search%")
                ->orderBy('name')
                ->get();
        }
        return response()->json($data);
    }
}
