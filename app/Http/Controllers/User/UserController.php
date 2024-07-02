<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\Payment;
use App\Models\School;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function packageUpgrade()
    {
        $title = "ترقية الإشتراك";
        $grades = Grade::query()->get();
        $user = Auth::guard('web')->user();
        $packages = Package::query()->where('price', '>', 0)->get();
        return view('user.settings.packages', compact('title', 'grades', 'user', 'packages'));
    }

    public function checkSubscribe(Request $request)
    {
        $user = Auth::guard('web')->user();
        if ($user->package->price == 0)
        {
            if( Carbon::createFromFormat('Y-m-d', '2021-07-08') > date('Y-m-d'))
            {
                $user->update([
                    'active_from' => Carbon::now(),
                    'active_to' => Carbon::createFromFormat('Y-m-d', '2021-07-08')
                ]);
                Payment::query()->create([
                    'user_id' => $user->id,
                    'package_id' => $user->package->id,
                    'days' => $user->package_id,
                    'amount' => 0,
                    'payment_id' => 'FREE SUBSCRIBE',
                    'expire_at' => Carbon::createFromFormat('Y-m-d', '2021-07-08')
                ]);
            }else{
                $user->update([
                    'active_from' => Carbon::now(),
                    'active_to' => Carbon::now()->addDays($user->package->days)
                ]);
                Payment::query()->create([
                    'user_id' => $user->id,
                    'package_id' => $user->package->id,
                    'days' => $user->package_id,
                    'amount' => 0,
                    'payment_id' => 'FREE SUBSCRIBE',
                    'expire_at' => Carbon::now()->addDays($user->package->days)
                ]);
            }

            return redirect()->route('home');
        }else{
            if ($request->get('grade_id', false))
            {
                return redirect()->route('subscribe_payment')->with('grade_id', $request->get('grade_id'));
            }else{
                return redirect()->route('subscribe_payment');
            }

        }
    }

    public function subscribePayment()
    {
        $user = Auth::guard('web')->user();
        if (!$user->package)
        {
            return redirect('home')->with('message', "يرجى اختيار باقة أولا")->with('m-class', 'error');
        }
        $total = $user->package->price;
        $array = array();
        $array["metadata"]["user_id"] = $user->id;
        $array["metadata"]["package_id"] = $user->package->id;
        Log::alert($array);

        $uuid = (string) Uuid::uuid4();
        $array['amount'] = $total;
        $array['currency'] = "USD";
        $array['threeDSecure'] = true;
        $array['save_card'] = false;
        $array['description'] = "Online Package Subscribe";
        $array['statement_descriptor'] = "Online Package Subscribe";
        $array['reference']['transaction'] = "txn_".$uuid;
        $array['reference']['order'] = "ord_".$uuid;
        $array['receipt']['email'] = "false";
        $array['receipt']['sms'] = "true";
        $array['customer']['first_name'] = $user->name;
        $array['customer']['middle_name'] = $user->name;
        $array['customer']['last_name'] = $user->name;
        $array['customer']['email'] = $user->email;
        $array['customer']['phone']['country_code'] = $user->country_code;
        $array['customer']['phone']['number'] = $user->mobile;
        $array['source']['id'] = "src_all";
        $array['post']['url'] = route('post_subscribe_payment');
        $array['redirect']['url'] = route('post_subscribe_payment');

        $json_array = json_encode($array);
//        dd(json_decode($json_array));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json_array,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tRijvPXVLZ6dGyHW0OUxkfCD",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            //dd($err);
            return redirect()->route('main')->with('message', t('some error happened please try again - error getaway'))->with('m-class', 'error');
        } else {
            $parms_array = json_decode($response, true);
            if(is_array($parms_array) && isset($parms_array['errors'])){
                //dd($parms_array);
                return redirect()->back()->with('message', t('some error happened please try again'))->with('m-class', 'error');
            }elseif(is_array($parms_array) && isset($parms_array['transaction']['url'])){
                return redirect()->to($parms_array['transaction']['url']);
            }else{
                return redirect()->route('main')->with('message', t('some error happened please try again failed'))->with('m-class', 'error');
            }
        }
    }

    public function payPackageUpgrade(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);
        $user = Auth::guard('web')->user();
        $package = Package::query()->findOrFail($request->get('package_id'));
        $total = $package->price;
        $array = array();
        $array["metadata"]["grade_id"] = (int) $request->get('grade_id');
        $array["metadata"]["user_id"] = $user->id;
        $array["metadata"]["package_id"] = $package->id;

        Log::alert($array);

        $uuid = (string) Uuid::uuid4();
        $array['amount'] = $total;
        $array['currency'] = "USD";
        $array['threeDSecure'] = true;
        $array['save_card'] = false;
        $array['description'] = "Online Package Subscribe";
        $array['statement_descriptor'] = "Online Package Subscribe";
        $array['reference']['transaction'] = "txn_".$uuid;
        $array['reference']['order'] = "ord_".$uuid;
        $array['receipt']['email'] = "false";
        $array['receipt']['sms'] = "true";
        $array['customer']['first_name'] = $user->name;
        $array['customer']['middle_name'] = $user->name;
        $array['customer']['last_name'] = $user->name;
        $array['customer']['email'] = $user->email;
        $array['customer']['phone']['country_code'] = $user->country_code;
        $array['customer']['phone']['number'] = $user->mobile;
        $array['source']['id'] = "src_all";
        $array['post']['url'] = route('post_subscribe_payment');
        $array['redirect']['url'] = route('post_subscribe_payment');

        $json_array = json_encode($array);
//        dd(json_decode($json_array));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json_array,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tRijvPXVLZ6dGyHW0OUxkfCD",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            //dd($err);
            return redirect()->route('main')->with('message', t('some error happened please try again - error getaway'))->with('m-class', 'error');
        } else {
            $parms_array = json_decode($response, true);
            if(is_array($parms_array) && isset($parms_array['errors'])){
                //dd($parms_array);
                return redirect()->back()->with('message', t('some error happened please try again'))->with('m-class', 'error');
            }elseif(is_array($parms_array) && isset($parms_array['transaction']['url'])){
                return redirect()->to($parms_array['transaction']['url']);
            }else{
                return redirect()->route('main')->with('message', t('some error happened please try again failed'))->with('m-class', 'error');
            }
        }
    }

    public function checkSubscribePayment(Request $request)
    {
        if (!$request->has('tap_id')){
            return redirect()->route('home')->with('message', t('Payment Order is Failed Tab Id Not Found'))->with('m-class', 'error');
        }
        $user = Auth::guard('web')->user();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/".$request->get('tap_id'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tRijvPXVLZ6dGyHW0OUxkfCD"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return redirect()->route('home')->with('message', t('some error happened please try again'))->with('m-class', 'error');
        } else {
            $parms_array = json_decode($response, true);
            if(is_array($parms_array) && isset($parms_array['errors'])){
                return redirect()->route('home')->with('message', t('Payment Order is Failed Error Happened'))->with('m-class', 'error');
            }elseif(is_array($parms_array) && isset($parms_array['response']['code'])){
                if($parms_array['response']['code'] != "000"){
                    Log::info($parms_array['response']);
                    return redirect()->route('home')->with('message', t('Payment Order is Failed'))->with('m-class', 'error');
                }else{
                    //Do Payment Complete here
                    if (is_array($parms_array) && isset($parms_array['metadata']) && is_array($parms_array['metadata'])){
                        Log::info($parms_array['metadata']);
                        if (isset($parms_array['metadata']['package_id']))
                        {
                            $package_id = $parms_array['metadata']['package_id'];
                        }else{
                            $package_id = $user->package_id;
                        }


                        if (isset($parms_array['metadata']['grade_id']))
                        {
                            $grade_id = $parms_array['metadata']['grade_id'];
                        }else{
                            $grade_id = $user->grade_id;
                        }


                        $package = Package::query()->find($package_id);
                        if ($package)
                        {

                            $pre_payment = Payment::query()->where('payment_id', $request->get('tap_id', ''))
                                ->where('user_id', $user->id)->first();
                            if (!$pre_payment)
                            {
                                $user->update([
                                    'package_id' => $package->id,
                                    'grade_id' => $grade_id,
                                    'active_from' => Carbon::now(),
                                    'active_to' => Carbon::now()->addDays($package->days),
                                ]);
                                Payment::query()->create([
                                    'user_id' => $user->id,
                                    'package_id' => $package->id,
                                    'days' => $package->days,
                                    'payment_id' => $request->get('tap_id', ''),
                                    'amount' => $package->price,
                                    'expire_at' => Carbon::now()->addDays($package->days),
                                ]);
                                return redirect()->route('home')->with('message', 'Your subscription was successful')->with('m-class', 'success');
                            }else{
                                return redirect()->route('package_upgrade')->with('message', 'Your subscription was registered previously')->with('m-class', 'error');
                            }

                        }else{
                            return redirect()->route('home')->with('message', t('subscription is failed'))->with('m-class', 'error');
                        }

                    }
                }
                return redirect()->route('home')->with('message', t('Payment Order is Incorrect'))->with('m-class', 'error');
            }else{
                return redirect()->route('home')->with('message', t('Payment Order is Failed'))->with('m-class', 'error');
            }
        }

    }

    public function profile()
    {
        $title = t('Profile');
        $schools = School::query()->get();
        $user = Auth::guard('web')->user();
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["name"] = 'required';
        $this->validationRules["email"] = "required|unique:users,email,$user->id,id,deleted_at,NULL";
        $this->validationRules["mobile"] = 'required';
        $this->validationRules["country_code"] = 'required';
        $this->validationRules["short_country"] = 'required';
        $this->validationRules["phone"] = ['required'];
        $this->validationRules["mobile"] = ['required', 'phone:'.request()->get('short_country')];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('user.settings.profile', compact('title', 'schools', 'user', 'validator'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::guard('web')->user();
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["name"] = 'required';
        $this->validationRules["email"] = "required|unique:users,email,$user->id,id,deleted_at,NULL";
        $this->validationRules["country_code"] = 'required';
        $this->validationRules["short_country"] = 'required';
        $this->validationRules["mobile"] = ['required'];
//        $this->validationRules["mobile"] = ['required', 'phone:'.request()->get('short_country')];
        $request->validate($this->validationRules);
        $data = $request->only(['image','name','email','mobile']);
        $data['mobile'] = PhoneNumber::make($request->get('mobile'))->ofCountry($request->get('short_country'));

        if ($request->hasFile('image'))
        {
            $data['image'] = $this->uploadFile($request->file('image'), 'users');
        }
        $user->update($data);
        return $this->redirectWith(true, null, 'تم تحديث البيانات بنجاح');

    }

    public function updatePasswordView()
    {
        $title = t('Change password');
//        $this->validationRules["current_password"] = 'required';
        $this->validationRules["password"] = 'required|min:6|confirmed';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('user.settings.password', compact('title',  'validator'));
    }

    public function updatePassword(Request $request)
    {
//        $this->validationRules["current_password"] = 'required';
        $this->validationRules["password"] = 'required|min:6|confirmed';
        $request->validate($this->validationRules);
        $user = Auth::guard('web')->user();
//        if(Hash::check($request->get('current_password'), $user->password)) {
            $data['password'] = bcrypt($request->get('password'));
            $user->update($data);
            return $this->redirectWith(true, null, 'تم تحديث كلمة المرور بنجاح');
//        }else{
//            return $this->redirectWith(true, null, 'Current Password Invalid', 'error');
//        }
    }

    public function trackLesson($id, $type)
    {
        $user =  Auth::user();
        $lesson = Lesson::query()->findOrFail($id);
        switch ($type)
        {
            case 'learn':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'learn',
                    'color' => 'warning',
                    'start_at' => now(),
                ]);
                break;
            case 'practise':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'practise',
                    'color' => 'primary',
                    'start_at' => now(),
                ]);
                break;
            case 'test':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'test',
                    'color' => 'danger',
                    'start_at' => now(),
                ]);
                break;
            case 'play':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'play',
                    'color' => 'success',
                    'start_at' => now(),
                ]);
                break;
        }
        return $this->sendResponse(true);
    }

    public function userLesson(Request $request, $id)
    {
        $user = Auth::user();
        $user_lesson = UserLesson::query()->updateOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $id,
        ],[
            'user_id' => $user->id,
            'lesson_id' => $id,
            'status' => 'pending',
        ]);
        $record = null;

        if($request->hasFile('record_file')){
            $record = $this->uploadFile($request->file('record_file'), 'record_result');
        }else if(isset($_FILES['record1']) && $_FILES['record1']['type'] != 'text/plain' && $_FILES['record1']['error'] <= 0){
            $new_name = uniqid().'.'.'wav';
            $destination = public_path('uploads/record_result');
            move_uploaded_file($_FILES['record1']['tmp_name'], $destination .'/'. $new_name);
            $record = 'uploads'.DIRECTORY_SEPARATOR.'record_result'.DIRECTORY_SEPARATOR.$new_name;
        }else{
            $record = $user_lesson->getOriginal('reading_answer');
        }


        if($request->hasFile('writing_attachment')){
            $writing_attachment_file = $this->uploadFile($request->file('writing_attachment'), 'writing_attachments');
        }else{
            $writing_attachment_file = $user_lesson->getOriginal('attach_writing_answer');
        }

        $user_lesson->writing_answer = $request->get('writing_answer', null) ;
        $user_lesson->attach_writing_answer = $writing_attachment_file ;
        $user_lesson->reading_answer = $record ;
        $user_lesson->submitted_at = now() ;

        $user_lesson->save();

        if ($user_lesson->user->teacherUser)
        {
            updateTeacherStatistics($user_lesson->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()->where('user_id', $user->id)
            ->where('lesson_id', $id)
            ->where('tasks_assignment', 1)
            ->where('done_tasks_assignment', 0)
            ->first();

        if ($user_assignment)
        {
            $user_assignment->update([
                'done_tasks_assignment' => 1,
            ]);

            if (($user_assignment->test_assignment && $user_assignment->done_test_assignment) || !$user_assignment->test_assignment){
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }


        return response()->json('saved - تم الحفظ','200');

    }
}
