@extends('school.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
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
                            تقرير الاستخدام
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="{{route('school.report.usage_report')}}" id="filter">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Grade') }}:</label>
                                <select class="form-control grade" title="{{t('Select grade')}}" name="grades[]" multiple id="grades">
                                    <option value="" selected>{{t('All')}}</option>
                                    @foreach($grades as $grade)
                                    <option value="{{$grade->id}}" selected>{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>{{t('Select date')}}:</label>
                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                    <input type="text" class="form-control date" value=""
                                           name="start_date" placeholder="{{t('from date')}}"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" class="form-control date" value=""
                                           name="end_date" placeholder="{{t('to date')}}"/>
                                </div>
                            </div>

                            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br />
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    استخراج التقرير
                                </button>
                            </div>


                        </div>
                        <div class="row kt-margin-b-20">


                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>
    <!-- DataTables -->
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
            //jQuery detect user pressing enter
            $(document).on('keypress',function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $('#users-table').DataTable().draw(true);
                }
            });
            var all_option = true;
            $("#grades").on("changed.bs.select",
                function(e, clickedIndex, newValue, oldValue) {
                    if (clickedIndex == 0)
                    {
                        if (newValue == false)
                        {
                            all_option = false;
                        }else{
                            all_option = true;
                        }
                        if (all_option)
                        {
                            console.log('all_option is true');
                            // $('#assignment_students option').attr("selected","selected");
                            $('#grades').selectpicker('selectAll');
                            $('#grades').selectpicker('refresh');
                        }else{
                            console.log('all_option is false');
                            // $('#assignment_students option').attr("selected",false);
                            $('#grades').selectpicker('deselectAll');
                            $('#grades').selectpicker('refresh');
                        }
                    }
                    console.log($(this).val());
                });


            $("#grades option:first").click(function(){
                alert('select first');
                if ($($(this).is(':selected'))) {
                    $('#grades option').prop('selected', true);
                }else{
                    $('#grades option').prop('selected', true);
                }

                $('select[name="grades[]"]').selectpicker('refresh');
            });

        });
    </script>
@endsection
