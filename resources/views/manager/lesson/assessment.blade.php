{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('style')
    <style>
        .form-group label {
            font-size: 14px;
            font-weight: 400;
            color: #31ab3f;
        }
    </style>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.lesson.index') }}">{{$lesson->grade->grade_number}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($title) ? $title:'' }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">الدرس : {{$lesson->name}}
                            - {{$lesson->grade->name}} -
                        <a href="{{route('manager.lesson.review', [$lesson->id, 'test'])}}" target="_blank">معاينة</a>
                        </h3>
                    </div>
                </div>


                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                @if($lesson->grade->true_false)
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab"
                                       href="#kt_tabs_1_1">صح و خطأ</a>
                                </li>
                                @endif
                                @if($lesson->grade->choose)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab"
                                       href="#kt_tabs_1_2">اختر الإجابة</a>
                                </li>
                                @endif
                                @if($lesson->grade->match)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3">التوصيل</a>
                                </li>
                                @endif
                                @if($lesson->grade->sort)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab"
                                       href="#kt_tabs_1_4">ترتيب الكلمات</a>
                                </li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                @if($lesson->grade->true_false)
                                <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ route('manager.lesson.update_assessment', [$lesson->id, 1]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        @if(isset($t_f_questions) && count($t_f_questions))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($t_f_questions as $t_f_question)
                                                @php
                                                if ($lesson->grade->grade_number >= 7)
                                                {
                                                    $mark = 6;
                                                }elseif ($lesson->grade->grade_number >= 4){
                                                    $mark = 6;
                                                }else{
                                                    $mark = 6;
                                                }
                                                @endphp
                                                <div class="form-group row">
                                                    <div class="col-lg-7">
                                                        <label class="text-info">س {{$i}}:</label>
                                                        <input required class="form-control"
                                                               name="old_t_f_question[{{$t_f_question->id}}]" type="text"
                                                               value="{{$t_f_question->content}}">
                                                        <input type="hidden" name="mark[{{$t_f_question->id}}]" value="{{$mark}}"/>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>الإجابة الصحيحة:</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio">
                                                                <input required
                                                                       {{optional($t_f_question->trueFalse)->result == 1 ? 'checked':''}} checked
                                                                       type="radio" value="1"
                                                                       name="old_t_f[{{$t_f_question->id}}]"> صح
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required
                                                                       {{optional($t_f_question->trueFalse)->result == 0 ? 'checked':''}} type="radio"
                                                                       value="0" name="old_t_f[{{$t_f_question->id}}]">
                                                                خطأ
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>مرفق : @if($t_f_question->getFirstMediaUrl('imageQuestion')) <a
                                                                href="{{$t_f_question->getFirstMediaUrl('imageQuestion')}}"
                                                                class="kt-font-warning"
                                                                target="_blank">استعراض</a>  |
                                                            <a href="#deleteModel" data-id="{{$t_f_question->id}}"
                                                                    data-toggle="modal" data-target="#deleteModel"
                                                                    class="text-warning deleteRecord">(حذف)
                                                            </a>  @endif</label>
                                                        <input type="file"
                                                               name="old_t_f_q_attachment[{{$t_f_question->id}}]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                @php
                                                    $i ++;
                                                @endphp
                                            @endforeach
                                        @else
                                            @for($i = 1; $i<=$lesson->grade->true_false;$i++)
                                                @php
                                                    if ($lesson->grade->grade_number >= 7)
                                                    {
                                                        $mark = 6;
                                                    }elseif ($lesson->grade->grade_number >= 4){
                                                        $mark = 6;
                                                    }else{
                                                        $mark = 6;
                                                    }
                                                @endphp
                                                <div class="form-group row">
                                                    <div class="col-lg-7">
                                                        <label>س {{$i}}:</label>
                                                        <input required class="form-control" name="t_f_question[{{$i}}]"
                                                               type="text">
                                                        <input type="hidden" name="mark[{{$i}}]" value="{{$mark}}"/>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>الإجابة الصحيحة:</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio">
                                                                <input required checked type="radio" value="1"
                                                                       name="t_f[{{$i}}]"> صح
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required type="radio" value="0"
                                                                       name="t_f[{{$i}}]"> خطأ
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>مرفق :</label>
                                                        <input type="file" name="t_f_q_attachment[{{$i}}]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if($lesson->grade->choose)
                                <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ route('manager.lesson.update_assessment', [$lesson->id, 2]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        @if(isset($c_questions) && count($c_questions))
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach($c_questions as $c_question)
                                                    @php
                                                        if ($lesson->grade->grade_number >= 7)
                                                        {
                                                            $mark = 7;
                                                        }elseif ($lesson->grade->grade_number >= 4){
                                                            $mark = 6;
                                                        }else{
                                                            $mark = 7;
                                                        }
                                                    @endphp
                                                    <div class="form-group row">
                                                        <div class="col-lg-6">
                                                            <label class="text-info">س {{$i}}:</label>
                                                            <input required class="form-control"
                                                                   name="old_c_question[{{$c_question->id}}]" type="text"
                                                                   value="{{$c_question->content}}">
                                                            @if($i == 3 && $lesson->grade->grade_number <= 3)
                                                                <input type="hidden" name="mark[{{$c_question->id}}]" value="8"/>
                                                            @else
                                                                <input type="hidden" name="mark[{{$c_question->id}}]" value="{{$mark}}"/>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label>الإجابة الصحيحة:</label>
                                                            <div class="kt-radio-inline">
                                                                @php
                                                                    $o_counter = 1;
                                                                @endphp
                                                                @foreach($c_question->options as $option)
                                                                    <label class="kt-radio">
                                                                        <input required
                                                                               {{$option->result == 1 ? 'checked':''}} type="radio"
                                                                               value="{{$option->id}}"
                                                                               name="old_c_q_a[{{$c_question->id}}]"> {{$o_counter}}
                                                                        <span></span>
                                                                    </label>
                                                                    @php
                                                                        $o_counter ++;
                                                                    @endphp
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label>مرفق : @if($c_question->getFirstMediaUrl('imageQuestion')) <a
                                                                    href="{{$c_question->getFirstMediaUrl('imageQuestion')}}"
                                                                    class="kt-font-warning"
                                                                    target="_blank">استعراض</a>  |
                                                                <a href="#deleteModel" data-id="{{$c_question->id}}"
                                                                        data-toggle="modal" data-target="#deleteModel"
                                                                        class="text-warning deleteRecord">(حذف)
                                                                </a>  @endif</label>
                                                            <input type="file" name="old_c_q_attachment[{{$c_question->id}}]"
                                                                   class="form-control">
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
                                                @endforeach
                                            @else
                                                @for($i = 1; $i<=$lesson->grade->choose;$i++)
                                                    @php
                                                    if ($lesson->grade->grade_number >= 7)
                                                    {
                                                        $mark = 7;
                                                    }elseif ($lesson->grade->grade_number >= 4){
                                                        $mark = 6;
                                                    }else{
                                                        $mark = 7;
                                                    }
                                                @endphp
                                                <div class="form-group row">
                                                    <div class="col-lg-6">
                                                        <label>س {{$i}}:</label>
                                                        <input required class="form-control" name="c_question[{{$i}}]"
                                                               type="text">
                                                        @if($i == 3 && $lesson->grade->grade_number <= 3)
                                                        <input type="hidden" name="mark[{{$i}}]" value="8"/>
                                                        @else
                                                            <input type="hidden" name="mark[{{$i}}]" value="{{$mark}}"/>
                                                        @endif

                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>الإجابة الصحيحة:</label>
                                                        <div class="kt-radio-inline">
                                                            <label class="kt-radio">
                                                                <input required checked type="radio" value="1"
                                                                       name="c_q_a[{{$i}}]"> 1
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required type="radio" value="2"
                                                                       name="c_q_a[{{$i}}]"> 2
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required type="radio" value="3"
                                                                       name="c_q_a[{{$i}}]"> 3
                                                                <span></span>
                                                            </label>
                                                            <label class="kt-radio">
                                                                <input required type="radio" value="4"
                                                                       name="c_q_a[{{$i}}]"> 4
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>مرفق :</label>
                                                        <input type="file" name="c_q_attachment[{{$i}}]"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
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
                                                    <div class="col-lg-3">
                                                        <label>4 :</label>
                                                        <input required type="text" class="form-control"
                                                               name="c_q_option[{{$i}}][]">
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if($lesson->grade->match)
                                <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ route('manager.lesson.update_assessment', [$lesson->id, 3]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        @if(isset($m_questions) && count($m_questions))
                                            @php
                                                if ($lesson->grade->grade_number >= 7)
                                                {
                                                    $mark = 0;
                                                }elseif ($lesson->grade->grade_number >= 4){
                                                    $mark = 8;
                                                }else{
                                                    $mark = 8;
                                                }
                                            @endphp
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($m_questions as $m_question)
                                            <div class="form-group row">
                                                <div class="col-lg-9">
                                                    <label class="text-info">س {{$i}}:</label>
                                                    <input required class="form-control"
                                                           name="old_m_question[{{$m_question->id}}]" type="text"
                                                           value="{{$m_question->content}}">
                                                    <input type="hidden" name="mark[{{$m_question->id}}]" value="8">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>مرفق : @if($m_question->getFirstMediaUrl('imageQuestion')) <a
                                                            href="{{$m_question->getFirstMediaUrl('imageQuestion')}}" class="kt-font-warning"
                                                            target="_blank">استعراض</a>  |
                                                        <a href="#deleteModel" data-id="{{$m_question->id}}"
                                                                data-toggle="modal" data-target="#deleteModel"
                                                                class="text-warning deleteRecord">(حذف)
                                                        </a>  @endif</label>
                                                    <input type="file" name="old_m_q_attachment[{{$m_question->id}}]"
                                                           class="form-control">
                                                </div>
                                            </div>
                                                @php
                                                    $i ++;
                                                @endphp
                                            @php
                                                $o_counter = 1;
                                            @endphp
                                            @foreach($m_question->matches as $match)
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <label>خيار {{$o_counter}}:</label>
                                                        <input required class="form-control"
                                                               name="old_m_q_option[{{$match->id}}]"
                                                               value="{{$match->content}}" type="text">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>الإجابة:</label>
                                                        <input required class="form-control"
                                                               name="old_m_q_answer[{{$match->id}}]"
                                                               value="{{$match->result}}" type="text">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>صورة:
                                                            @if($match->getFirstMediaUrl('match'))
                                                                <a href="{{$match->getFirstMediaUrl('match')}}" class="kt-font-warning"
                                                                   target="_blank">استعراض</a>
                                                                |
                                                                <a href="#deleteMatchImageModel" data-id="{{$match->id}}"
                                                                        data-toggle="modal"
                                                                        data-target="#deleteMatchImageModel"
                                                                        class="text-warning deleteMatchImageRecord">
                                                                    (حذف)
                                                                </a>
                                                            @endif
                                                        </label>
                                                        <input class="form-control" name="old_m_q_image[{{$match->id}}]"
                                                               type="file">
                                                    </div>
                                                </div>
                                                @php
                                                    $o_counter ++;
                                                @endphp
                                            @endforeach
                                            @endforeach
                                        @else
                                            @for($i=0; $i<$lesson->grade->match; $i++)
                                                @php
                                                    if ($lesson->grade->grade_number >= 7)
                                                    {
                                                        $mark = 0;
                                                    }elseif ($lesson->grade->grade_number >= 4){
                                                        $mark = 8;
                                                    }else{
                                                        $mark = 8;
                                                    }
                                                @endphp
                                            <div class="form-group row">
                                                <div class="col-lg-9">
                                                    <label class="text-info">س {{$i + 1}}:</label>
                                                    <input required class="form-control" name="m_question[{{$i}}]"
                                                           type="text">
                                                    <input type="hidden" name="mark[{{$i}}]" value="{{$mark}}">

                                                </div>
                                                <div class="col-lg-3">
                                                    <label>مرفق :</label>
                                                    <input type="file" name="m_q_attachment[{{$i}}]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <label>خيار 1:</label>
                                                    <input required class="form-control" name="m_q_option[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>الإجابة:</label>
                                                    <input required class="form-control" name="m_q_answer[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>صورة:</label>
                                                    <input class="form-control" name="m_q_image[{{$i}}][]" type="file">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <label>خيار 2:</label>
                                                    <input required class="form-control" name="m_q_option[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>الإجابة:</label>
                                                    <input required class="form-control" name="m_q_answer[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>صورة:</label>
                                                    <input class="form-control" name="m_q_image[{{$i}}][]" type="file">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <label>خيار 3:</label>
                                                    <input required class="form-control" name="m_q_option[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>الإجابة:</label>
                                                    <input required class="form-control" name="m_q_answer[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>صورة:</label>
                                                    <input class="form-control" name="m_q_image[{{$i}}][]" type="file">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <label>خيار 4:</label>
                                                    <input required class="form-control" name="m_q_option[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>الإجابة:</label>
                                                    <input required class="form-control" name="m_q_answer[{{$i}}][]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>صورة:</label>
                                                    <input class="form-control" name="m_q_image[{{$i}}][]" type="file">
                                                </div>
                                            </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if($lesson->grade->sort)
                                <div class="tab-pane" id="kt_tabs_1_4" role="tabpanel">
                                    <form enctype="multipart/form-data" id="form_information"
                                          class="kt-form kt-form--label-right"
                                          action="{{ route('manager.lesson.update_assessment', [$lesson->id, 4]) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        @if(isset($s_questions) && count($s_questions))
                                            @php
                                                if ($lesson->grade->grade_number >= 7)
                                                {
                                                    $mark = 0;
                                                }elseif ($lesson->grade->grade_number >= 4){
                                                    $mark = 8;
                                                }else{
                                                    $mark = 6;
                                                }
                                            @endphp
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($s_questions as $s_question)
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <label class="text-info">س {{$i}}:</label>
                                                        <input required class="form-control"
                                                               name="old_s_question[{{$s_question->id}}]" type="text"
                                                               value="{{$s_question->content}}">
                                                        <input type="hidden" name="mark[{{$s_question->id}}]" value="{{$mark}}">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>مرفق : @if($s_question->getFirstMediaUrl('imageQuestion')) <a
                                                                href="{{$s_question->getFirstMediaUrl('imageQuestion')}}"
                                                                class="kt-font-warning"
                                                                target="_blank">استعراض</a>  |
                                                            <a href="#deleteModel" data-id="{{$s_question->id}}"
                                                                    data-toggle="modal" data-target="#deleteModel"
                                                                    class="text-warning deleteRecord">(حذف)
                                                            </a>  @endif</label>
                                                        <input type="file" name="old_s_q_attachment[{{$s_question->id}}]"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-lg-1 text-center">
                                                        <label>خيار جديد :</label>
                                                        <br/>
                                                        <button type="button" data-id="{{$s_question->id}}"
                                                                id="add_label_{{$s_question->id}}"
                                                                class="btn btn-danger btn-icon btn-block add_button"><i
                                                                class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group row" id="row-{{$s_question->id}}">
                                                    @php
                                                        $o_counter = 1;
                                                    @endphp
                                                    @foreach($s_question->sortWords as $sort_word)
                                                        <div class="col-lg-4 mt-3">
                                                            <label>خيار {{$o_counter}} : <a href="#"
                                                                                            data-id="{{$sort_word->id}}"
                                                                                            data-toggle="modal"
                                                                                            data-target="#deleteSortWord"
                                                                                            class="kt-font-warning delete_old_input">حذف</a></label>
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

                                            @for($i=1;$i<=$lesson->grade->sort;$i++)
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <label class="text-info">س {{$i}}:</label>
                                                        <input required class="form-control" name="s_question[{{$i}}]"
                                                               type="text">
                                                        <input type="hidden" name="mark[{{$i}}]" value="{{$mark}}">


                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>مرفق :</label>
                                                        <input type="file" name="s_q_attachment[{{$i}}]"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-lg-1 text-center">
                                                        <label>خيار جديد :</label>
                                                        <button type="button" data-id="{{$i}}" id="add_label_{{$i}}"
                                                                class="btn btn-danger btn-block add_button"><i
                                                                class="fa fa-plus"></i> إضافة
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group row" id="row-{{$i}}">
                                                    <div class="col-lg-4 mt-3">
                                                        <label>خيار 1:</label>
                                                        <input required class="form-control" name="s_q_option[{{$i}}][]"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-4 mt-3">
                                                        <label>خيار 2:</label>
                                                        <input required class="form-control" name="s_q_option[{{$i}}][]"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-4 mt-3">
                                                        <label>خيار 3:</label>
                                                        <input required class="form-control" name="s_q_option[{{$i}}][]"
                                                               type="text">
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
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
                        <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_attachment_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <h5>هل أنت متأكد من حذف السجل المحدد ؟</h5>
                            <br/>
                            <p>حذف السجل الحالي يؤدي لحذف السجلات المرتبطة به .</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-warning">حذف</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_sort_word_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <h5>هل أنت متأكد من حذف السجل المحدد ؟</h5>
                            <br/>
                            <p>حذف السجل الحالي يؤدي لحذف السجلات المرتبطة به .</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-warning">حذف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteMatchImageModel" tabindex="-1" role="dialog"
             aria-labelledby="deleteMatchImageModel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_match_attachment_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <h5>هل أنت متأكد من حذف السجل المحدد ؟</h5>
                            <br/>
                            <p>حذف السجل الحالي يؤدي لحذف السجلات المرتبطة به .</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-warning">حذف</button>
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
                        var url = '{{route('manager.lesson.remove_a_question_attachment', ':id')}}';
                        url = url.replace(':id', id);
                        $('#delete_attachment_form').attr('action', url);
                    }));
                    $(document).on('click', '.delete_old_input', (function () {
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_a_sort_word', ':id')}}';
                        url = url.replace(':id', id);
                        $('#delete_sort_word_form').attr('action', url);
                    }));
                    $(document).on('click', '.deleteMatchImageRecord', (function () {
                        var id = $(this).data("id");
                        var url = '{{route('manager.lesson.remove_a_match_image', ':id')}}';
                        url = url.replace(':id', id);
                        $('#delete_match_attachment_form').attr('action', url);
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
                                "<label>خيار " + y + " : <a href='#' class='kt-font-warning delete_input'>حذف</a></label>\n" +
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
                    //     ele.attr('disabled', صح)
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
                    //                 //btn.attr('disabled', خطأ)
                    //                 toastr.success(data.message);
                    //             } else {
                    //                 toastr.error(data.message);
                    //             }
                    //         },
                    //         error: function(errMsg) {
                    //             toastr.error(errMsg.responseJSON.message);
                    //             btn.attr('disabled', خطأ)
                    //         }
                    //     });
                    // });
                });
            </script>
@endsection
