@extends('supervisor.layout.container')
@section('title',$title)
@section('charts')
    <div class="row gy-5 g-xl-10 justify-content-center">
        <div class="col-sm-6 col-xl-2 mb-xl-10">
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-duotone ki-profile-user fs-2hx text-gray-600">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                            <i class="path4"></i>
                        </i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$teachers}} </span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">{{t('Teachers')}}</span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
        </div>
        <div class="col-sm-6 col-xl-2 mb-xl-10">
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-duotone ki-profile-user fs-2hx text-gray-600">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                            <i class="path4"></i>
                        </i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$students}} </span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">{{t('Students')}}</span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
        </div>

        <div class="col-sm-6 col-xl-2 mb-xl-10">
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-duotone ki-note-2 fs-2hx text-gray-600">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                            <i class="path4"></i>
                        </i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$tests}} </span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">{{t('Students Tests')}}</span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
        </div>
    </div>
@endsection
