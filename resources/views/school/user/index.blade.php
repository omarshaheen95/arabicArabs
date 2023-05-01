@extends('school.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{$title}}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{$title}}
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="" id="filter">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>اسم الطالب:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input" placeholder="اسم الطالب">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الصف :</label>
                                <select class="form-control grade" name="grade" id="grade">
                                    <option selected value="">اختر الصف </option>
                                    <option value="1" >1</option>
                                    <option value="2" >2</option>
                                    <option value="3" >3</option>
                                    <option value="4" >4</option>
                                    <option value="5" >5</option>
                                    <option value="6" >6</option>
                                    <option value="7" >7</option>
                                    <option value="8" >8</option>
                                    <option value="9" >9</option>
                                    <option value="10" >10</option>
                                    <option value="10" >11</option>
                                    <option value="10" >12</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>المعلم :</label>
                                <select class="form-control level" name="teacher_id" id="teacher_id">
                                    <option selected value="">اختر معلم</option>
                                    @foreach($teachers as $teacher)
                                        <option  value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الشعبة :</label>
                                <select class="form-control" name="section" id="section">
                                    <option selected value="">اختر شعبة</option>
                                    @foreach(schoolSections(Auth::guard('school')->user()->id) as $section)
                                        <option value="{{$section}}">{{$section}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الحالة:</label>
                                <select class="form-control status" name="status" id="status">
                                    <option selected value="">اختر حالة</option>
                                    <option  value="active">فعال</option>
                                    <option  value="expire">منتهي</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>تاريخ التسجيل:</label>
                                <input class="form-control date" id="created_at" name="created_at" type="text" placeholder="تاريخ التسجيل">
                            </div>
                            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
                                <label>الإجراءات:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                   بحث
                                </button>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_excel">
                                    <i class="la la-paper-plane"></i>
                                    إكسل
                                </button>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_cards">
                                    <i class="la la-list"></i>
                                    البطاقات
                                </button>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_cards_qr">
                                    <i class="la la-qrcode"></i>
                                    QR Codes
                                </button>
                            </div>

                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>المعلم</th>
                        <th>الصف</th>
                        <th>الشعبة</th>
                        <th>فعال حتى</th>
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

@endsection
@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("school.student.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
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
                        url : '{{ route('school.student.index') }}',
                        data: function (d) {
                            var frm_data = $('#filter').serializeArray();
                            $.each(frm_data, function (key, val) {
                                d[val.name] = val.value;
                            });
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'teacher', name: 'teacher'},
                        {data: 'grade', name: 'grade'},
                        {data: 'section', name: 'section'},
                        {data: 'active_to', name: 'active_to'},
                        {data: 'last_login', name: 'last_login'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function(e){
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });

            //jQuery detect user pressing enter
            $(document).on('keypress',function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $('#users-table').DataTable().draw(true);
                }
            });

            $('#kt_excel').click(function(e){
                e.preventDefault();
                $("#filter").attr("method",'post');
                $("#filter").attr("action",'{{route('school.user.export_students_excel')}}')
                $('#filter').submit();

                $("#filter").attr("method",'');
                $("#filter").attr("action",'')
            });
            $('#kt_cards').click(function(e){
                e.preventDefault();
                $("#filter").attr("method",'get');
                $("#filter").attr("action",'{{route('school.user.cards')}}')
                $('#filter').submit();

                $("#filter").attr("method",'');
                $("#filter").attr("action",'');
            });
            $('#kt_cards_qr').click(function(e){
                e.preventDefault();
                $("#filter").attr("method",'get');
                $("#filter").attr("action",'{{route('school.user.cardsQR')}}')
                $('#filter').submit();

                $("#filter").attr("method",'');
                $("#filter").attr("action",'');
            });
        });
    </script>
@endsection
