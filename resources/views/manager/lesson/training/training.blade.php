@extends('manager.layout.container')

@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('manager.lesson.index') }}">الدروس</a>
    </li>
    <li class="breadcrumb-item">
        {{ isset($title) ? $title:'' }}
    </li>
@endpush

@section('title')
    {{$lesson->name}} - {{$lesson->grade->name}}
@endsection
@section('actions')
    @can('lesson review')
        <a href="{{route('manager.lesson.review', [$lesson->id, 'training'])}}" target="_blank" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-eye"></i>
            {{t('Preview')}}
        </a>
    @endcan
@endsection
@section('content')

    <div class="row">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_0" type="button" role="tab"
                       >{{t('Lesson')}}</a>
                </li>
                @if($lesson->grade->true_false)
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab"  type="button" role="tab"
                           data-bs-target="#kt_tabs_1_1">{{ t('True or False')}} </a>
                    </li>
                @endif
                @if($lesson->grade->choose)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_2" type="button" role="tab"
                           >{{t('Choose the Answer')}}</a>
                    </li>
                @endif
                @if($lesson->grade->match)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_3" type="button" role="tab">{{t('Matching')}}</a>
                    </li>
                @endif
                @if($lesson->grade->sort)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_4" type="button" role="tab">{{t('Sorting Words')}}</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content py-5">
                <div class="tab-pane fade" id="kt_tabs_1_0" role="tabpanel">
                    <div class="row">
                        {!! $lesson->content !!}
                    </div>

                </div>

                @if($lesson->grade->true_false)
                    <div class="tab-pane fade show active" id="kt_tabs_1_1" role="tabpanel">
                        <form enctype="multipart/form-data" id="true_false_form"
                              class="form-data"
                              action="{{ route('manager.lesson.training.update', [$lesson->id, 1]) }}"
                              method="post">
                             @csrf
                            @if(isset($t_f_questions) && count($t_f_questions))
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($t_f_questions as $t_f_question)
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-7">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control"
                                                   name="old_t_f_question[{{$t_f_question->id}}]"
                                                   type="text"
                                                   value="{{$t_f_question->content}}">
                                            <input type="hidden" name="mark[{{$t_f_question->id}}]"
                                                   value="6"/>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>{{ t('Correct Answer')}} :</label>
                                            <div class="d-flex gap-1">
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required class="form-check-input" type="radio"
                                                           {{optional($t_f_question->trueFalse)->result == 1 ? 'checked':''}}
                                                           value="1" name="old_t_f[{{$t_f_question->id}}]" id="flexRadioLg"/>
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        {{t('True')}}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required
                                                           class="form-check-input"
                                                           {{optional($t_f_question->trueFalse)->result == 0 ? 'checked':''}}
                                                           type="radio" value="0" name="old_t_f[{{$t_f_question->id}}]">
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        {{t('False')}}
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex">
                                                <label>{{t('Attachment')}} :</label>
                                                @if($t_f_question->getFirstMediaUrl('t_imageQuestion'))
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="{{$t_f_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="{{$t_f_question->getFirstMediaUrl('t_imageQuestion')}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <input type="file" name="old_t_f_q_attachment[{{$t_f_question->id}}]" class="form-control">
                                        </div>
                                    </div>
                                    @php
                                        $i ++;
                                    @endphp
                                @endforeach
                            @else
                                @for($i = 1; $i<=$lesson->grade->true_false;$i++)
                                    <div class="form-group row">
                                        <div class="col-lg-7">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control" name="t_f_question[{{$i}}]"
                                                   type="text">
                                            <input type="hidden" name="mark[{{$i}}]" value="6"/>

                                        </div>
                                        <div class="col-lg-2">
                                            <label>{{ t('Correct Answer')}} :</label>
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
                                            <label>{{t('Attachment')}} :</label>
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

                @if($lesson->grade->choose)
                    <div class="tab-pane fade" id="kt_tabs_1_2" role="tabpanel">
                        <form enctype="multipart/form-data" id="choose_form"
                              class="form-data"
                              action="{{ route('manager.lesson.training.update', [$lesson->id, 2]) }}"
                              method="post">
                            @csrf
                            @if(isset($c_questions) && count($c_questions))
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($c_questions as $c_question)
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-6">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control"
                                                   name="old_c_question[{{$c_question->id}}]" type="text"
                                                   value="{{$c_question->content}}">
                                            @if($i == 3)
                                                <input type="hidden" name="mark[{{$c_question->id}}]"
                                                       value="8"/>
                                            @else
                                                <input type="hidden" name="mark[{{$c_question->id}}]"
                                                       value="7"/>
                                            @endif
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="mb-2">{{ t('Correct Answer')}} :</label>
                                            <div class="d-flex gap-2">
                                                @php
                                                    $o_counter = 1;
                                                @endphp
                                                    @foreach($c_question->options as $option)
                                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                            <input required {{$option->result == 1 ? 'checked':''}} class="form-check-input" type="radio"
                                                                   value="{{$option->id}}"
                                                                   name="old_c_q_a[{{$c_question->id}}]">
                                                            <label class="form-check-label" for="flexRadioLg">
                                                                {{$o_counter}}
                                                            </label>
                                                        </div>
                                                    @php
                                                        $o_counter ++;
                                                    @endphp
                                                @endforeach
                                        </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex">
                                                <label>{{t('Attachment')}} :</label>
                                                @if($c_question->getFirstMediaUrl('t_imageQuestion'))
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="{{$c_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="{{$c_question->getFirstMediaUrl('t_imageQuestion')}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <input type="file" name="old_c_q_attachment[{{$c_question->id}}]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        @php
                                            $o_counter = 1;
                                        @endphp
                                        @foreach($c_question->options as $option)
                                            <div class="col-lg-3">
                                                <label>{{$o_counter}} :</label>
                                                <input required type="text" class="form-control"
                                                       name="old_c_q_option[{{$c_question->id}}][{{$option->id}}]"
                                                       value="{{$option->content}}">
                                            </div>
                                            @php
                                                $o_counter ++;
                                            @endphp
                                        @endforeach
                                    </div>
                                    @php
                                        $i ++;
                                    @endphp
                                    <div class="separator separator-dashed my-5"></div>

                                @endforeach
                            @else
                                @for($i = 1; $i<=$lesson->grade->choose;$i++)
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control" name="c_question[{{$i}}]"
                                                   type="text">
                                            @if($i == 3)
                                                <input type="hidden" name="mark[{{$i}}]" value="8"/>
                                            @else
                                                <input type="hidden" name="mark[{{$i}}]" value="7"/>
                                            @endif

                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ t('Correct Answer')}} :</label>
                                            <div class="d-flex gap-3">
                                                @foreach(range(1,4) as $item)
                                                    @if($item !=4 || $item == 4 && $lesson->grade->grade_number != 0)
                                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                        <input required  class="form-check-input" type="radio"
                                                               value="{{$item}}"
                                                               name="c_q_a[{{$i}}]">
                                                        <label class="form-check-label" for="flexRadioLg">
                                                            {{$item}}
                                                        </label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label>{{ t('Attachment')}}  :</label>
                                            <input type="file" name="c_q_attachment[{{$i}}]"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        @foreach(range(1,4) as $item)
                                            @if($item !=4 || $item == 4 && $lesson->grade->grade_number != 0)
                                                <div class="col-lg-3">
                                                    <label>{{$item}} :</label>
                                                    <input required type="text" class="form-control"
                                                           name="c_q_option[{{$i}}][{{$item}}]">
                                                </div>
                                            @endif
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

                @if($lesson->grade->match)
                    <div class="tab-pane fade" id="kt_tabs_1_3" role="tabpanel">
                        <form enctype="multipart/form-data" id="match_form"
                              class="form-data"
                              action="{{ route('manager.lesson.training.update', [$lesson->id, 3]) }}"
                              method="post">
                            {{ csrf_field() }}
                            @if(isset($m_questions) && count($m_questions))
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($m_questions as $m_question)
                                    <div class="form-group row">
                                        <div class="col-lg-9">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control"
                                                   name="old_m_question[{{$m_question->id}}]" type="text"
                                                   value="{{$m_question->content}}">
                                            <input type="hidden" name="mark[{{$m_question->id}}]" value="8">
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex">
                                                <label>{{t('Attachment')}} :</label>
                                                @if($m_question->getFirstMediaUrl('t_imageQuestion'))
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="{{$m_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="{{$m_question->getFirstMediaUrl('t_imageQuestion')}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <input type="file" name="old_m_q_attachment[{{$m_question->id}}]" class="form-control">
                                        </div>
                                    </div>
                                    @php
                                        $i ++;
                                    @endphp
                                    @php
                                        $o_counter = 1;
                                    @endphp
                                    @foreach($m_question->matches as $match)
                                        <div class="form-group row mt-2">
                                            <div class="col-lg-8">
                                                <label>{{ t('Option')}}  {{$o_counter}}:</label>
                                                <input required class="form-control"
                                                       name="old_m_q_option[{{$match->id}}]"
                                                       value="{{$match->content}}" type="text">
                                            </div>
                                            <div class="col-lg-2">
                                                <label>{{ t('Answer')}} :</label>
                                                <input required class="form-control"
                                                       name="old_m_q_answer[{{$match->id}}]"
                                                       value="{{$match->result}}" type="text">
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="d-flex">
                                                    <label>{{t('Image')}} :</label>
                                                    @if($match->getFirstMediaUrl('t_match'))
                                                        <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                            <a data-id="{{$match->id}}" class="btn btn-icon btn-danger deleteMatchImageRecord" style="height: 20px; width: 20px">
                                                                <i class="la la-close la-2"></i>
                                                            </a>
                                                            <a href="{{$match->getFirstMediaUrl('t_match')}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                                <i class="la la-eye la-2"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                </div>
                                                <input type="file" name="old_m_q_image[{{$match->id}}]" class="form-control">
                                            </div>

                                        </div>
                                        @php
                                            $o_counter ++;
                                        @endphp
                                    @endforeach
                                    <div class="separator separator-dashed my-5"></div>
                                @endforeach
                            @else
                                @for($i=0; $i<$lesson->grade->match; $i++)
                                    <div class="form-group row">
                                        <div class="col-lg-9">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i + 1}}:</label>
                                            <input required class="form-control" name="m_question[{{$i}}]"
                                                   type="text">
                                            <input type="hidden" name="mark[{{$i}}]" value="8">

                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ t('Attachment')}}  :</label>
                                            <input type="file" name="m_q_attachment[{{$i}}]"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    @foreach(range(1,3) as $item)
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label>{{ t('Option')}}  {{$item}}:</label>
                                                <input required class="form-control" name="m_q_option[{{$i}}][{{$item}}]"
                                                       type="text">
                                            </div>
                                            <div class="col-lg-2">
                                                <label>{{ t('Answer')}} :</label>
                                                <input required class="form-control" name="m_q_answer[{{$i}}][{{$item}}]"
                                                       type="text">
                                            </div>
                                            <div class="col-lg-2">
                                                <label>{{ t('Image')}} :</label>
                                                <input class="form-control" name="m_q_image[{{$i}}][{{$item}}]"
                                                       type="file">
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($lesson->grade->grade_number != 0 )
                                        <div class="form-group row mt-2">
                                            <div class="col-lg-8">
                                                <label>{{ t('Option')}}  {{4}}:</label>
                                                <input required class="form-control" name="m_q_option[{{$i}}][4]"
                                                       type="text">
                                            </div>
                                            <div class="col-lg-2">
                                                <label>{{ t('Answer')}} :</label>
                                                <input required class="form-control" name="m_q_answer[{{$i}}][4]"
                                                       type="text">
                                            </div>
                                            <div class="col-lg-2">
                                                <label>{{ t('Image')}} :</label>
                                                <input class="form-control" name="m_q_image[{{$i}}][4]"
                                                       type="file">
                                            </div>
                                        </div>
                                    @endif

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

                @if($lesson->grade->sort)
                    <div class="tab-pane fade" id="kt_tabs_1_4" role="tabpanel">
                        <form enctype="multipart/form-data" id="sort_form"
                              class="form-data"
                              action="{{ route('manager.lesson.training.update', [$lesson->id, 4]) }}"
                              method="post">
                            @csrf
                            @if(isset($s_questions) && count($s_questions))
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($s_questions as $s_question)
                                    <div class="form-group row mb-2">
                                        <div class="col-lg-9">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control"
                                                   name="old_s_question[{{$s_question->id}}]" type="text"
                                                   value="{{$s_question->content}}">
                                            <input type="hidden" name="mark[{{$s_question->id}}]" value="6">
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex">
                                                <label>{{t('Attachment')}} :</label>
                                                @if($s_question->getFirstMediaUrl('t_imageQuestion'))
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="{{$s_question->id}}" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="{{$s_question->getFirstMediaUrl('t_imageQuestion')}}" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <input type="file" name="old_s_q_attachment[{{$s_question->id}}]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row" id="row-{{$s_question->id}}">
                                        @php
                                            $o_counter = 1;
                                        @endphp
                                        @foreach($s_question->sortWords as $sort_word)
                                            <div class="col-4 mb-3 option">
                                                <label>{{ t('Option')}}  {{$o_counter}} : <a href="#"
                                                                                data-id="{{$sort_word->id}}"
                                                                                data-bs-toggle="modal"
                                                                                data-target="#deleteSortWord"
                                                                                class="text-danger delete_old_input">{{ t('Delete')}} </a></label>
                                                <input required class="form-control"
                                                       name="old_s_q_option[{{$s_question->id}}][{{$sort_word->id}}]"
                                                       value="{{$sort_word->content}}" type="text">
                                            </div>

                                            @php
                                                $o_counter ++;
                                            @endphp
                                        @endforeach
                                        <div class="col-1 d-flex align-items-center mt-3">
                                            <button type="button" data-id="{{$s_question->id}}"
                                                    id="add_label_{{$s_question->id}}"
                                                    class="btn btn-danger btn-icon btn-block add_button"><i
                                                    class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="separator separator-dashed my-5"></div>
                                    </div>
                                    @php
                                        $i ++;
                                    @endphp
                                @endforeach
                            @else

                                @for($i=1;$i<=$lesson->grade->sort;$i++)
                                    <div class="form-group row mb-2">
                                        <div class="col-lg-9">
                                            <label class="text-dark fw-bold">{{ t('Q')}}  {{$i}}:</label>
                                            <input required class="form-control" name="s_question[{{$i}}]"
                                                   type="text">
                                            <input type="hidden" name="mark[{{$i}}]" value="6">


                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ t('Attachment')}}  :</label>
                                            <input type="file" name="s_q_attachment[{{$i}}]"
                                                   class="form-control">
                                        </div>

                                    </div>
                                    <div class="form-group row" id="row-{{$i}}">
                                        @foreach(range(1,3) as $item)
                                            <div class="col-lg-4 mt-3 option">
                                                <label>{{ t('Option').$item}}:</label>
                                                <input required class="form-control option" name="s_q_option[{{$i}}][{{$item}}]" type="text">
                                            </div>
                                        @endforeach
                                        <div class="col-1 d-flex align-items-end mt-3">
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
                        let parent = $(this).parent()
                        let id = $(this).data("id");
                        let url = '{{route('manager.lesson.remove_t_question_attachment', ':id')}}';
                        url = url.replace(':id', id);
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
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_t_sort_word', ':id')}}';
                        url = url.replace(':id', id);
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
                        let parent = $(this).parent()
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_t_match_image', ':id')}}';
                        url = url.replace(':id', id);
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
                                "<label>{{ t('Option')}}  " + y + " : <a href='#' class='text-danger delete_input'>{{ t('Delete')}} </a></label>\n" +
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
