
@isset($edit_url)
    <a href="{{$edit_url}}" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a>
@endisset
@isset($show_url)
    <a href="{{$show_url}}" class="btn btn-icon btn-danger "><i class="la {{isset($show_icon) ? $show_icon:'la-eye'}}"></i></a>
@endisset
@isset($row)
    <button type="button" data-id="{{$row->id}}" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger">
        <i class="la la-trash"></i>
    </button>
@endisset

@isset($status_row)
    <button type="button" data-id="{{$row->id}}" data-toggle="modal" data-target="#statusModel" class="statusRecord btn btn-icon btn-danger">
        <i class="la la-refresh"></i>
    </button>
@endisset
@isset($login_url)
    <a href="{{$login_url}}" class="btn btn-icon btn-danger " target="_blank"><i class="la la-arrow-left"></i></a>
@endisset
