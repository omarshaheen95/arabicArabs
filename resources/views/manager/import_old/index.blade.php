{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('manager.layout.container')
@section('style')
    <link href="{{asset('cdn_files/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ $title }}
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
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="{{ route('manager.import_users_files.create') }}" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    {{ t('Import File') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th class="text-start">{{t('School')}}</th>
                        <th class="text-start">{{t('File')}}</th>
                        <th class="text-start">{{t('Created Students')}}</th>
                        <th class="text-start">{{t('Updated Students')}}</th>
                        <th class="text-start">{{t('Failed Data Students')}}</th>
                        <th class="text-start">{{t('Status')}}</th>
                        <th class="text-start">{{t('Creation Date')}}</th>
                        <th class="text-start">{{t('Actions')}}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="delete_form">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <h5>{{ t('Are You Sure To Delete The Selected Record ?') }}</h5>
                        <br />
                        <p>{{ t('Deleting The Record Will Delete All Records Related To It') }}</p>
                        <br>
                        <div class="form-group row">
                            <label class="col-6 col-form-label font-weight-bold">{{t('Delete students when deleting the import file')}}</label>
                            <div class="col-3">
                                        <span class="kt-switch">
                                            <label class="mb-2">
                                            <input type="checkbox" value="1" name="delete_students">
                                            <span></span>
                                            </label>
                                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ t('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="{{asset('cdn_files/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('cdn_files/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.import_users_files.destroy", ":id") }}';
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
                        url: "{{asset('cdn_files/Arabic.json')}}"
                    },
                    @endif
                    ajax: {
                        url : '{{ route('manager.import_users_files.index') }}',
                        data: function (d) {
                            d.search = $("#search").val();
                        }
                    },
                    columns: [
                        {data: 'school', name: 'school'},
                        {data: 'original_file_name', name: 'original_file_name'},
                        {data: 'created_rows_count', name: 'created_rows_count'},
                        {data: 'updated_rows_count', name: 'updated_rows_count'},
                        {data: 'failed_rows_count', name: 'failed_rows_count'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });

        });
    </script>
@endsection
