@extends('manager.layout.container')


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{$title}}</h3>
                    </div>
                </div>

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @if(!is_null($file->error))
                                    <p>{{$file->error}}</p>
                                @elseif(!is_null($file->failures))
                                    <table class="table table-bordered">
                                        <thead>
                                        <th>Rows In File</th>
                                        </thead>
                                        <tbody>
                                        @foreach($file->failures as $key => $row)
                                            <tr>
                                                <td>Row : {{$key}}</td>
                                            </tr>
                                            @foreach($row as $log_error)
                                                <td>{{$log_error}}</td>
                                        @endforeach
                                        @endforeach
                                    </table>
                                @elseif(isset($error))
                                    <table class="table table-bordered">
                                        <thead>
                                        <th>Rows In File</th>
                                        </thead>
                                        <tbody>
                                        @foreach($error as $key => $row)
                                            <tr>
                                                <td>Row : {{$key}}</td>
                                            </tr>
                                            @foreach($row as $log_error)
                                                <td>{{$log_error}}</td>
                                        @endforeach
                                        @endforeach
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

