{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}


<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester');
    .cursive {
        font-family: 'Pinyon Script', cursive;
    }
    .sans {
        font-family: 'Open Sans', sans-serif;
    }
    .bold {
        font-weight: bold;
    }
    .block {
        display: block;
    }
    .underline {
        border-bottom: 1px solid #777;
        padding: 5px;
        margin-bottom: 15px;
    }
    .margin-0 {
        margin: 0;
    }
    .padding-0 {
        padding: 0;
    }
    .pm-empty-space {
        height: 40px;
        width: 100%;
    }

    .pm-certificate-container {
        position: relative;
        width: 800px;
        height: 600px;
        background-color: #618597 !important;
        padding: 30px;
        color: #333;
        font-family: 'Open Sans', sans-serif;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        /*background: -webkit-repeating-linear-gradient(
          45deg,
          #618597,
          #618597 1px,
          #b2cad6 1px,
          #b2cad6 2px
        );
        background: repeating-linear-gradient(
          90deg,
          #618597,
          #618597 1px,
          #b2cad6 1px,
          #b2cad6 2px
        );*/

    }
    .pm-certificate-container .outer-border {
        width: 794px;
        height: 594px;
        position: absolute;
        left: 50%;
        margin-left: -397px;
        top: 50%;
        margin-top: -297px;
        border: 2px solid #fff;
    }
    .pm-certificate-container .inner-border {
        width: 730px;
        height: 530px;
        position: absolute;
        left: 50%;
        margin-left: -365px;
        top: 50%;
        margin-top: -265px;
        border: 2px solid #fff;
    }
    .pm-certificate-container .pm-certificate-border {
        position: relative;
        width: 720px;
        height: 520px;
        padding: 0;
        border: 1px solid #E1E5F0;
        background-color: #ffffff;
        background-image: none;
        left: 50%;
        margin-left: -360px;
        top: 50%;
        margin-top: -260px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-block {
        width: 650px;
        height: 200px;
        position: relative;
        left: 50%;
        margin-left: -325px;
        top: 70px;
        margin-top: 0;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-header {
        margin-bottom: 10px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-title {
        position: relative;
        top: 40px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-title h2 {
        font-size: 54px !important;
        color: #c8ae17 !important;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-body {
        padding: 20px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-body .pm-name-text {
        font-size: 16px;
    }
    .pm-certificate-container .pm-certificate-border .pm-earned {
        margin: 15px 0 20px;
    }
    .pm-certificate-container .pm-certificate-border .pm-earned .pm-earned-text {
        font-size: 20px;
    }
    .pm-certificate-container .pm-certificate-border .pm-earned .pm-credits-text {
        font-size: 15px;
    }
    .pm-certificate-container .pm-certificate-border .pm-course-title .pm-earned-text {
        font-size: 20px;
    }
    .pm-certificate-container .pm-certificate-border .pm-course-title .pm-credits-text {
        font-size: 15px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certified {
        font-size: 12px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certified .underline {
        margin-bottom: 5px;
    }
    .pm-certificate-container .pm-certificate-border .pm-certificate-footer {
        width: 650px;
        height: 100px;
        position: relative;
        left: 50%;
        margin-left: -325px;
        bottom: -70px;
    }
    .col-md-4 img{
        width: 100% !important;
    }

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>


<section class="inner-page">
    <section>
        <div class="container pm-certificate-container" id="invocieContainer" dir="ltr">
            <div class="outer-border"></div>
            <div class="inner-border"></div>

            <div class="pm-certificate-border col-md-12">
                <div class="row pm-certificate-header">
                    <div class="pm-certificate-title cursive col-md-12 text-center ">
                        <h2 class="bold">Certificate of achievement</h2>
                    </div>
                </div>

                <div class="row pm-certificate-body">

                    <div class="pm-certificate-block">
                        <div class="col-md-12 text-center pm-credits-text block bold cursive mb-4" dir="ltr">
                            <h3 class="bold">This Certificate Awarded to : <span style="color: #FF0000">{{$student_test->user->name}}.</span></h3>
                        </div>

                        <div class="col-md-12 pm-earned text-center pm-credits-text block bold sans" dir="ltr">
                            In appreciation of passing the assessment of lesson
                            <span style="color: #0246f1">{{$student_test->lesson->translate('en')->name}} - {{$student_test->lesson->translate('ar')->name}}</span>
                            <br />
                            in the level {{$student_test->lesson->level->translate('en')->name}}
                            <br />
                            with the percentage of {{$student_test->total_per}}
                            <br />
                            During the journey of learning Arabic from the Non-Arabs platform.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row pm-certificate-footer">
                            <div class="col-md-4 pm-certified col-md-4 text-center">
                                <span class="pm-credits-text block sans mt-3">Date</span>
                                <span class="bold block pm-name-text">{{$student_test->created_at->format('Y-m-d')}}</span>
                            </div>
                            <div class="col-md-4">
                                <!-- LEAVE EMPTY -->
                            </div>
                            <div class="col-md-4 pm-certified col-md-4 text-center">
                                <img src="{{asset('website/images/main_logo.png')}}" width="100%">
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <br />
        <button class="btn btn-success" id="btnPrint">Print certificate - طباعة الشهادة</button>
    </section>
</section>

