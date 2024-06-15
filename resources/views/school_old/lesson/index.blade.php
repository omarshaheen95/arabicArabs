{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('school.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('Control Lessons') }}
        </li>
    @endpush

    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Control Lessons') }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="#storeModel" data-toggle="modal" data-target="#storeModel" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    {{ t('Hide Lesson') }}
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="" method="post">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
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
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Level') }}:</label>
                                <select class="form-control level_id" name="level_id" id="level_id">
                                    <option selected value="">{{t('Select level')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Lesson') }}:</label>
                                <select class="form-control lesson_id" name="lesson_id" id="lesson_id">
                                    <option selected value="">{{t('Select lesson')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                            </div>
                        </div>
                        <div class="row kt-margin-t-20">

                        </div>
                    </form>

                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{ t('Lesson') }}</th>
                        <th>{{ t('Level') }}</th>
                        <th>{{ t('Grade') }}</th>
                        <th>{{ t('Actions') }}</th>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ t('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="storeModel" tabindex="-1" role="dialog" aria-labelledby="storeModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Hide Lesson') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="{{route('school.lesson.store')}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="col-lg-12 mt-3 kt-margin-b-10-tablet-and-mobile">
                            <label>{{ t('Grade') }}:</label>
                            <select class="form-control grade" required name="grade" id="grade">
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
                        <div class="col-lg-12 mt-3 kt-margin-b-10-tablet-and-mobile">
                            <label>{{ t('Level') }}:</label>
                            <select class="form-control level_id" required name="level_id" id="level_id">
                                <option selected value="">{{t('Select level')}}</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mt-3 kt-margin-b-10-tablet-and-mobile">
                            <label>{{ t('Lesson') }}:</label>
                            <select class="form-control lesson_id" required name="lesson_id" id="lesson_id">
                                <option selected value="">{{t('Select lesson')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ t('Save') }}</button>
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
                var url = '{{ route("school.lesson.destroy", ":id") }}';
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
                        url : '{{ route('school.lesson.index') }}',
                        data: function (d) {
                            d.search = $("#search").val();
                        }
                    },
                    columns: [
                        {data: 'lesson', name: 'lesson'},
                        {data: 'level', name: 'level'},
                        {data: 'grade', name: 'grade'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });

            $('#kt_search').click(function(e){
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });

            $('select[name="grade"]').change(function () {
                var id = $(this).val();
                var url = '{{ route("school.getLevelsByGrade", ":id") }}';
                url = url.replace(':id', id );
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    $('select[name="level_id"]').html(data.html);
                    $('select[name="level_id"]').selectpicker('refresh');
                });
            });
            $('select[name="level_id"]').change(function () {
                var id = $(this).val();
                var url = '{{ route("school.getLessonsByLevel", ":id") }}';
                url = url.replace(':id', id );
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    $('select[name="lesson_id"]').html(data.html);
                    $('select[name="lesson_id"]').selectpicker('refresh');
                });
            });
        });
    </script>
@endsection
