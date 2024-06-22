@extends('manager.layout.container')
@section('pre_style')
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5')}}" rel="stylesheet"
          type="text/css"/>
@endsection
@section('page-title')
    {{$title}}
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item text-muted">
        <a href="{{route('manager.import_files.index')}}" class="text-muted">
            {{t('Import Files')}}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        {{$title}}
    </li>

@endpush
@section('content')
    <!--begin::Card-->
    <div class="card ">
        <div class="card-header">
            <h3 class="card-label">{{$title}}</h3>
        </div>
        <div class="card-body">

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
    <!--end::Card-->
@endsection
@section('script')

@endsection
