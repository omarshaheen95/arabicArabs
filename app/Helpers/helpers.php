<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\Topics;
use GuzzleHttp\Client;
use Kreait\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use RLaurindo\TelegramLogger\Services\TelegramService;

function getAllModels() {
    $modelsPath = app_path('Models');
    $modelFiles = \Illuminate\Support\Facades\File::allFiles($modelsPath);
    $modelNames = [];

    foreach ($modelFiles as $file) {
        $relativePath = $file->getRelativePathname();
        $className = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);
        if (class_exists($className)) {
            $modelNames[] = $className;
        }
    }

    return $modelNames;
}
function schoolSections($school = false)
{
    $sections = \App\Models\User::query()
        ->when($school, function (\Illuminate\Database\Eloquent\Builder $query) use($school){
            $query->where('school_id', $school);
        })->whereNotNull('section')
        ->select('section')
        ->orderBy('section')
        ->get()->pluck('section')->unique()->values();
    return $sections;
}
function uploadFile($file, $path ,bool $new_name=true)
{
    $file_original_name = $file->getClientOriginalName();
    $file_new_name = Str::random(27) . '.' . $file->getClientOriginalExtension();
    $path = $path.'/'.date("Y").'/'.date("m").'/'.date("d");
    $destination = public_path($path);
    $file->move($destination , $file_new_name);
    return ['name'=>$new_name?$file_new_name:$file_original_name,'path'=>$path .'/'. $file_new_name];
}
function getGradeName($grade)
{
    switch($grade)
    {
        case 1:
            return "الأول";
        case 2:
            return "الثاني";
        case 3:
            return "الثالث";
        case 4:
            return "الرابع";
        case 5:
            return "الخامس";
        case 6:
            return "السادس";
        case 7:
            return "السابع";
        case 8:
            return "الثامن";
        case 9:
            return "التاسع";
        case 10:
            return "العاشر";
        case 11:
            return "الحادي عشر";
        case 12:
            return "الثاني عشر";
        case 13:
            return "التمهيدي";
        default:
            return '';
    }
}


function updateTeacherStatistics($teacher_id)
{
    $teacher = \App\Models\Teacher::query()->findOrFail($teacher_id);
    $user_lessons = \App\Models\UserLesson::query()->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($teacher){
        $query->whereHas('teacherUser', function (\Illuminate\Database\Eloquent\Builder $query) use ($teacher){
            $query->where('teacher_id', $teacher->id);
        });
    })->get();

    $user_tests = \App\Models\UserTest::query()->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($teacher){
        $query->whereHas('teacherUser', function (\Illuminate\Database\Eloquent\Builder $query) use ($teacher){
            $query->where('teacher_id', $teacher->id);
        });
    })->get();

    $teacher->update([
        'pending_tasks' => $user_lessons->where('status', 'pending')->count(),
        'corrected_tasks' => $user_lessons->where('status', 'corrected')->count(),
        'returned_tasks' => $user_lessons->where('status', 'returned')->count(),
        'passed_tests' => $user_tests->where('status', 'Pass')->count(),
        'failed_tests' => $user_tests->where('status', 'Fail')->count(),
    ]);

    return true;
}

function supportMessage($message)
{
    $telegram = new TelegramService(config('telegram-logger.bot_token'), '-514978669');
    $telegram->sendMessage($message);
}

function set_locale()
{
    $locale = isAPI()? request()->header('Accept-Language') : (session('lang') ?  session('lang'): 'en') ;
    if(!$locale || !in_array($locale,['ar','en'])) $locale = 'ar';

    app()->setLocale($locale);

    return $locale;
}

function get_firebase_token(string $userid = '')
{
    $claims = [];
    $serviceAccount = \Firebase\ServiceAccount::fromJsonFile(app_path('Helpers/aunak-b9c5a-firebase-adminsdk.json'));
    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->create();
    $auth = $firebase->getAuth();
    $firebase_token = (string)$auth->createCustomToken($userid, $claims);
    return $firebase_token;
}
function t($key, $placeholder = [], $locale = null)
{
    $group = 'translation';
    if (is_null($locale))
        $locale = config('app.locale');
    $key = trim($key);
    $word = $group . '.' . $key;
    if (Lang::has($word))
        return trans($word, $placeholder, $locale);

    $messages = [
        $word => $key,
    ];

    app('translator')->addLines($messages, $locale);
    $langs = ['en','ar'];
    foreach ($langs as $lang) {
        $translation_file = base_path() . '/resources/lang/' . $lang . '/' . $group . '.php';
        $fh = fopen($translation_file, 'r+');
        $key = str_replace("'", "\'", $key);
        $new_key = "\n \t'$key' => '$key',\n];\n";
        fseek($fh, -4, SEEK_END);
        fwrite($fh, $new_key);
        fclose($fh);
    }
    return trans($word, $placeholder, $locale);
    //return $key;
}

function w($key,$placeholder=[],$locale=null)
{

    $group = 'web';
    if(is_null($locale))
    $locale = config('app.locale');
    $key = trim($key);
    $word = $group.'.'.$key;
    if (Lang::has($word))
        return trans($word,$placeholder,$locale);

    $messages = [
        $word => $key,
    ];

    app('translator')->addLines($messages, $locale);
    $langs = config('translatable.locales');
    foreach ($langs as $lang) {
        $translation_file = base_path() . '/resources/lang/'.$lang.'/' . $group . '.php';
        $fh = fopen($translation_file, 'r+');
        $new_key = "\n \t'$key' => '$key',\n];\n";
        fseek($fh, -4, SEEK_END);
        fwrite($fh, $new_key);
        fclose($fh);
     }
    return trans($word,$placeholder,$locale);
    return $key;

}
function api($key,$placeholder=[],$locale=null)
{

    $group = 'api';
    if(is_null($locale))
        $locale = config('app.locale');
    $key = trim($key);
    $word = $group.'.'.$key;
    if (Lang::has($word))
        return trans($word,$placeholder,$locale);

    $messages = [
        $word => $key,
    ];

    app('translator')->addLines($messages, $locale);
    $langs = config('translatable.locales');
    foreach ($langs as $lang) {
        $translation_file = base_path() . '/resources/lang/'.$lang.'/' . $group . '.php';
        $fh = fopen($translation_file, 'r+');
        $new_key = "  \n  '$key' => '$key',\n];\n";
        fseek($fh, -4, SEEK_END);
        fwrite($fh, $new_key);
        fclose($fh);
    }
    return trans($word,$placeholder,$locale);
    return $key;

}

function isRtl() {
    return app()->getLocale() === 'ar';
}

function isRtlJS() {
    return app()->getLocale() === 'ar' ? 'true':'false';
}

function direction($dot='') {
    return isRtl()? 'rtl'.$dot : '';
}

function currentLanguage() {
    return app()->getLocale();
}

function MimeFile($extension)
{
    /*
     Video Type     Extension       MIME Type
    Flash           .flv            video/x-flv
    MPEG-4          .mp4            video/mp4
    iPhone Index    .m3u8           application/x-mpegURL
    iPhone Segment  .ts             video/MP2T
    3GP Mobile      .3gp            video/3gpp
    QuickTime       .mov            video/quicktime
    A/V Interleave  .avi            video/x-msvideo
    Windows Media   .wmv            video/x-ms-wmv
    */
  $ext_photos = ['png','jpg','jpeg','gif'];
    return in_array($extension,$ext_photos) ? 'photo' : 'video';

}

function split_string($string,$count=2){

//Using the explode method
$arr_ph = explode(" ",$string,$count);

if(!isset($arr_ph[1]))
  $arr_ph[1] = '';
  return $arr_ph;

}

function check_mobile($mobile){

    if(\Str::startsWith($mobile, '05')){
        return '+966'.substr($mobile,1,9);
    }
    if(\Str::startsWith($mobile, '03')){
        return '+966'.substr($mobile,1,9);
    }
    if(\Str::startsWith($mobile, '5')){
        return '+966'.substr($mobile,0,9);
    }
    if(\Str::startsWith($mobile, '00966')){
        return '+'.substr($mobile,2,13);
    }
    if(\Str::startsWith($mobile, '966')){
        return '+'.$mobile;
    }

    return $mobile;


 //   $mobile = str_replace('05', '+9665', $mobile);

}

/*
 |--------------------------------------------------------------------------
 | Send sms
 |--------------------------------------------------------------------------
 |
 */
 function send_sms($msg, $numbers,$attr=[], $dateSend = 0, $timeSend = 0)
{


   // $sms_settings = cached('settings');
    $setting = \App\Models\Setting::first();

    $settingsSmsGateway = $setting->sms_gateway;
    $settingsSmsUsername = urlencode($setting->sms_username);
    $settingsSmsPassword = urlencode($setting->sms_password);
    $settingsSmsSender = urlencode($setting->sms_sender);
    $applicationType = "68";
    $domainName = url('/');


   // $msg = convertToUnicode($msg);

    $stringToPost = "mobile=" . $settingsSmsUsername . "&password=" . $settingsSmsPassword . "&numbers=" . $numbers . "&sender=" . $settingsSmsSender . "&msg=" . $msg . "&timeSend=" . $timeSend . "&dateSend=" . $dateSend . "&applicationType=" . $applicationType ."&lang=3&domainName=" . $domainName;

    if (config('app.debug')) {
        \Log::info('debug ' . $settingsSmsGateway.'?'.$stringToPost);
        return 1;
    }
    else
    \Log::info('real ' . $settingsSmsGateway.'?'.$stringToPost);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $settingsSmsGateway);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $stringToPost);
        $result = curl_exec($ch);

        if ($result == 1) {
            return true;
        }
        elseif ($result == 5) {
            return \Log::info(trans('mobily.wrondpassword'));
        } elseif ($result == 4) {
            return \Log::info(trans('mobily.null_user_or_mobile'));
        } elseif ($result == 3) {
            return \Log::info(trans('mobily.no_charge'));
        } elseif ($result == 2) {
            return \Log::info(trans('mobily.no_charge_zeor'));
        } elseif ($result == 6) {
            return \Log::info(trans('mobily.try_later'));
        } elseif ($result == 10) {
            return \Log::info(trans('mobily.not_equeal'));
        } elseif ($result == 13) {
            return \Log::info(trans('mobily.sender_not_approval'));
        } elseif ($result == 14) {
            return \Log::info(trans('mobily.empty_sender'));
        } elseif ($result == 15) {
            return \Log::info(trans('mobily.empty_numbers'));
        } elseif ($result == 16) {
            return \Log::info(trans('mobily.empty_sender2'));
        } elseif ($result == 17) {
            return \Log::info(trans('mobily.message_not_encoding'));
        } elseif ($result == 18) {
            return \Log::info(trans('mobily.service_stoped'));
        } elseif ($result == 19) {
            return \Log::info(trans('mobily.app_error'));
        }
      return false;

    // $attr = http_build_query($attr);
    // if(!empty($attr))
    // $attr = '&'.$attr;

    //    if (is_string($numbers))
    //     $numbers = [$numbers];

    //     $phone_numbers = [];
    //    foreach ($numbers as $key => $mobile) {
    //     $mobile = str_replace('00966','+966',$mobile);
    //     if(!starts_with($mobile,'+966'))
    //     $mobile = '966'.substr($mobile, 1);
    //     else
    //     $mobile = substr($mobile, 1);
    //     $phone_numbers[] =  $mobile;
    //    }

    //     $numbers = implode(',', $phone_numbers);

    //     $url = $settingsSmsGateway . "?" . "mobile=" . $settingsSmsUsername . "&password=" . $settingsSmsPassword . "&numbers=" . $numbers . "&sender=" . $settingsSmsSender . "&msg=" . $msg.'&applicationType=68&lang=3';

    // if (config('app.debug')) {
    //     \Log::info('debug ' . $url);
    //     return 1;
    // }
    // else
    // \Log::info('real ' . $url);

    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_RETURNTRANSFER => 1,
    //     CURLOPT_URL => $url,
    // ));
    // $result = curl_exec($curl);
    // curl_close($curl);
    // if($result != 1)
    //  \Log::info('problem in sms : code' . $result);

    // return $result;
}

 function nearest($lat,$lng,$radius=1){

    // Km
    $angle_radius = $radius / 111;
    $location['min_lat'] = $lat - $angle_radius;
    $location['max_lat'] = $lat + $angle_radius;
    $location['min_lng'] = $lng - $angle_radius;
    $location['max_lng'] = $lng + $angle_radius;

    return (object)$location;

}

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2017                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
 function DistanceFromLatLonInKm(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}

function assets($path='',$relative=false)
{
    return $relative ?  'public/'.$path : url('public/'.$path);
}

function slug($string)
{
    return preg_replace('/\s+/u', '-', trim($string));
}

function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

function generateInvoiceNumber($model) {


 $year = date('Y');
 $expNum = 0;
//get last record
$record = $model::latest()->first();
 if($record)
 list($year,$expNum) = explode('-', $record->invoice_id);

//check first day in a year
if ( date('z') === '0' ) {
    $nextInvoiceNumber = date('Y').'-0001';
} else {
    //increase 1 with last invoice number
    $nextInvoiceNumber = $year.'-'. ((int)$expNum+1);
}

 return $nextInvoiceNumber;
//now add into database $nextInvoiceNumber as a next number.
}

function work_hours()
{
    $days = [
        ["day" => 'Saturday', 'num' => 1, 'from' => '8', 'to' => '20'],
        ["day" => 'Sunday', 'num' => 2, 'from' => '8', 'to' => '20'],
        ["day" => 'Monday', 'num' => 3, 'from' => '8', 'to' => '20'],
        ["day" => 'Tuesday', 'num' => 4, 'from' => '8', 'to' => '20'],
        ["day" => 'Wednesday', 'num' => 5, 'from' => '8', 'to' => '20'],
        ["day" => 'Thursday', 'num' => 6, 'from' => '8', 'to' => '20'],
        ["day" => 'Friday', 'num' => 7, 'from' => '8', 'to' => '20'],
    ];

    return $days;

}
function e_days($index)
{
    $days = [
        '1' => 'Saturday',
        '2' => 'Sunday',
        '3' => 'Monday',
        '4' => 'Tuesday',
        '5' => 'Wednesday',
        '6' => 'Thursday',
        '7' => 'Friday',
    ];

    return $days[$index];
}
function days($index)
{
    $days = [
        '1' => 'Saturday',
        '2' => 'Sunday',
        '3' => 'Monday',
        '4' => 'Tuesday',
        '5' => 'Wednesday',
        '6' => 'Thursday',
        '7' => 'Friday',
    ];

    return t($days[$index]);
}

function defaultImage()
{
    return "public/assets/img/default.png";
}

function status($status, $type = '')
{
    $color = [
        '0' => 'danger',
        '1' => 'success',
        'pending' => 'warning',
        'active' => 'success',
        'accepted' => 'success',
        'delayed' => 'default',
        'rejected' => 'danger',
        'cancelled' => 'default',
        'inactive' => 'danger',
        'waiting' => 'warning',
        'acceptable' => 'info',
        'unacceptable' => 'danger',
        'winners' => 'success',
        'done' => 'success',
        'pass' => 'info',
        'shipping' => 'warning',
        'new' => 'warning',
        'completed' => 'success',

    ];

    $text = [
        '0' => t('admin.Inactive'),
        '1' => t('admin.Active'),
        'pending' => t('admin.Pending'),
        'active' => t('admin.Active'),
        'accepted' => 'مقبول',
        'delayed' => 'مؤجل',
        'rejected' => 'مرفوض',
        'cancelled' => t('admin.Cancelled'),
        'inactive' => t('admin.inactive'),
        'waiting' => t('admin.waiting'),
        'acceptable' => 'مقبول',
        'unacceptable' => 'مرفوض',
        'winners' => 'فائز',
        'done' => t('admin.Done'),
        'pass' => 'تم تمريرها',
        'shipping' => t('admin.Shipping'),
        'new' => t('admin.New'),
        'completed' => t('admin.completed'),
    ];

    if ($type == 't')
        return $text[$status];

    if ($type == 'c')
        return $color[$status];

    return "<label class='label label-mini label-{$color[$status]}'>{$text[$status]}<label>";
}

function pic($src, $class = 'full')
{
    $html = "<img class='  " . $class . "' src='" . asset($src) . "'>";

    return $html;
}

function ext($filename, $style = false)
{

    //$ext = File::extension($filename);

    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if (!$style)
        return $ext;
    return $html = "<img class='' src='" . asset('public/assets/img/ext/' . $ext . '.png') . "'>";
}

function IsLang($lang='ar')
{
    return session('lang') == $lang;
}

function CurrentLang()
{
    return session('lang','en');
}

 function rating($val,$max=5){
     $html = '';
        for ($i = 1; $i <=$max; $i++) {

            if ($i <= $val)
                $html .= "<span><i class='fa fa-star fa-lg active'></i></span>";
            else
                $html .= "<span><i class='fa fa-star-o fa-lg '></i></span>";

        }
        return $html;

  }

function isAPI(){
return request()->is('api/*');
}

function versions()
{
    return ['v1'];
}

function base64ToFile($data){

$file_name = 'attach_'.time().'.'.getExtBase64($data);
$path = 'uploads/user_attachments/'.$file_name;
$uploadPath  = public_path($path);
if(!file_put_contents($uploadPath , base64_decode($data)));
 $path = '';
return $path;

}

function getExtBase64($data){

$pos  = strpos($data, ';');
$mimi = explode(':', substr($data, 0, $pos))[1];
return $ext = explode('/', $mimi)[1];
}

function paginate($object)
{
    return [
        'current_page' => $object->currentPage(),
        //'items' => $object->items(),
        'first_page_url' => $object->url(1),
        'from' => $object->firstItem(),
        'last_page' => $object->lastPage(),
        'last_page_url' => $object->url($object->lastPage()),
        'next_page_url' => $object->nextPageUrl(),
        'per_page' => $object->perPage(),
        'prev_page_url' => $object->previousPageUrl(),
        'to' => $object->lastItem(),
        'total' => $object->total(),
    ];
}

function paginate_message($object)
{

    $items = [];
    foreach($object->items() as $key => $item) {
    foreach($item['data'] as $k => $val) {
    $items[$key][$k] = $val;

    // $items[$key] = ['id' => $item->id,'title' => $item->data['title'],'body' => $item->data['body'],'created_at' => $item->created_at ];
    /* if(isset($item->data['title']))
      $items[$key]['title'] = $item->data['title']; */
    }
    $items[$key]['notification_id'] = $item->id;
    $items[$key]['created_at'] = $item->created_at->format('Y-m-d H:i:s');
    }

    return [
        'current_page' => $object->currentPage(),
        'items' => $items,
        'first_page_url' => $object->url(1),
        'from' => $object->firstItem(),
        'last_page' => $object->lastPage(),
        'last_page_url' => $object->url($object->lastPage()),
        'next_page_url' => $object->nextPageUrl(),
        'per_page' => $object->perPage(),
        'prev_page_url' => $object->previousPageUrl(),
        'to' => $object->lastItem(),
        'total' => $object->total(),
    ];
}

function send_push($device_token, $payload_data = [])
{
    if ($device_token instanceof Illuminate\Support\Collection) {
        $device_token = $device_token->toArray();
    }

    if (!$device_token) {
        return true;
    }
    $device_token = array_filter($device_token);

    if (!$device_token) {
        return true;
    }

    // if (config('app.debug')) {
    //     \Log::info('send_push device_token: ' . json_encode($device_token));
    //     \Log::info('send_push payload_data: ' . json_encode($payload_data));
    //     return true;
    // }

    $notificationBuilder = new PayloadNotificationBuilder($payload_data['title']);
    $notificationBuilder->setBody($payload_data['body'])
        ->setSound('default');
    if (isset($payload_data['click_action'])) {
        $notificationBuilder->setClickAction($payload_data['click_action']);
    }
    $notification = $notificationBuilder->build();

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData($payload_data);
    $data = $dataBuilder->build();

    FCM::sendTo($device_token, null, $notification, $data);

}

function send_push_to_pusher($topic,$event, $message)
{
    $options = array(
        'cluster' => 'mt1',
        'useTLS' => true
    );

    //Remember to set your credentials below.
    $pusher = new \Pusher\Pusher(
        'dca3e1c68a7801d90df2',
        'b5ecad2c7dcf97aeb007',
        '1194147', $options
    );

    //Send a message to notify channel with an event name of notify-event
    $status = $pusher->trigger($topic, $event, $message);
    \Illuminate\Support\Facades\Log::info($status);
    return true;
}

function send_notification($topic,$data = null, $notification = null)
{
    $message = CloudMessage::withTarget('topic', $topic)
        ->withNotification($notification) // optional
        ->withData($data) // optional
    ;
    $message = CloudMessage::fromArray([
        'topic' => $topic,
        'notification' => [/* Notification data as array */], // optional
        'data' => [/* data array */], // optional
    ]);
    $message->send($message);
}

function send_push_to_topic($topic_name,$payload_data)
{
    // if (config('app.debug')) {
    //     \Log::info('send_push topic_name: ' . json_encode($topic_name));
    //     \Log::info('send_push payload_data: ' . json_encode($payload_data));
    //     return true;
    // }

    $notificationBuilder = new PayloadNotificationBuilder($payload_data['title']);
    $notificationBuilder->setBody($payload_data['body'])
        ->setSound('default');
    if (isset($payload_data['click_action'])) {
        $notificationBuilder->setClickAction($payload_data['click_action']);
    }
    $notification = $notificationBuilder->build();

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData($payload_data);

    $data = $dataBuilder->build();

    $topic = new Topics();
    $topic->topic($topic_name);

    FCM::sendToTopic($topic, null, $notification, $data);

}

function send_to_topic($topic_name,$payload_data)
{
    $data = json_encode([
        "to" => "/topics/$topic_name",
        "notification" => [
            "body" => $payload_data['body'],
            "title" => $payload_data['title'],
            "icon" => "ic_launcher",
            "sound" => "default",
            "click_action" => isset($payload_data['click_action']) ? $payload_data['click_action']:"",
        ],
        "data" => isset($payload_data['other']) ? $payload_data['other']:null,
    ]);
    //FCM API end-point
    $url = 'https://fcm.googleapis.com/fcm/send';
    //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
    $server_key = 'AAAAw4_5I5c:APA91bGZfWxazbgxzDaBf_wHQ8rTomAbhcYznljmlEVyiJ93Wzm9pP75KAFuVs-EZvxrP1ptgdLwTIW8OoqXWBv-UwQ2bOPSKLiV1ZxVjKu_noaQ_QLsChsOfSZ8WkO20do8y4DTN0ZF';
    //header with content_type api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$server_key
    );
    //CURL request to route notification to FCM connection server (provided by Google)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    \Illuminate\Support\Facades\Log::info($result);
    curl_close($ch);

//
//    \Log::info($status);

}

function send_to_topics($topic_name,$payload_data)
{
    $data = json_encode([
        "to" => '/topics/test_1',
        "notification" => [
            "body" => "اشعار 1",
            "title" => "SOMETHING",
            "icon" => "ic_launcher",
            "sound" => "default",
        ],
    ]);
    //FCM API end-point
    $url = 'https://fcm.googleapis.com/fcm/send';
    //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
    $server_key = 'AAAA1nddVzw:APA91bGoQSlSGQ702blNlQ1AcETq73n9WOGkWfYCAy-OHsgPQ7Lz5Tj2ay6VkDxy6BKDbu7vQttOZE9qGvwCzcTJ6kctYjVca1M9sMDkwobHpI-L7q3PRlmhnWZcalI0n336nADscmkn';
    //header with content_type api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$server_key
    );
    //CURL request to route notification to FCM connection server (provided by Google)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    \Illuminate\Support\Facades\Log::info($result);
    curl_close($ch);

//    $firebase = (new Factory)->withServiceAccount(__DIR__.DIRECTORY_SEPARATOR.'google_service.json');
//    $messaging = $firebase->createMessaging();
//
//    $message = CloudMessage::fromArray([
//        'notification' => ['title' => 'اشعار 21', 'body' => 'dasdasd'],
//        'data' => ['hi' => 'hi'], // optional
//        'android' => [
//            'priority' => 'high',
//            'notification' => [
//                'default_vibrate_timings' => true,
//                'default_sound' => true,
//                'notification_count' => 42,
//                'color' => '#200e57',
//                'notification_priority' => 'PRIORITY_HIGH' // PRIORITY_LOW , PRIORITY_DEFAULT , PRIORITY_HIGH , PRIORITY_MAX
//            ],
//        ],
//        'apns' => [
//            'headers' => [
//                'apns-priority' => '10',
//            ],
//            'payload' => [
//                'aps' => [
//                    'alert' => [
//                        'title' => '$GOOG up 1.43% on the day',
//                        'body' => '$GOOG gained 11.80 points to close at 835.67, up 1.43% on the day.',
//                    ],
//                    'sound' => 'default',
//                    'badge' => 42,
//                ],
//            ],
//        ],
//
//    ])->withChangedTarget('topic', 'test_1');
//
//    $status = $messaging->send($message);
//    \Illuminate\Support\Facades\Log::info($status);
}
function getOnly($only, $array)
{
    $data = [];
    foreach ($only as $id) {
        if (isset($array[$id])) {
            $data[$id] = $array[$id];
        }
    }
    return $data;
}

function status_text($status){

 $title = ['pending' => 'المعلقة','accepted' => 'المقبولة','cancelled' => 'الملغية','rejected' => 'المرفوضة'];

 return $title[$status];

}

function cached($index = 'settings',$col=false)
{

    //Cache::forget('cities');
    $cache['settings'] = Cache::remember('settings', 60 * 48, function () {
        return \App\Models\Setting::first();
    });

    if (!isset($cache[$index]))
        return $index;
    if(!$col)
    return $cache[$index];
    return $cache[$index]->{$col};

}

function destroyFile($file){

 if(!empty($file) and File::exists(public_path($file)))
  File::delete(public_path($file));

}

function curl_get_contents($url)
{
    $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
   $html = curl_exec($ch);
   $data = curl_exec($ch);
   curl_close($ch);
   return $data;
}

function arabic_date($datetime){

 $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
 $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
 $am_pm = ['AM' => 'ص', 'PM' => 'م'];

 $_month = $months[date('M',strtotime($datetime))];
 $_day    = $days[date('D',strtotime($datetime))];
 $_day    = date('d',strtotime($datetime));
 $_time    = date('h:i',strtotime($datetime));
 $_am_pm  = $am_pm[date('A',strtotime($datetime))];

 return \Carbon\Carbon::parse($datetime)->format('d/m/Y - h:i').' '.$_am_pm;

}
function arabic_time($_time){

 $am_pm = ['AM' => 'ص', 'PM' => 'م'];

 $_time    = date('h:i',strtotime($_time));
$_am_pm  = $am_pm[date('A',strtotime($_time))];

 return $_am_pm.' '.\Carbon\Carbon::parse($_time)->format('h:i');

}

function numhash($n) {
    return (((0x0000FFFF & $n) << 16) + ((0xFFFF0000 & $n) >> 16));
}


function compress_image($source_url, $destination_url, $quality) {

      // $info = getimagesize($source_url);
//        $memoryNeeded = round(($info[0] * $info[1] * $info['bits']  / 8 + Pow(2, 16)) * 1.65);

// if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > (integer) ini_get('memory_limit') * pow(1024, 2)) {

//     ini_set('memory_limit', (integer) ini_get('memory_limit') + ceil(((memory_get_usage() + $memoryNeeded) - (integer) ini_get('memory_limit') * pow(1024, 2)) / pow(1024, 2)) . 'M');

// }

      ini_set('memory_limit', '265M');

    // $newHeight = ($height / $width) * $newWidth;
    // $tmp = imagecreatetruecolor($newWidth, $newHeight);
    // imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);


        // if ($info['mime'] == 'image/jpeg'){
        //       $image = imagecreatefromjpeg($source_url);
        // imagejpeg($image, $destination_url, $quality);
        // }
        // if ($info['mime'] == 'image/gif'){
        //       $image = imagecreatefromgif($source_url);
        // imagegif($image, $destination_url, 5);
        // }
        // elseif ($info['mime'] == 'image/png'){
        //       $image = imagecreatefrompng($source_url);
        // imagepng($image, $destination_url, 5);
        // }
        // else{
        // $image = imagecreatefromjpeg($source_url);
        // imagejpeg($image, $destination_url, $quality);
        // }



// jpg, png, gif or bmp?
    $exploded = explode('.',$source_url);
    $ext = $exploded[count($exploded) - 1];

    if (preg_match('/jpg|jpeg/i',$ext))
        $imageTmp=imagecreatefromjpeg($source_url);
    else if (preg_match('/png/i',$ext))
        $imageTmp=imagecreatefrompng($source_url);
    else if (preg_match('/gif/i',$ext))
        $imageTmp=imagecreatefromgif($source_url);
    else if (preg_match('/bmp/i',$ext))
        $imageTmp=imagecreatefrombmp($source_url);
    else
        return 0;

    // quality is a value from 0 (worst) to 100 (best)
    imagejpeg($imageTmp, $destination_url, $quality);


        imagedestroy($imageTmp);
        return $destination_url;
    }

function resize($newWidth, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default:
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // if (file_exists($targetFile)) {
    //         unlink($targetFile);
    // }

    $image_save_func($tmp,$originalFile);
}

function convertToUnicode($message)
{
    $chrArray[0] = "¡";
    $unicodeArray[0] = "060C";
    $chrArray[1] = "º";
    $unicodeArray[1] = "061B";
    $chrArray[2] = "¿";
    $unicodeArray[2] = "061F";
    $chrArray[3] = "Á";
    $unicodeArray[3] = "0621";
    $chrArray[4] = "Â";
    $unicodeArray[4] = "0622";
    $chrArray[5] = "Ã";
    $unicodeArray[5] = "0623";
    $chrArray[6] = "Ä";
    $unicodeArray[6] = "0624";
    $chrArray[7] = "Å";
    $unicodeArray[7] = "0625";
    $chrArray[8] = "Æ";
    $unicodeArray[8] = "0626";
    $chrArray[9] = "Ç";
    $unicodeArray[9] = "0627";
    $chrArray[10] = "È";
    $unicodeArray[10] = "0628";
    $chrArray[11] = "É";
    $unicodeArray[11] = "0629";
    $chrArray[12] = "Ê";
    $unicodeArray[12] = "062A";
    $chrArray[13] = "Ë";
    $unicodeArray[13] = "062B";
    $chrArray[14] = "Ì";
    $unicodeArray[14] = "062C";
    $chrArray[15] = "Í";
    $unicodeArray[15] = "062D";
    $chrArray[16] = "Î";
    $unicodeArray[16] = "062E";
    $chrArray[17] = "Ï";
    $unicodeArray[17] = "062F";
    $chrArray[18] = "Ð";
    $unicodeArray[18] = "0630";
    $chrArray[19] = "Ñ";
    $unicodeArray[19] = "0631";
    $chrArray[20] = "Ò";
    $unicodeArray[20] = "0632";
    $chrArray[21] = "Ó";
    $unicodeArray[21] = "0633";
    $chrArray[22] = "Ô";
    $unicodeArray[22] = "0634";
    $chrArray[23] = "Õ";
    $unicodeArray[23] = "0635";
    $chrArray[24] = "Ö";
    $unicodeArray[24] = "0636";
    $chrArray[25] = "Ø";
    $unicodeArray[25] = "0637";
    $chrArray[26] = "Ù";
    $unicodeArray[26] = "0638";
    $chrArray[27] = "Ú";
    $unicodeArray[27] = "0639";
    $chrArray[28] = "Û";
    $unicodeArray[28] = "063A";
    $chrArray[29] = "Ý";
    $unicodeArray[29] = "0641";
    $chrArray[30] = "Þ";
    $unicodeArray[30] = "0642";
    $chrArray[31] = "ß";
    $unicodeArray[31] = "0643";
    $chrArray[32] = "á";
    $unicodeArray[32] = "0644";
    $chrArray[33] = "ã";
    $unicodeArray[33] = "0645";
    $chrArray[34] = "ä";
    $unicodeArray[34] = "0646";
    $chrArray[35] = "å";
    $unicodeArray[35] = "0647";
    $chrArray[36] = "æ";
    $unicodeArray[36] = "0648";
    $chrArray[37] = "ì";
    $unicodeArray[37] = "0649";
    $chrArray[38] = "í";
    $unicodeArray[38] = "064A";
    $chrArray[39] = "Ü";
    $unicodeArray[39] = "0640";
    $chrArray[40] = "ð";
    $unicodeArray[40] = "064B";
    $chrArray[41] = "ñ";
    $unicodeArray[41] = "064C";
    $chrArray[42] = "ò";
    $unicodeArray[42] = "064D";
    $chrArray[43] = "ó";
    $unicodeArray[43] = "064E";
    $chrArray[44] = "õ";
    $unicodeArray[44] = "064F";
    $chrArray[45] = "ö";
    $unicodeArray[45] = "0650";
    $chrArray[46] = "ø";
    $unicodeArray[46] = "0651";
    $chrArray[47] = "ú";
    $unicodeArray[47] = "0652";
    $chrArray[48] = "!";
    $unicodeArray[48] = "0021";
    $chrArray[49]='"';
    $unicodeArray[49] = "0022";
    $chrArray[50] = "#";
    $unicodeArray[50] = "0023";
    $chrArray[51] = "$";
    $unicodeArray[51] = "0024";
    $chrArray[52] = "%";
    $unicodeArray[52] = "0025";
    $chrArray[53] = "&";
    $unicodeArray[53] = "0026";
    $chrArray[54] = "'";
    $unicodeArray[54] = "0027";
    $chrArray[55] = "(";
    $unicodeArray[55] = "0028";
    $chrArray[56] = ")";
    $unicodeArray[56] = "0029";
    $chrArray[57] = "*";
    $unicodeArray[57] = "002A";
    $chrArray[58] = "+";
    $unicodeArray[58] = "002B";
    $chrArray[59] = ",";
    $unicodeArray[59] = "002C";
    $chrArray[60] = "-";
    $unicodeArray[60] = "002D";
    $chrArray[61] = ".";
    $unicodeArray[61] = "002E";
    $chrArray[62] = "/";
    $unicodeArray[62] = "002F";
    $chrArray[63] = "0";
    $unicodeArray[63] = "0030";
    $chrArray[64] = "1";
    $unicodeArray[64] = "0031";
    $chrArray[65] = "2";
    $unicodeArray[65] = "0032";
    $chrArray[66] = "3";
    $unicodeArray[66] = "0033";
    $chrArray[67] = "4";
    $unicodeArray[67] = "0034";
    $chrArray[68] = "5";
    $unicodeArray[68] = "0035";
    $chrArray[69] = "6";
    $unicodeArray[69] = "0036";
    $chrArray[70] = "7";
    $unicodeArray[70] = "0037";
    $chrArray[71] = "8";
    $unicodeArray[71] = "0038";
    $chrArray[72] = "9";
    $unicodeArray[72] = "0039";
    $chrArray[73] = ":";
    $unicodeArray[73] = "003A";
    $chrArray[74] = ";";
    $unicodeArray[74] = "003B";
    $chrArray[75] = "<";
    $unicodeArray[75] = "003C";
    $chrArray[76] = "=";
    $unicodeArray[76] = "003D";
    $chrArray[77] = ">";
    $unicodeArray[77] = "003E";
    $chrArray[78] = "?";
    $unicodeArray[78] = "003F";
    $chrArray[79] = "@";
    $unicodeArray[79] = "0040";
    $chrArray[80] = "A";
    $unicodeArray[80] = "0041";
    $chrArray[81] = "B";
    $unicodeArray[81] = "0042";
    $chrArray[82] = "C";
    $unicodeArray[82] = "0043";
    $chrArray[83] = "D";
    $unicodeArray[83] = "0044";
    $chrArray[84] = "E";
    $unicodeArray[84] = "0045";
    $chrArray[85] = "F";
    $unicodeArray[85] = "0046";
    $chrArray[86] = "G";
    $unicodeArray[86] = "0047";
    $chrArray[87] = "H";
    $unicodeArray[87] = "0048";
    $chrArray[88] = "I";
    $unicodeArray[88] = "0049";
    $chrArray[89] = "J";
    $unicodeArray[89] = "004A";
    $chrArray[90] = "K";
    $unicodeArray[90] = "004B";
    $chrArray[91] = "L";
    $unicodeArray[91] = "004C";
    $chrArray[92] = "M";
    $unicodeArray[92] = "004D";
    $chrArray[93] = "N";
    $unicodeArray[93] = "004E";
    $chrArray[94] = "O";
    $unicodeArray[94] = "004F";
    $chrArray[95] = "P";
    $unicodeArray[95] = "0050";
    $chrArray[96] = "Q";
    $unicodeArray[96] = "0051";
    $chrArray[97] = "R";
    $unicodeArray[97] = "0052";
    $chrArray[98] = "S";
    $unicodeArray[98] = "0053";
    $chrArray[99] = "T";
    $unicodeArray[99] = "0054";
    $chrArray[100] = "U";
    $unicodeArray[100] = "0055";
    $chrArray[101] = "V";
    $unicodeArray[101] = "0056";
    $chrArray[102] = "W";
    $unicodeArray[102] = "0057";
    $chrArray[103] = "X";
    $unicodeArray[103] = "0058";
    $chrArray[104] = "Y";
    $unicodeArray[104] = "0059";
    $chrArray[105] = "Z";
    $unicodeArray[105] = "005A";
    $chrArray[106] = "[";
    $unicodeArray[106] = "005B";
    $char="\ ";
    $chrArray[107]=trim($char);
    $unicodeArray[107] = "005C";
    $chrArray[108] = "]";
    $unicodeArray[108] = "005D";
    $chrArray[109] = "^";
    $unicodeArray[109] = "005E";
    $chrArray[110] = "_";
    $unicodeArray[110] = "005F";
    $chrArray[111] = "`";
    $unicodeArray[111] = "0060";
    $chrArray[112] = "a";
    $unicodeArray[112] = "0061";
    $chrArray[113] = "b";
    $unicodeArray[113] = "0062";
    $chrArray[114] = "c";
    $unicodeArray[114] = "0063";
    $chrArray[115] = "d";
    $unicodeArray[115] = "0064";
    $chrArray[116] = "e";
    $unicodeArray[116] = "0065";
    $chrArray[117] = "f";
    $unicodeArray[117] = "0066";
    $chrArray[118] = "g";
    $unicodeArray[118] = "0067";
    $chrArray[119] = "h";
    $unicodeArray[119] = "0068";
    $chrArray[120] = "i";
    $unicodeArray[120] = "0069";
    $chrArray[121] = "j";
    $unicodeArray[121] = "006A";
    $chrArray[122] = "k";
    $unicodeArray[122] = "006B";
    $chrArray[123] = "l";
    $unicodeArray[123] = "006C";
    $chrArray[124] = "m";
    $unicodeArray[124] = "006D";
    $chrArray[125] = "n";
    $unicodeArray[125] = "006E";
    $chrArray[126] = "o";
    $unicodeArray[126] = "006F";
    $chrArray[127] = "p";
    $unicodeArray[127] = "0070";
    $chrArray[128] = "q";
    $unicodeArray[128] = "0071";
    $chrArray[129] = "r";
    $unicodeArray[129] = "0072";
    $chrArray[130] = "s";
    $unicodeArray[130] = "0073";
    $chrArray[131] = "t";
    $unicodeArray[131] = "0074";
    $chrArray[132] = "u";
    $unicodeArray[132] = "0075";
    $chrArray[133] = "v";
    $unicodeArray[133] = "0076";
    $chrArray[134] = "w";
    $unicodeArray[134] = "0077";
    $chrArray[135] = "x";
    $unicodeArray[135] = "0078";
    $chrArray[136] = "y";
    $unicodeArray[136] = "0079";
    $chrArray[137] = "z";
    $unicodeArray[137] = "007A";
    $chrArray[138] = "{";
    $unicodeArray[138] = "007B";
    $chrArray[139] = "|";
    $unicodeArray[139] = "007C";
    $chrArray[140] = "}";
    $unicodeArray[140] = "007D";
    $chrArray[141] = "~";
    $unicodeArray[141] = "007E";
    $chrArray[142] = "©";
    $unicodeArray[142] = "00A9";
    $chrArray[143] = "®";
    $unicodeArray[143] = "00AE";
    $chrArray[144] = "÷";
    $unicodeArray[144] = "00F7";
    $chrArray[145] = "×";
    $unicodeArray[145] = "00F7";
    $chrArray[146] = "§";
    $unicodeArray[146] = "00A7";
    $chrArray[147] = " ";
    $unicodeArray[147] = "0020";
    $chrArray[148] = "\n";
    $unicodeArray[148] = "000D";
    $chrArray[149] = "\r";
    $unicodeArray[149] = "000A";

    $strResult = "";
    for($i=0; $i<strlen($message); $i++)
    {
        if(in_array(substr($message,$i,1), $chrArray))
        $strResult.= $unicodeArray[array_search(substr($message,$i,1), $chrArray)];
    }
    return $strResult;
}

function keyToValue($key)
{
    switch ($key)
    {
        case 'speech':
            return 1;
        case 'lecture':
            return 2;
        case 'lesson':
            return 3;
        case 'word':
            return 4;
    }
}

function get_guard(){
    if (strpos(request()->url(), '/api/') !== false || strpos(request()->url(), '/web/') !== false) {
        foreach (array_keys(config('auth.guards')) as $guard) {

            if (auth()->guard('sanctum:'.$guard)->check()) return $guard;

        }
    }
    foreach(array_keys(config('auth.guards')) as $guard){

        if(auth()->guard($guard)->check()) return $guard;

    }
    return null;
}

function settingCache($key)
{
    //Cache::forget('settings');
    $cache = Cache::remember('settings', 60 * 48, function () {
        return \App\Models\Setting::get();
    });

    //dd($cache->where('key','mobile')->pluck('value')->first());
    return $cache->where('key',$key)->pluck('value')->first() ;

}

function sysDomain()
{
    $host = request()->getHost();
    if(strpos($host, 'www') !== false){
        return $host;
    } else{
        return 'www.'.$host;
    }

}

function deleteFile($path, string $disk = 'public')
{
    return \Illuminate\Support\Facades\File::delete(public_path($path));
}
function storyGradesSys()
{
  return  [15=>'Grade KG',1=>'Grade 1',2=>'Grade 2',3=>'Grade 3',4=>'Grade 4',5=>'Grade 5',
        6=>'Grade 6',7=>'Grade 7',8=>'Grade 8',9=>'Grade 9'];
//   return[
//
//       ['name'=>'Grade KG','value'=>15],
//       ['name'=>'Grade 1','value'=>1],
//       ['name'=>'Grade 2','value'=>2],
//       ['name'=>'Grade 3','value'=>3],
//       ['name'=>'Grade 4','value'=>4],
//       ['name'=>'Grade 5','value'=>5],
//       ['name'=>'Grade 6','value'=>6],
//       ['name'=>'Grade 7','value'=>7],
//       ['name'=>'Grade 8','value'=>8],
//       ['name'=>'Grade 9','value'=>9],
//   ] ;
}
function lessonTypes()
{
    return ['reading','writing','listening','speaking','grammar','dictation','rhetoric'];
    //return ['قراءة','كتابة','استماع','تحدث','القواعد النحوية','الإملاء','البلاغة'];
}

function teacherSections($teacher_id=null)
{
    if (!$teacher_id){
        $teacher_id = request()->get('teacher_id');
    }
    return  \App\Models\User::query()
        ->whereHas('teacherUser', function (Builder $query) use ($teacher_id) {
            $query->where('teacher_id', $teacher_id);
        })
        ->whereNotNull('section')
        ->select('section')
        ->orderBy('section')
        ->get()
        ->pluck('section')->unique()->values();
}
