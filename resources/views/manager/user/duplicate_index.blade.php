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
            {{ t('Students') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Students') }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="{{ route('manager.user.create') }}"
                                   class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    {{ t('Add User') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" id="filter" action="">
                        {{csrf_field()}}
                        <div class="row form-group">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Student name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input"
                                       placeholder="{{t('Student name')}}">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Grade') }}:</label>
                                <select class="form-control grade" name="grade" id="grade">
                                    <option selected value="">{{t('Select grade')}}</option>
                                    <option value="15" >{{t('KG')}} 2</option>

                                    <option value="1">{{t('Grade')}} 1</option>
                                    <option value="2">{{t('Grade')}} 2</option>
                                    <option value="3">{{t('Grade')}} 3</option>
                                    <option value="4">{{t('Grade')}} 4</option>
                                    <option value="5">{{t('Grade')}} 5</option>
                                    <option value="6">{{t('Grade')}} 6</option>
                                    <option value="7">{{t('Grade')}} 7</option>
                                    <option value="8">{{t('Grade')}} 8</option>
                                    <option value="9">{{t('Grade')}} 9</option>
                                    <option value="10">{{t('Grade')}} 10</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('School') }}:</label>
                                <select class="form-control level" name="school_id" id="school_id">
                                    <option selected value="">{{t('Select school')}}</option>
                                    @foreach($schools as $school)
                                        <option value="{{$school->id}}">{{$school->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Package') }}:</label>
                                <select class="form-control package" name="package_id" id="package_id">
                                    <option selected value="">{{t('Select package')}}</option>
                                    @foreach($packages as $package)
                                        <option value="{{$package->id}}">{{$package->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Section') }}:</label>
                                <select class="form-control" name="section" id="section">
                                    <option selected value="">{{t('Select section')}}</option>
                                    @foreach(schoolSections() as $section)
                                        <option value="{{$section}}">{{$section}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Status') }}:</label>
                                <select class="form-control status" name="status" id="status">
                                    <option selected value="">{{t('Select status')}}</option>
                                    <option value="active">{{t('Active')}}</option>
                                    <option value="expire">{{t('Expire')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br/>
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                                <button type="submit" class="btn btn-info btn-elevate btn-icon-sm" id="kt_excel">
                                    <i class="la la-paper-plane"></i>
                                    {{t('Excel')}}
                                </button>
                                <button type="submit" class="btn btn-info btn-elevate btn-icon-sm" id="kt_cards">
                                    <i class="la la-list"></i>
                                    {{t('Cards')}}
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{ t('Name') }}</th>
                        <th>{{ t('Email') }}</th>
                        <th>{{ t('School') }}</th>
                        <th>{{ t('Package') }}</th>
                        <th>{{ t('Grade') }}</th>
                        <th>{{ t('Section') }}</th>
                        <th>{{ t('Active To') }}</th>
                        <th>{{ t('Last login') }}</th>
                        <th>{{ t('Actions') }}</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="delete_form">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <h5>{{ t('Are You Sure To Delete The Selected Record ?') }}</h5>
                        <br/>
                        <p>{{ t('Deleting The Record Will Delete All Records Related To It') }}</p>
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.deleteRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route("manager.user.delete_duplicate_user", ":id") }}';
                url = url.replace(':id', id);
                $('#delete_form').attr('action', url);
            }));
            $('#delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $('#delete_form').attr('action'),
                    type: "post",
                    data: $("#delete_form").serialize()
                })
                    .done(function (info) {
                        $('#users-table').DataTable().draw(true);
                        $("#deleteModel").modal('toggle');
                        toastr.success(info.message);

                    });
            });
            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    dom: `<'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

                    @if(app()->getLocale() == 'ar')
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    @endif
                    ajax: {
                        url: '{{ route('manager.user.duplicate_user') }}',
                        data: function (d) {
                            d.name = $("#name").val();
                            d.grade = $("#grade").val();
                            d.school_id = $("#school_id").val();
                            d.package_id = $("#package_id").val();
                            d.status = $("#status").val();
                            d.section = $("#section").val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'school', name: 'school'},
                        {data: 'package', name: 'package'},
                        {data: 'grade', name: 'grade'},
                        {data: 'section', name: 'section'},
                        {data: 'active_to', name: 'active_to'},
                        {data: 'last_login', name: 'last_login'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function (e) {
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });
            //jQuery detect user pressing enter
            $(document).on('keypress', function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                    $('#users-table').DataTable().draw(true);
                }
            });

        });
    </script>
@endsection
