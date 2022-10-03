
@extends('school.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('Teachers statistics') }}
        </li>
    @endpush
{{--    @push('search')--}}
{{--        <div class="kt-subheader-search" style="background: linear-gradient(to right,#39B448,#31ab3f);">--}}
{{--            <h3 class="kt-subheader-search__title">--}}
{{--                {{ t('search') }}--}}
{{--            </h3>--}}
{{--            <form class="kt-form">--}}
{{--                <div class="kt-grid kt-grid--desktop kt-grid--ver-desktop">--}}
{{--                    <div class="row" style="width: 100%">--}}
{{--                        <div class="col-lg-6">--}}
{{--                            <div class="kt-input-icon kt-input-icon--pill kt-input-icon--right">--}}
{{--                                <input style="background: white" type="text" id="search" class="form-control form-control-pill"placeholder="الكلمات المفتاحية">--}}
{{--                                <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-search"></i></span></span>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="col-lg-2">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    @endpush--}}
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{$title}}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="{{route('school.teacher.statistics_export')}}" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-paper-plane"></i>
                                    استخراج
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>الاسم</th>
                        <th>اختبارات دروس منجزة</th>
                        <th>اختبارات دروس فاشلة</th>

                        <th>اختبارات قصص منجزة</th>
                        <th>اختبارات قصص فاشلة</th>
                        <th>آخر دخول</th>
                        <th>الإجراءات</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    dom: `<'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

                    @if(app()->getLocale() == 'ar')
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    @endif
                    ajax: {
                        url : '{{ route('school.teacher.statistics') }}',
                        data: function (d) {
                            d.search = $("#search").val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'passed_tests', name: 'passed_tests'},
                        {data: 'failed_tests', name: 'failed_tests'},
                        {data: 'passed_tests_lessons', name: 'passed_tests_lessons'},
                        {data: 'failed_tests_lessons', name: 'failed_tests_lessons'},
                        {data: 'last_login', name: 'last_login'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#search').keyup(function(){
                $('#users-table').DataTable().draw(true);
            });
        });
    </script>
@endsection
