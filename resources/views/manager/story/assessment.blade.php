{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/summernote/dist/summernote.rtl.css') }}" rel="stylesheet"/>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.story.index') }}">{{ t('Stories') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Story Assessment') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ t('Story Assessment') }} : {{$story->name}}
                            - {{$story->grade}}</h3>
                    </div>
                </div>


                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
    @if($true_false_count)
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab"
                                       href="#kt_tabs_1_1">{{t('True False')}}</a>
                                </li>
    @endif
                                @if($chose_count)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab"
                                       href="#kt_tabs_1_2">{{t('Choose Answer')}}</a>
                                </li>
    @endif
                                @if($match_count)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3">{{t('Match')}}</a>
                                </li>
    @endif
                                @if($sort_count)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab"
                                       href="#kt_tabs_1_4">{{t('Sort Words')}}</a>
                                </li>
    @endif
                            </ul>
                            <div class="tab-content">
                                @if($true_false_count)
                                <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ count($t_f_questions) > 0 ? route('manager.story.updateAssessment', [$story->id, 1]):route('manager.story.storeAssessment', [$story->id, 1]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        <h4>True Or False</h4>
                                        @if(count($t_f_questions))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($t_f_questions as $t_f_question)
                                                <div class="form-group row">
                                                    <div class="col-lg-7">
                                                        <label class="text-info">Q {{$i}}:</label>
                                                        <input required class="form-control"
                                                               name="t_f_question[{{$t_f_question->id}}]" type="text"
                                                               value="{{$t_f_question->content}}">
                                                    </div>
                                                    <div class="col-lg-2 text-success">
                                                        <label>Correct Answer:</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio">
                                                                <input required
                                                                       {{optional($t_f_question->trueFalse)->result == 1 ? 'checked':''}} checked
                                                                       type="radio" value="1"
                                                                       name="t_f[{{$t_f_question->id}}]"> True
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required
                                                                       {{optional($t_f_question->trueFalse)->result == 0 ? 'checked':''}} type="radio"
                                                                       value="0" name="t_f[{{$t_f_question->id}}]">
                                                                False
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Attachment : @if($t_f_question->attachment) <a
                                                                href="{{$t_f_question->attachment}}"
                                                                class="kt-font-warning"
                                                                target="_blank">{{t('Browse')}}</a>  |
                                                            <button type="button" data-id="{{$t_f_question->id}}"
                                                                    data-toggle="modal" data-target="#deleteModel"
                                                                    class="btn btn-sm btn-warning deleteRecord">(Delete)
                                                            </button>  @endif</label>
                                                        <input type="file"
                                                               name="t_f_q_attachment[{{$t_f_question->id}}]"
                                                               class="form-control">
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
                                                        <label class="text-info">Q {{$i}}:</label>
                                                        <input required class="form-control" name="t_f_question[{{$i}}]"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-2 text-success">
                                                        <label>Correct Answer:</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio">
                                                                <input required checked type="radio" value="1"
                                                                       name="t_f[{{$i}}]"> True
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required type="radio" value="0"
                                                                       name="t_f[{{$i}}]"> False
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Attachment :</label>
                                                        <input type="file" name="t_f_q_attachment[{{$i}}]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if($chose_count)
                                <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ count($c_questions) > 0 ? route('manager.story.updateAssessment', [$story->id, 2]):route('manager.story.storeAssessment', [$story->id, 2]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        <h4>Choose Answer</h4>
                                        @if(count($c_questions))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($c_questions as $c_question)
                                                <div class="form-group row">
                                                    <div class="col-lg-6">
                                                        <label class="text-info">Q {{$i}}:</label>
                                                        <input required class="form-control"
                                                               name="c_question[{{$c_question->id}}]" type="text"
                                                               value="{{$c_question->content}}">
                                                    </div>
                                                    <div class="col-lg-3 text-success">
                                                        <label>Correct Answer:</label>
                                                        <div class="kt-radio-inline">
                                                            @php
                                                                $o_counter = 1;
                                                            @endphp
                                                            @foreach($c_question->options as $option)
                                                                <label class="kt-radio @if(!$loop->first) ml-5 @endif">
                                                                    <input required
                                                                           {{$option->result == 1 ? 'checked':''}} type="radio"
                                                                           value="{{$option->id}}"
                                                                           name="c_q_a[{{$c_question->id}}]"> {{$o_counter}}
                                                                    <span></span>
                                                                </label>
                                                                @php
                                                                    $o_counter ++;
                                                                @endphp
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Attachment : @if($c_question->attachment) <a
                                                                href="{{$c_question->attachment}}"
                                                                class="kt-font-warning"
                                                                target="_blank">{{t('Browse')}}</a>  |
                                                            <button type="button" data-id="{{$c_question->id}}"
                                                                    data-toggle="modal" data-target="#deleteModel"
                                                                    class="btn btn-sm btn-warning deleteRecord">(Delete)
                                                            </button>  @endif</label>
                                                        <input type="file" name="c_q_attachment[{{$c_question->id}}]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row text-success">
                                                    @php
                                                        $o_counter = 1;
                                                    @endphp
                                                    @foreach($c_question->options as $option)
                                                        <div class="col-lg-3">
                                                            <label>{{$o_counter}} :</label>
                                                            <input required type="text" class="form-control"
                                                                   name="c_q_option[{{$c_question->id}}][{{$option->id}}]"
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
                                            @endforeach
                                        @else
                                            @for($i = 1; $i<=$chose_count;$i++)
                                                <div class="form-group row">
                                                    <div class="col-lg-6">
                                                        <label class="text-info">Q {{$i}}:</label>
                                                        <input required class="form-control" name="c_question[{{$i}}]"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-3 text-success">
                                                        <label>Correct Answer:</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio">
                                                                <input required checked type="radio" value="1"
                                                                       name="c_q_a[{{$i}}]"> 1
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio ml-5">
                                                                <input required type="radio" value="2"
                                                                       name="c_q_a[{{$i}}]"> 2
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio ml-5">
                                                                <input required type="radio" value="3"
                                                                       name="c_q_a[{{$i}}]"> 3
                                                                <span></span>
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Attachment :</label>
                                                        <input type="file" name="c_q_attachment[{{$i}}]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row text-success">
                                                    <div class="col-lg-3">
                                                        <label>1 :</label>
                                                        <input required type="text" class="form-control"
                                                               name="c_q_option[{{$i}}][]">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>2 :</label>
                                                        <input required type="text" class="form-control"
                                                               name="c_q_option[{{$i}}][]">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>3 :</label>
                                                        <input required type="text" class="form-control"
                                                               name="c_q_option[{{$i}}][]">
                                                    </div>

                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if($match_count)
                                <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ count($m_questions) ? route('manager.story.updateAssessment', [$story->id, 3]):route('manager.story.storeAssessment', [$story->id, 3]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        <h4>Match Answer</h4>
                                        @if(count($m_questions))
                                            @php
                                                $keyCount = 1;
                                            @endphp
                                        @foreach($m_questions as $m_question)
                                            <div class="form-group row">
                                                <div class="col-lg-9">
                                                    <label class="text-info">Q {{$keyCount}}:</label>
                                                    <input required class="form-control"
                                                           name="m_question[{{$m_question->id}}]" type="text"
                                                           value="{{$m_question->content}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Attachment : @if($m_question->attachment) <a
                                                            href="{{$m_question->attachment}}" class="kt-font-warning"
                                                            target="_blank">{{t('Browse')}}</a>  |
                                                        <button type="button" data-id="{{$m_question->id}}"
                                                                data-toggle="modal" data-target="#deleteModel"
                                                                class="btn btn-sm btn-warning deleteRecord">(Delete)
                                                        </button>  @endif</label>
                                                    <input type="file" name="m_q_attachment[{{$m_question->id}}]"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            @php
                                                $o_counter = 1;
                                            @endphp
                                            @foreach($m_question->matches as $match)
                                                <div class="form-group row text-success">
                                                    <div class="col-lg-8">
                                                        <label>Option {{$o_counter}}:</label>
                                                        <input required class="form-control"
                                                               name="m_q_option[{{$match->id}}]"
                                                               value="{{$match->content}}" type="text">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>Answer:</label>
                                                        <input required class="form-control"
                                                               name="m_q_answer[{{$match->id}}]"
                                                               value="{{$match->result}}" type="text">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>Image:
                                                            @if($match->image)
                                                                <a href="{{$match->image}}" class="kt-font-warning" target="_blank">{{t('Browse')}}</a>
                                                                |
                                                                <button type="button" data-id="{{$match->id}}" data-toggle="modal" data-target="#deleteMatchImageModel" class="btn btn-sm btn-warning deleteMatchImageRecord">(Delete)</button>
                                                            @endif
                                                        </label>
                                                        <input class="form-control" name="m_q_image[{{$match->id}}]" type="file">
                                                    </div>
                                                </div>
                                                @php
                                                    $o_counter ++;
                                                @endphp
                                            @endforeach
                                                @php
                                                    $keyCount ++;
                                                @endphp
                                            @endforeach
                                        @else
                                            @for($i=1;$i<=$match_count;$i++)
                                            <div class="form-group row">
                                                <div class="col-lg-9">
                                                    <label class="text-info">Q {{$i}}:</label>
                                                    <input required class="form-control" name="m_question[{{$i}}]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Attachment :</label>
                                                    <input type="file" name="m_q_attachment[{{$i}}]" class="form-control">
                                                </div>
                                            </div>
                                            @for($y=1;$y<=$match_option;$y++)
                                            <div class="form-group row text-success">
                                                <div class="col-lg-8">
                                                    <label>Option {{$y}}:</label>
                                                    <input required class="form-control" name="m_q_option[{{$i}}][{{$y}}]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>Answer:</label>
                                                    <input required class="form-control" name="m_q_answer[{{$i}}][{{$y}}]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>Image:</label>
                                                    <input class="form-control" name="m_q_image[{{$i}}][{{$y}}]" type="file">
                                                </div>
                                            </div>

                                            @endfor
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                               @endif
                                @if($sort_count)
                                <div class="tab-pane" id="kt_tabs_1_4" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ count($s_questions) > 0 ? route('manager.story.updateAssessment', [$story->id, 4]):route('manager.story.storeAssessment', [$story->id, 4]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        <h4>Sort Answer</h4>
                                        @if(count($s_questions))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($s_questions as $s_question)
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <label class="text-info">Q {{$i}}:</label>
                                                        <input required class="form-control"
                                                               name="s_question[{{$s_question->id}}]" type="text"
                                                               value="{{$s_question->content}}">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Attachment : @if($s_question->attachment) <a
                                                                href="{{$s_question->attachment}}"
                                                                class="kt-font-warning"
                                                                target="_blank">{{t('Browse')}}</a>  |
                                                            <button type="button" data-id="{{$s_question->id}}"
                                                                    data-toggle="modal" data-target="#deleteModel"
                                                                    class="btn btn-sm btn-warning deleteRecord">(Delete)
                                                            </button>  @endif</label>
                                                        <input type="file" name="s_q_attachment[{{$s_question->id}}]"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-lg-1 text-center">
                                                        <label>{{t('New Label')}} :</label>
                                                        <button type="button" data-id="{{$s_question->id}}"
                                                                id="add_label_{{$s_question->id}}"
                                                                class="btn btn-danger btn-block add_button"><i
                                                                class="fa fa-plus"></i> {{t('Add')}}</button>
                                                    </div>
                                                </div>
                                                <div class="form-group row text-success" id="row-{{$s_question->id}}">
                                                    @php
                                                        $o_counter = 1;
                                                    @endphp
                                                    @foreach($s_question->sort_words as $sort_word)
                                                        <div class="col-lg-4 mt-3">
                                                            <label>Option {{$o_counter}} : <a href="#"
                                                                                              data-id="{{$sort_word->id}}"
                                                                                              data-toggle="modal"
                                                                                              data-target="#deleteSortWord"
                                                                                              class="kt-font-warning delete_old_input">Delete</a></label>
                                                            <input required class="form-control"
                                                                   name="old_s_q_option[{{$s_question->id}}][{{$sort_word->id}}]"
                                                                   value="{{$sort_word->content}}" type="text">
                                                        </div>
                                                        @php
                                                            $o_counter ++;
                                                        @endphp
                                                    @endforeach

                                                </div>
                                                @php
                                                    $i ++;
                                                @endphp
                                            @endforeach
                                        @else

                                            @for($i=1;$i<=$sort_count;$i++)
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <label class="text-info">Q {{$i}}:</label>
                                                        <input required class="form-control" name="s_question[{{$i}}]"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Attachment :</label>
                                                        <input type="file" name="s_q_attachment[{{$i}}]"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-lg-1 text-center">
                                                        <label>{{t('New Label')}} :</label>
                                                        <button type="button" data-id="{{$i}}" id="add_label_{{$i}}"
                                                                class="btn btn-danger btn-block add_button"><i
                                                                class="fa fa-plus"></i> {{t('Add')}}</button>
                                                    </div>
                                                </div>
                                                <div class="form-group row text-success" id="row-{{$i}}">
                                                    <div class="col-lg-4 mt-3">
                                                        <label>Option 1:</label>
                                                        <input required class="form-control" name="s_q_option[{{$i}}][]"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-4 mt-3">
                                                        <label>Option 2:</label>
                                                        <input required class="form-control" name="s_q_option[{{$i}}][]"
                                                               type="text">
                                                    </div>
{{--                                                    <div class="col-lg-4 mt-3">--}}
{{--                                                        <label>Option 3:</label>--}}
{{--                                                        <input required class="form-control" name="s_q_option[{{$i}}][]"--}}
{{--                                                               type="text">--}}
{{--                                                    </div>--}}
                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                               @endif
                            </div>
                        </div>
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
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.deleteRecord', (function () {
                        var id = $(this).data("id");
                        var url = '{{ route("manager.story.remove_attachment", ":id") }}';
                        url = url.replace(':id', id);
                        $('#delete_attachment_form').attr('action', url);
                    }));
                    $(document).on('click', '.delete_old_input', (function () {
                        var id = $(this).data("id");
                        var url = '{{ route("manager.story.remove_sort_word", ":id") }}';
                        url = url.replace(':id', id);
                        $('#delete_sort_word_form').attr('action', url);
                    }));
                    $(document).on('click','.deleteMatchImageRecord',(function(){
                        var id = $(this).data("id");
                        var url = '{{ route("manager.story.remove_match_attachment", ":id") }}';
                        url = url.replace(':id', id );
                        $('#delete_match_attachment_form').attr('action',url);
                    }));
                    var x = 1; //Initial field counter is 1
                    var y = 1; //Initial field counter is 1

                    var maxField = 10 //Input fields increment limitation
                    var addButton = $('.add_button'); //Add button selector
                    //Once add button is clicked
                    $(addButton).click(function () {
                        var row_id = $(this).attr('data-id');
                        var wrapper_row = $('#row-' + row_id); //Input field wrapper
                        x = $('#row-' + row_id).children().length;
                        y = $('#row-' + row_id).children().length;
                        //Check maximum number of input fields
                        if (x < maxField) {
                            x++; //Increment field counter
                            y++; //Increment field counter.
                            $(wrapper_row).append(
                                "<div class=\"col-lg-4 mt-3\">\n" +
                                "<label>Option " + y + " : <a href='#' class='kt-font-warning delete_input'>Delete</a></label>\n" +
                                "<input required class=\"form-control\" name=\"s_q_option[" + row_id + "][]\" type=\"text\">\n"
                            ); //Add field html
                        }
                    });
                    //Once remove button is clicked
                    $(document).on('click', '.delete_input', function (e) {
                        e.preventDefault();
                        $(this).parent().parent('div').remove(); //Remove field html
                        // x--;
                    });
                    // Once remove button is clicked
                    // $(wrapper).on('click', '.removed_button', function (e) {
                    //     e.preventDefault();
                    //     let ele = $(this);
                    //     let csrf = $('meta[name="csrf-token"]').attr('content');
                    //     var id = $(this).attr('data-id');
                    //     var url = '';
                    //     url = url.replace(':id', id );
                    //     ele.attr('disabled', true)
                    //     $.ajax({
                    //         url : url,
                    //         type: 'POST',
                    //         data : {
                    //             '_token': csrf,
                    //             '_method': 'delete',
                    //         },
                    //         success: function (data) {
                    //             if (data.success) {
                    //                 ele.parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                    //                 x--; //Decrement field counter
                    //                 //btn.attr('disabled', false)
                    //                 toastr.success(data.message);
                    //             } else {
                    //                 toastr.error(data.message);
                    //             }
                    //         },
                    //         error: function(errMsg) {
                    //             toastr.error(errMsg.responseJSON.message);
                    //             btn.attr('disabled', false)
                    //         }
                    //     });
                    // });
                });
            </script>
@endsection
