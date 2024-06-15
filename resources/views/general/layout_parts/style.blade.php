<title>{{ t('My 1st Language Platform') }} | @yield('title')</title>
<meta charset="utf-8"/>
<meta name="description" content="{{ !settingCache('name') ?settingCache('name'):'My 1st Language Platform' }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('b_style')
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="{{asset('assets_v1/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets_v1/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
@if(app()->getLocale() == 'ar')
    <link href="{{asset('assets_v1/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets_v1/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css"/>
@else
    <link href="{{asset('assets_v1/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets_v1/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endif
<!--end::Global Stylesheets Bundle-->
<link rel="SHORTCUT ICON"  type="image/x-icon" href="{{!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))}}" />

@yield('style')
