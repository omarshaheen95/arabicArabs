@extends('layouts.container')
@section('style')
        <link href="{{asset('website/css/price-ar.css')}}" rel="stylesheet">
@endsection
@section('content')
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-8">
                            <div class="slider-item animate__animated animate__fadeInRight">
                                <div class="slider-item-left">

                                    <h1 class="theme-text-default"
                                         style="direction: rtl;" >
                                       منصة لغتي الأولى
                                    </h1>
{{--                                    <p>--}}
{{--                                        {{w('Start your lesson with ease and fun, train and practice the Arabic language fluently, test your abilities, get a certificate of achievement, play your favourite game.')}}--}}
{{--                                    </p>--}}
                                    <p>منصة مهنية إثرائية لتطوير مهارات اللغة العربية كلغة أولى للطلاب العرب تشمل جميع المهارات.</p>
                                    <p>تحتوي المنصة على تمارين تفاعلية متنوعة ذات جودة عالية تهدف إلى تطوير مهارات اللغة.

                                        تشمل المنصة على 600 نصًا معلوماتيًا وأدبيًا لتطوير وقياس الفهم والاستيعاب.

                                        كما تشمل المنصة على 600 نصًا للاستماع متبوعةً بأنشطة وتمارين إثرائية.
                                        <br>
                                        ينتهي الدرس باختبار قصير من عشر دقائق لقياس مدى فهم الطالب ويستطيع الطالب الحصول على شهادة تقدير مهنية بعد اجتياز الاختبار

                                        كما تحتوي المنصة على مجموعة متنوعة من أسئلة التحدث والكتابة يتم تصحيحها من خلال المعلم.

                                        بالإضافة إلى مكتبة رقمية تشمل 150 قصة تفاعلية مقسمة إلى 12 مستوى مختلف.
                                    </p>


                                    <div class="slider-btn">
                                        <a href="/register" class="theme-btn theme-btn-default js-scroll-trigger">
                                            ابدأ الأن
                                            <i class="flaticon-double-right-arrows-angles"></i>
                                        </a>
                                    </div>
                                    <div class="slider-btn play-btn">
                                        <button type="button" data-toggle="modal" data-target="#video" data-backdrop="static" data-keyboard="false" style="font-weight: bold;">
                                            <i class="mdi mdi-play-speed icon-bg" style="color: #2fb50d;"></i>
                                            ماذا نقدم
                                        </button>
                                    </div>
{{--                                    <div class="slider-btn">--}}
{{--                                        <a class="theme-btn theme-btn-default" href="#video" data-toggle="modal" data-target="#video" data-backdrop="static" data-keyboard="false">--}}
{{--                                            {{w('What we offer')}}--}}
{{--                                            <i class="mdi mdi-play-speed"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                    <div class="slider-btn play-btn">--}}
{{--                                        <button type="button" data-toggle="modal" data-target="#video" data-backdrop="static" data-keyboard="false">--}}
{{--                                            <i class="mdi mdi-play-speed icon-bg"></i>--}}
{{--                                            {{w('What we offer')}}--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="slider-item animate__animated animate__fadeInLeft">
                                <div class="slider-item-right">
                                    <img src="{{asset('website/images/image.png')}}" alt="">
                                </div>
                                <h3 class="theme-text-default count text-center" style="font-style:normal; font-weight:bold">إجمالي الطلاب :
                                    <span style="font-size:25px; border: 3px solid #FDB416; padding: 8px; border-radius: 10px" class="count-num" data-speed="6000" data-stop="{{$users_count}}" data-in-viewport="yes">0</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SLIDER -->

    <!-- START VIDEO -->
    <div id="video" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
                <div class="modal-body">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/_K3C8YWTE9w" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- END VIDEO -->
    <!-- END ABOUT -->
    @if(count($packages))
    <div id="price_table">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!--PRICE HEADING START-->
                        <div class="price-heading clearfix section-header">
                            <h1 class="section-title">التكلفة</h1>
                        </div>
                        <!--//PRICE HEADING END-->
                    </div>
                </div>
            </div>
            <div class="container-fluid">

                <!--BLOCK ROW START-->
                <div class="row justify-content-center">
                    <div class="col-md-1">
                    </div>
                    @foreach($packages as $package)
                    <div class="col-md-2">

                        <!--PRICE CONTENT START-->
                        <div class="generic_content active clearfix">

                            <!--HEAD PRICE DETAIL START-->
                            <div class="generic_head_price clearfix">

                                <!--HEAD CONTENT START-->
                                <div class="generic_head_content clearfix">

                                    <!--HEAD START-->
                                    <div class="head_bg"></div>
                                    <div class="head">
                                        <span>{{$package->name}}</span>
                                    </div>
                                    <!--//HEAD END-->

                                </div>
                                <!--//HEAD CONTENT END-->

                                <!--PRICE START-->
                                <div class="generic_price_tag clearfix">
                                <span class="price">

                                        <span class="currency" dir="ltr">{{$package->price}} <span class="sign">$</span></span>
                                </span>
                                </div>
                                <!--//PRICE END-->

                            </div>
                            <!--//HEAD PRICE DETAIL END-->

                            <!--FEATURE LIST START-->
                            <div class="generic_feature_list">
                                <ul>
                                    <li><span>{{$package->days}}</span> @if($package->days <= 10) أيام @else يوم @endif</li>
                                </ul>
                            </div>
                            <!--//FEATURE LIST END-->

                            <!--BUTTON START-->
                            <div class="generic_price_btn clearfix">
                                <a class="" href="/register?package_id={{$package->id}}">إشتراك</a>
                            </div>
                            <!--//BUTTON END-->

                        </div>
                        <!--//PRICE CONTENT END-->

                    </div>
                    @endforeach
                    <div class="col-md-1">
                    </div>
                </div>
                <!--//BLOCK ROW END-->

            </div>
        </section>
    </div>
    @endif
    <!-- START CONTACT -->
    <div id="contact" class="theme-bg-secondary-light aos-item" data-aos="fade-in">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title">اتصل بنا</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row d-flex align-items-center">

                        <div class="col-lg-12">
                            <div class="contact-item">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row text-center font-weight-bold">
                                            <div class="col-lg-4">
                                                <label>الموبايل</label><br />
                                                00971503842666
                                            </div>
                                            <div class="col-lg-4">
                                                <label>البريد الإلكتروني</label><br />
                                                Support@abt-assessments.com
                                            </div>
                                            <div class="col-lg-4">
                                                <label>البريد الإلكتروني</label><br />
                                                Support@Non-Arabs.com
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTACT -->

@endsection

@section('script')
    <script type="text/javascript">
        factCounter();
        function factCounter() {
            console.log(true);
            if($('.slider-item').length){
                $('.slider-item .count').each(function() {

                    var $t = $(this),
                        n = $t.find(".count-num").attr("data-stop"),
                        r = parseInt($t.find(".count-num").attr("data-speed"), 10);

                    if (!$t.hasClass("counted")) {
                        $t.addClass("counted");
                        $({
                            countNum: $t.find(".count-text").text()
                        }).animate({
                            countNum: n
                        }, {
                            duration: r,
                            easing: "linear",
                            step: function() {
                                $t.find(".count-num").text(Math.floor(this.countNum));
                            },
                            complete: function() {
                                $t.find(".count-num").text(this.countNum);
                            }
                        });
                    }

                    //set skill building height


                    var size = $(this).children('.progress-bar').attr('aria-valuenow');
                    $(this).children('.progress-bar').css('width', size+'%');


                });
            }
        }

    </script>
@endsection
