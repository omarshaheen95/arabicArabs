@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            القصص
        </li>
    @endpush
    @push('search')
        <div class="kt-subheader-search" style="background: linear-gradient(to right,#31ab3f,#31ab3f);">
            <h3 class="kt-subheader-search__title">
                {{ t('search') }}
            </h3>
            <form class="kt-form">
                <div class="kt-grid kt-grid--desktop kt-grid--ver-desktop">
                    <div class="row" style="width: 100%">
                        <div class="col-lg-6">
                            <div class="kt-input-icon kt-input-icon--pill kt-input-icon--right">
                                <input style="background: white" type="text" id="search" class="form-control form-control-pill" placeholder="{{ t('keywords') }}">
                                <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-search"></i></span></span>
                            </div>

                        </div>
                        <div class="col-lg-2">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            القصص
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="{{ route('manager.story.create') }}" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    إضافة قصة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>الاسم</th>
                        <th>الصف</th>
                        <th>الاجراء</th>
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
                var url = '{{ route("manager.story.destroy", ":id") }}';
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
                        url : '{{ route('manager.story.index') }}',
                        data: function (d) {
                            d.search = $("#search").val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'grade', name: 'grade'},
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
