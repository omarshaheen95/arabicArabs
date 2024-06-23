{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('manager.story.index') }}">{{ t('Stories') }}</a>
    </li>
    <li class="breadcrumb-item">
        {{ t('Story Assessment') }}
    </li>
@endpush
@section('title')
    {{ t('Story Assessment') }} : {{$story->name}}- {{$story->grade}}
@endsection
@section('content')

    <div class="row">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            @if($true_false_count)
                <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab"  type="button" role="tab"
                           data-bs-target="#kt_tabs_1_1">{{ t('True or False')}} </a>
                    </li>
            @endif
            @if($chose_count)
                <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_2" type="button" role="tab"
                           >{{t('Choose the Answer')}}</a>
                    </li>
            @endif
            @if($match_count)
                <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_3" type="button" role="tab">{{t('Matching')}}</a>
                    </li>
            @endif
            @if($sort_count)
                <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_4" type="button" role="tab">{{t('Sorting Words')}}</a>
                    </li>
            @endif
        </ul>
        <div class="tab-content">
            @if($true_false_count)
                <div class="tab-pane fade show active" id="kt_tabs_1_1" role="tabpanel">
                    <form enctype="multipart/form-data" id="true_false_form"
                          class="form-data"
                          action="{{ count($t_f_questions) > 0 ? route('manager.story.assessment.update', [$story->id, 1]):route('manager.story.storeAssessment', [$story->id, 1]) }}"
                          method="post">
                       @csrf
                        @if(count($t_f_questions))
                            @php
                                $i = 1;
                            @endphp
                            @foreach($t_f_questions as $t_f_question)
                                <div class="form-group row mt-2">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                        <input required class="form-control"
                                               name="t_f_question[{{$t_f_question->id}}]"
                                               type="text"
                                               value="{{$t_f_question->content}}">

                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2">{{ t('Correct Answer')}} :</label>
                                        <div class="d-flex gap-1">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required class="form-check-input" type="radio"
                                                       {{optional($t_f_question->trueFalse)->result == 1 ? 'checked':''}}
                                                       value="1" name="t_f[{{$t_f_question->id}}]" id="flexRadioLg"/>
                                                <label class="form-check-label" for="flexRadioLg">
                                                    {{t('True')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required
                                                       class="form-check-input"
                                                       {{optional($t_f_question->trueFalse)->result == 0 ? 'checked':''}}
                                                       type="radio" value="0" name="t_f[{{$t_f_question->id}}]">
                                                <label class="form-check-label" for="flexRadioLg">
                                                    {{t('False')}}
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2">{{t('Attachment')}} :</label>
                                            @if($t_f_question->attachment)
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="{{$t_f_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="{{asset($t_f_question->attachment)}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                        <input type="file" name="t_f_q_attachment[{{$t_f_question->id}}]" class="form-control">
                                    </div>
                                </div>
                                @php
                                    $i ++;
                                @endphp
                            @endforeach
                        @else
                            @for($i = 1; $i<=$true_false_count;$i++)
                                <div class="form-group row">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                        <input required class="form-control" name="t_f_question[{{$i}}]"
                                               type="text">

                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2">{{ t('Correct Answer')}} :</label>
                                        <div class="d-flex gap-1">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required class="form-check-input" type="radio" checked
                                                       value="1" name="t_f[{{$i}}]" id="flexRadioLg"/>
                                                <label class="form-check-label" for="flexRadioLg">
                                                    {{t('True')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required
                                                       class="form-check-input"

                                                       type="radio" value="0" name="t_f[{{$i}}]">
                                                <label class="form-check-label" for="flexRadioLg">
                                                    {{t('False')}}
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2">{{t('Attachment')}} :</label>
                                        <input type="file" name="t_f_q_attachment[{{$i}}]"
                                               class="form-control">
                                    </div>
                                </div>
                            @endfor
                        @endif
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2">{{t('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @if($chose_count)
                <div class="tab-pane fade" id="kt_tabs_1_2" role="tabpanel">
                    <form enctype="multipart/form-data" id="choose_form"
                          class="form-data"
                          action="{{ count($c_questions) > 0 ? route('manager.story.assessment.update', [$story->id, 2]):route('manager.story.storeAssessment', [$story->id, 2]) }}"
                          method="post">
                        @csrf
                        @if(count($c_questions))
                            @foreach($c_questions as $c_question)
                                <div class="form-group row mt-2">
                                    <div class="col-lg-6">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$loop->index+1}}:</label>
                                        <input required class="form-control"
                                               name="c_question[{{$c_question->id}}]" type="text"
                                               value="{{$c_question->content}}">
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2">{{ t('Correct Answer')}} :</label>
                                        <div class="d-flex gap-2">
                                            @foreach($c_question->options as $option)
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required {{$option->result == 1 ? 'checked':''}} class="form-check-input" type="radio"
                                                           value="{{$option->id}}"
                                                           name="c_q_a[{{$c_question->id}}]">
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        {{$loop->index+1}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2">{{t('Attachment')}} :</label>
                                            @if($c_question->attachment)
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="{{$c_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="{{asset($c_question->attachment)}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                        <input type="file" name="c_q_attachment[{{$c_question->id}}]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    @php
                                        $o_counter = 1;
                                    @endphp
                                    @foreach($c_question->options as $option)
                                        <div class="col-lg-3">
                                            <label class="mb-2">{{$o_counter}} :</label>
                                            <input required type="text" class="form-control"
                                                   name="c_q_option[{{$c_question->id}}][{{$option->id}}]"
                                                   value="{{$option->content}}">
                                        </div>
                                        @php
                                            $o_counter ++;
                                        @endphp
                                    @endforeach
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                            @endforeach
                        @else
                            @for($i = 1; $i<=$chose_count;$i++)
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                        <input required class="form-control" name="c_question[{{$i}}]"
                                               type="text">
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2">{{ t('Correct Answer')}} :</label>
                                        <div class="d-flex gap-3">
                                            @foreach(range(1,3) as $item)
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required  class="form-check-input" type="radio"
                                                           value="{{$item}}"
                                                           name="c_q_a[{{$i}}]">
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        {{$item}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <label class="mb-2">{{ t('Attachment')}}  :</label>
                                        <input type="file" name="c_q_attachment[{{$i}}]"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    @foreach(range(1,3) as $item)
                                        <div class="col-lg-3">
                                            <label class="mb-2">{{$item}} :</label>
                                            <input required type="text" class="form-control"
                                                   name="c_q_option[{{$i}}][{{$item}}]">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                            @endfor
                        @endif
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2">{{t('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @if($match_count)
                <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                    <form enctype="multipart/form-data" id="match_form"
                          class="form-data"
                          action="{{ count($m_questions) ? route('manager.story.assessment.update', [$story->id, 3]):route('manager.story.storeAssessment', [$story->id, 3]) }}"
                          method="post">
                        @csrf

                        @if(count($m_questions))

                            @foreach($m_questions as $m_question)
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$loop->index+1}}:</label>
                                        <input required class="form-control"
                                               name="m_question[{{$m_question->id}}]" type="text"
                                               value="{{$m_question->content}}">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2">{{t('Attachment')}} :</label>
                                            @if($m_question->attachment)
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="{{$m_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="{{asset($m_question->attachment)}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                        <input type="file" name="m_q_attachment[{{$m_question->id}}]" class="form-control">
                                    </div>
                                </div>

                                @foreach($m_question->matches as $match)
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-8">
                                            <label class="mb-2">{{ t('Option')}}  {{$loop->index+1}}:</label>
                                            <input required class="form-control"
                                                   name="m_q_option[{{$match->id}}]"
                                                   value="{{$match->content}}" type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2">{{ t('Answer')}} :</label>
                                            <input required class="form-control"
                                                   name="m_q_answer[{{$match->id}}]"
                                                   value="{{$match->result}}" type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="d-flex">
                                                <label class="mb-2">{{t('Image')}} :</label>
                                                @if($match->image)
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="{{$match->id}}" class="btn btn-icon btn-danger deleteMatchImageRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="{{asset($match->image)}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <input type="file" name="m_q_image[{{$match->id}}]" class="form-control">
                                        </div>

                                    </div>
                                @endforeach
                                <div class="separator separator-dashed my-5"></div>

                            @endforeach
                        @else
                            @for($i=1;$i<=$match_count;$i++)
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                        <input required class="form-control" name="m_question[{{$i}}]"
                                               type="text">
                                        <input type="hidden" name="mark[{{$i}}]" value="{{$mark}}">

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2">{{ t('Attachment')}}  :</label>
                                        <input type="file" name="m_q_attachment[{{$i}}]"
                                               class="form-control">
                                    </div>
                                </div>
                                @for($y=1;$y<=$match_option;$y++)
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-8">
                                            <label class="mb-2">{{ t('Option')}}  {{$y}}:</label>
                                            <input required class="form-control" name="m_q_option[{{$i}}][{{$y}}]"
                                                   type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2">{{ t('Answer')}} :</label>
                                            <input required class="form-control" name="m_q_answer[{{$i}}][{{$y}}]"
                                                   type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2">{{ t('Image')}} :</label>
                                            <input class="form-control" name="m_q_image[{{$i}}][{{$y}}]"
                                                   type="file">
                                        </div>
                                    </div>
                                @endfor
                                <div class="separator separator-dashed my-5"></div>

                            @endfor
                        @endif
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2">{{t('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @if($sort_count)
                <div class="tab-pane" id="kt_tabs_1_4" role="tabpanel">
                    <form enctype="multipart/form-data" id="sort_form"
                          class="form-data"
                          action="{{ count($s_questions) > 0 ? route('manager.story.assessment.update', [$story->id, 4]):route('manager.story.storeAssessment', [$story->id, 4]) }}"
                          method="post">
                        @csrf
                        @if(count($s_questions))

                            @foreach($s_questions as $s_question)

                                <div class="form-group row mb-2">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$loop->index+1}}:</label>
                                        <input required class="form-control"
                                               name="s_question[{{$s_question->id}}]" type="text"
                                               value="{{$s_question->content}}">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2">{{t('Attachment')}} :</label>
                                            @if($s_question->attachment)
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="{{$s_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="{{asset($s_question->attachment)}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                        <input type="file" name="s_q_attachment[{{$s_question->id}}]" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row text-success" id="row-{{$s_question->id}}">
                                    @php
                                        $o_counter = 1;
                                    @endphp
                                    @foreach($s_question->sort_words as $sort_word)
                                        <div class="col-4 mb-3 option">
                                            <label class="mb-2">{{ t('Option')}}  {{$loop->index+1}} :
                                                <a href="#"
                                                   data-id="{{$sort_word->id}}"
                                                   data-bs-toggle="modal"
                                                   data-target="#deleteSortWord"
                                                   class="text-danger delete_old_input">{{ t('Delete')}} </a></label>
                                            <input required class="form-control"
                                                   name="s_q_option[{{$s_question->id}}][{{$sort_word->id}}]"
                                                   value="{{$sort_word->content}}" type="text">
                                        </div>

                                    @endforeach
                                    <div class="col-1 d-flex align-items-end mt-3">
                                        <button type="button" data-id="{{$s_question->id}}"
                                                id="add_label_{{$s_question->id}}"
                                                class="btn btn-danger btn-icon btn-block add_button"><i
                                                class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>
                                </div>

                            @endforeach
                        @else

                            @for($i=1;$i<=$sort_count;$i++)
                                <div class="form-group row mb-2">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                        <input required class="form-control" name="s_question[{{$i}}]"
                                               type="text">

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2">{{ t('Attachment')}}  :</label>
                                        <input type="file" name="s_q_attachment[{{$i}}]"
                                               class="form-control">
                                    </div>

                                </div>
                                <div class="form-group row" id="row-{{$i}}">
                                    @foreach(range(1,2) as $item)
                                        <div class="col-lg-4 mt-3 option">
                                            <label class="mb-2">{{ t('Option').$item}}:</label>
                                            <input required class="form-control option" name="s_q_option[{{$i}}][{{$item}}]" type="text">
                                        </div>
                                    @endforeach
                                    <div class="col-1 d-flex align-items-center mt-3">
                                        <button type="button" data-id="{{$i}}"
                                                id="add_label_{{$i}}"
                                                class="btn btn-danger btn-icon btn-block add_button"><i
                                                class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>
                                </div>
                            @endfor
                        @endif
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2">{{t('Save')}}</button>
                            </div>
                        </div>

                    </form>
                </div>
            @endif
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
                    <form method="post" action="" id="delete_attachment_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <h5>{{ t('Are You Sure To Delete The Selected Record ?') }}</h5>
                            <br/>
                            <p>{{ t('Deleting The Record Will Delete All Records Related To It') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ t('Cancel') }}</button>
                            <button type="submit" class="btn btn-warning">{{ t('Delete') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteSortWord" tabindex="-1" role="dialog" aria-labelledby="deleteSortWord"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel">{{ t('Confirm Delete') }} {{t('Sort Answer')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_sort_word_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <h5>{{ t('Are You Sure To Delete The Selected Record ?') }}</h5>
                            <br/>
                            <p>{{ t('Deleting The Record Will Delete All Records Related To It') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ t('Cancel') }}</button>
                            <button type="submit" class="btn btn-warning">{{ t('Delete') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteMatchImageModel" tabindex="-1" role="dialog" aria-labelledby="deleteMatchImageModel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ t('Confirm Delete') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_match_attachment_form">
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

        @endsection

        @section('script')
            <script src="{{asset('assets_v1/js/jquery-validation/dist/jquery.validate.js')}}"></script>
            <script src="{{asset('assets_v1/js/jquery-validation/dist/additional-methods.js')}}"></script>
            @if(app()->getLocale()=='ar')
                <script src="{{asset('assets_v1/js/jquery-validation/dist/localization/messages_ar.js')}}"></script>
            @endif


            <script>
                $(document).ready(function () {
                    $(document).on('click', '.deleteRecord', (function () {
                        let id = $(this).data("id");
                        let url = '{{route('manager.story.remove_attachment', ':id')}}'.replace(':id', id);
                        let parent = $(this).parent()
                        showAlert("{{t('Delete Record')}}","{{t('Are you sure to for deleting process?')}}",'warning',
                            true,true,function (callback) {
                                if (callback){
                                    showLoadingModal()
                                    $.ajax({
                                        url: url,
                                        type: 'post',
                                        data: {'_token':"{{csrf_token()}}"},
                                        success: function(response){
                                            hideLoadingModal()
                                            parent.children('a').each(function (index,ele) {
                                                ele.remove()
                                            })
                                            toastr.success(response.message);
                                        },
                                        error(error){
                                            hideLoadingModal()
                                            toastr.error(error.responseJSON.message);
                                        }
                                    });
                                }
                            })
                    }));
                    $(document).on('click', '.delete_old_input', (function () {
                        let id = $(this).data("id");
                        let url = '{{route('manager.story.remove_sort_word', ':id')}}'.replace(':id', id);
                        let parent = $(this).parent()
                        showAlert("{{t('Delete Input')}}","{{t('Are you sure to for deleting process?')}}",'warning',
                            true,true,function (callback) {
                                if (callback){
                                    showLoadingModal()
                                    $.ajax({
                                        url: url,
                                        type: 'post',
                                        data: {'_token':"{{csrf_token()}}"},
                                        success: function(response){
                                            hideLoadingModal()
                                            parent.remove()
                                            toastr.success(response.message);
                                        },
                                        error(error){
                                            hideLoadingModal()
                                            toastr.error(error.responseJSON.message);
                                        }
                                    });
                                }
                            })
                    }));
                    $(document).on('click', '.deleteMatchImageRecord', (function () {
                        let id = $(this).data("id");
                        let url = '{{route('manager.story.remove_match_attachment', ':id')}}'.replace(':id', id);
                        let parent = $(this).parent()
                        showAlert("{{t('Delete Image')}}","{{t('Are you sure to for deleting process?')}}",'warning',
                            true,true,function (callback) {
                                if (callback){
                                    showLoadingModal()
                                    $.ajax({
                                        url: url,
                                        type: 'post',
                                        data: {'_token':"{{csrf_token()}}"},
                                        success: function(response){
                                            hideLoadingModal()
                                            parent.remove()
                                            toastr.success(response.message);
                                        },
                                        error(error){
                                            hideLoadingModal()
                                            toastr.error(error.responseJSON.message);
                                        }
                                    });
                                }
                            })
                    }));

                    validateAndSubmit('form-data') //validate any form and submit data


                    var x = 1; //Initial field counter is 1
                    var y = 1; //Initial field counter is 1

                    var maxField = 10 //Input fields increment limitation
                    var addButton = $('.add_button'); //Add button selector
                    //Once add button is clicked
                    $(addButton).click(function () {
                        var row_id = $(this).attr('data-id');
                        var wrapper_row = $('#row-' + row_id); //Input field wrapper
                        x = $('#row-' + row_id).children('.option').length;
                        y = $('#row-' + row_id).children('.option').length;
                        //Check maximum number of input fields
                        if (x < maxField) {
                            x++; //Increment field counter
                            y++; //Increment field counter.
                            $(this).parent().before(
                                "<div class=\"col-lg-4 mt-3 option\">\n" +
                                "<label class="mb-2">{{ t('Option')}}  " + y + " : <a href='#' class='text-danger delete_input'>{{ t('Delete')}} </a></label>\n" +
                                "<input required class=\"form-control\" name=\"s_q_option[" + row_id + "]["+y+"]\" type=\"text\">\n")
                        }
                    });
                    //Once remove button is clicked
                    $(document).on('click', '.delete_input', function (e) {
                        e.preventDefault();
                        $(this).parent().parent('div').remove(); //Remove field html
                        // x--;
                    });
                });
            </script>
@endsection
