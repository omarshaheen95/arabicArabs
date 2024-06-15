{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('teacher.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('Students works') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Students works') }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">

                    </div>

                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="" method="post">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Student name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input" placeholder="{{t('Student name')}}">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Grade') }}:</label>
                                <select class="form-control grade" name="grade" id="grade">
                                    <option selected value="">{{t('Select grade')}}</option>
                                    <option value="15" >{{t('KG')}} 2</option>

                                    <option value="1" >{{t('Grade')}} 1</option>
                                    <option value="2" >{{t('Grade')}} 2</option>
                                    <option value="3" >{{t('Grade')}} 3</option>
                                    <option value="4" >{{t('Grade')}} 4</option>
                                    <option value="5" >{{t('Grade')}} 5</option>
                                    <option value="6" >{{t('Grade')}} 6</option>
                                    <option value="7" >{{t('Grade')}} 7</option>
                                    <option value="8" >{{t('Grade')}} 8</option>
                                    <option value="9" >{{t('Grade')}} 9</option>
                                    <option value="10" >{{t('Grade')}} 10</option>
                                </select>
                            </div>
                            <input type="hidden" name="teacher_id" value="{{Auth::user()->id}}">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                            </div>

                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{ t('Student') }}</th>
                        <th>{{ t('Grade') }}</th>
                        <th>{{ t('Lesson') }}</th>
                        <th>{{ t('Status') }}</th>
                        <th>{{ t('Submitted at') }}</th>
                        <th>{{ t('Actions') }}</th>
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
                        url : '{{ route('teacher.students_works.index') }}',
                        data: function (d) {
                            d.name = $("#name").val();
                            d.grade = $("#grade").val();
                            d.lesson = $("#lesson").val();
                            d.level = $("#level").val();
                        }
                    },
                    columns: [
                        {data: 'user', name: 'user'},
                        {data: 'grade', name: 'grade'},
                        {data: 'lesson', name: 'lesson'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'},
                    ],
                });
            });
        });

        $('#kt_search').click(function(e){
            e.preventDefault();
            $('#users-table').DataTable().draw(true);
        });
    </script>
@endsection
