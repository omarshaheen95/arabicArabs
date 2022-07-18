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
            المشرفون
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                           المشرفون
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="{{ route('manager.supervisor.create') }}" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    إضافة مشرف
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
                                <label>اسم المشرف:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input" placeholder="اسم المشرف">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>المدرسة:</label>
                                <select class="form-control level" name="school_id" id="school_id">
                                    <option selected value="">اختر مدرسة</option>
                                    @foreach($schools as $school)
                                        <option  value="{{$school->id}}">{{$school->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 kt-margin-b-10-tablet-and-mobile">
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
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>المدرسة</th>
                        <th>التفعيل</th>
                        <th>أخر دخول</th>
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
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.supervisor.destroy", ":id") }}';
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
                        url : '{{ route('manager.supervisor.index') }}',
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
                        {data: 'school', name: 'school'},
                        {data: 'active', name: 'active'},
                        {data: 'last_login', name: 'last_login'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function (e) {
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });

            //Export Excel
            $('#kt_excel').click(function(e){
                e.preventDefault();
                $("#filter").attr("method",'post');
                $("#filter").attr("action",'{{route('manager.supervisor.export_supervisor_excel')}}');
                $('#filter').submit();

                $("#filter").attr("method",'');
                $("#filter").attr("action",'');
            });

        });
    </script>
@endsection
