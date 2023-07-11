{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
           المعلمون
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            المعلمون
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <div class="dropdown dropdown-inline">
                                    <button type="button" class="btn btn-danger btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="la la-se"></i> الإجراءات
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(114px, 38px, 0px);">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__section kt-nav__section--first">
                                                <span class="kt-nav__section-text">اختر إجراء</span>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" id="activate_teachers" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon la la-check"></i>
                                                    <span class="kt-nav__link-text">تفعيل</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" id="disable_teachers" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon la la-times"></i>
                                                    <span  class="kt-nav__link-text">تعطيل</span>
                                                </a>
                                            </li>

                                            <li class="kt-nav__item">
                                                <a href="#" id="approve_teachers" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon la la-check"></i>
                                                    <span  class="kt-nav__link-text">قبول</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <a href="{{ route('manager.teacher.create') }}" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    إضافة معلم
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" id="filter" action="" >
                        {{csrf_field()}}
                        <div class="row form-group">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>اسم المعلم:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input" placeholder="اسم المعلم">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>المدرسة :</label>
                                <select class="form-control level select2L" title="اختر مدرسة" name="school_id" id="school_id">
                                    @foreach($schools as $school)
                                        <option  value="{{$school->id}}">{{$school->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الحالة :</label>
                                <select class="form-control approved select2L" title="اختر حالة" name="approved" id="approved">
                                        <option  value="1">فعال</option>
                                        <option  value="2">غير فعال</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الإجراءات:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    بحث
                                </button>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_excel">
                                    <i class="la la-paper-plane"></i>
                                    تصدير
                                </button>
                            </div>

                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th><input type="checkbox" class='checkall' id='checkall'></th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الموبايل</th>
                        <th>المدرسة</th>
                        <th>عدد الطلاب</th>
                        <th>حالة القبول</th>
                        <th>التفعيل</th>
                        <th>آخر دخول</th>
                        <th>الإجراءات</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="delete_form">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <h5>هل أنت متأكد من حذف السجل المحدد ؟</h5>
                        <br/>
                        <p>حذف السجل المحدد سيؤدي لحذف السجلات المرتبطة به .</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approveModel" tabindex="-1" role="dialog" aria-labelledby="approveModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تأكيد القبول</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="approve_form">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <h5>هل أنت متأكد من قبول المعلمون المحددين</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">قبول</button>
                    </div>
                </form>
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
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.teacher.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            $(document).on('click','.approveRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.teacher.approveTeacher", ["teacher_id" => '']) }}'+ ':id';
                url = url.replace(':id', id );
                $('#approve_form').attr('action',url);
                console.log(url);
            }));
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
                        url : '{{ route('manager.teacher.index') }}',
                        data: function (d) {
                            var frm_data = $('#filter').serializeArray();
                            $.each(frm_data, function (key, val) {
                                d[val.name] = val.value;
                            });
                        }
                    },
                    columns: [
                        {data: 'check', name: 'check'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'mobile', name: 'mobile'},
                        {data: 'school', name: 'school'},
                        {data: 'users_count', name: 'users_count'},
                        {data: 'status', name: 'status'},
                        {data: 'active', name: 'active'},
                        {data: 'last_login', name: 'last_login'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function(e){
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });

            // Check all
            $('#checkall').click(function(){
                if($(this).is(':checked')){
                    $('.teacher_id').prop('checked', true);
                }else{
                    $('.teacher_id').prop('checked', false);
                }
            });

            // Delete record
            $(document).on('click', '#approve_teachers',function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var teacher_id = [];
                // Read all checked checkboxes
                $("input:checkbox[class=teacher_id]:checked").each(function () {
                    teacher_id.push($(this).val());
                });

                // Check checkbox checked or not
                if(teacher_id.length > 0){

                    // Confirm alert
                    var confirmdelete = confirm("هل أنت متأكد من قبول السجلات المحددة");
                    if (confirmdelete == true) {
                        $.ajax({
                            url: "{{route('manager.teacher.approveTeacher')}}",
                            type: 'post',
                            data: {
                                '_token': csrf,
                                teacher_id: teacher_id
                            },
                            success: function(response){
                                $('#users-table').DataTable().draw(true);
                                toastr.success(response.message);
                                $('#checkall').prop('checked', false);
                            }
                        });
                    }
                }
            });
            $(document).on('click', '#activate_teachers',function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var teacher_id = [];
                // Read all checked checkboxes
                $("input:checkbox[class=teacher_id]:checked").each(function () {
                    teacher_id.push($(this).val());
                });

                // Check checkbox checked or not
                if(teacher_id.length > 0){

                    // Confirm alert
                    var confirmdelete = confirm("هل أنت متأكد من تفعيل السجلات المحددة");
                    if (confirmdelete == true) {
                        $.ajax({
                            url: "{{route('manager.teacher.activateTeacher')}}",
                            type: 'post',
                            data: {
                                '_token': csrf,
                                teacher_id: teacher_id,
                                active: "1",
                            },
                            success: function(response){
                                $('#users-table').DataTable().draw(true);
                                toastr.success(response.message);
                                $('#checkall').prop('checked', false);
                            }
                        });
                    }
                }
            });
            $(document).on('click', '#disable_teachers',function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var teacher_id = [];
                // Read all checked checkboxes
                $("input:checkbox[class=teacher_id]:checked").each(function () {
                    teacher_id.push($(this).val());
                });

                // Check checkbox checked or not
                if(teacher_id.length > 0){

                    // Confirm alert
                    var confirmdelete = confirm("هل أنت متأكد من تعطيل السجلات المحددة");
                    if (confirmdelete == true) {
                        $.ajax({
                            url: "{{route('manager.teacher.activateTeacher')}}",
                            type: 'post',
                            data: {
                                '_token': csrf,
                                teacher_id: teacher_id,
                                active: "0",
                            },
                            success: function(response){
                                $('#users-table').DataTable().draw(true);
                                toastr.success(response.message);
                                $('#checkall').prop('checked', false);
                            }
                        });
                    }
                }
            });


            //Export Excel
            $('#kt_excel').click(function(e){
                e.preventDefault();
                $("#filter").attr("method",'post');
                $("#filter").attr("action",'{{route('manager.teacher.export_teachers_excel')}}');
                $('#filter').submit();

                $("#filter").attr("method",'');
                $("#filter").attr("action",'');
            });

        });
    </script>
@endsection
