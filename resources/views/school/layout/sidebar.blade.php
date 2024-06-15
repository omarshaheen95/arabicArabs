<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{route('school.home')}}">
            <img alt="Logo" src="{{!settingCache('logo')? asset('logo.svg'):asset(settingCache('logo'))}}"
                 class="h-45px app-sidebar-logo-default"/>
            <img alt="Logo" src="{{!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))}}"
                 class="h-30px app-sidebar-logo-minimize "/>
        </a>

        <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
             data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
             data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                 data-kt-menu="true" data-kt-menu-expand="false">


                <div class="menu-item">
                    <a class="menu-link @if(request()->url()==route('school.home') ) active @endif"
                       href="{{ route('school.home') }}">
                        <span class="menu-icon">
                      <i class="ki-duotone ki-chart-pie-simple fs-2">
                         <i class="path1"></i>
                         <i class="path2"></i>
                        </i>
                        </span>
                        <span class="menu-title">{{t('Home')}}</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link @if(request()->url() == route('school.supervisor.index'))active @endif"
                       href="{{ route('school.supervisor.index') }}">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-people fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                     <span class="path4"></span>
                                     <span class="path5"></span>
                                   </i></span>
                        <span class="menu-title">{{t('Supervisors')}}</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link @if(request()->url() == route('school.teacher.index'))active @endif"
                       href="{{ route('school.teacher.index') }}">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                </span>
                        <span class="menu-title">{{t('Teachers')}}</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link @if(request()->url() == route('school.teacher.statistics'))active @endif"
                       href="{{ route('school.teacher.statistics') }}">
                                 <span class="menu-icon">
                                   <i class="ki-duotone ki-graph-2 fs-2">
                                     <i class="path1"></i>
                                     <i class="path2"></i>
                                     <i class="path3"></i>
                                    </i>
                                </span>
                        <span class="menu-title">{{t('Teachers statistics')}}</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link @if(request()->url() == route('school.student.index'))active @endif"
                       href="{{ route('school.student.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-people fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                             <i class="path3"></i>
                             <i class="path4"></i>
                             <i class="path5"></i>
                            </i>
                        </span>
                        <span class="menu-title">{{t('Students')}}</span>
                    </a>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{Request::is('school/lessons_tests')|| Request::is('school/stories_tests') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-tablet-book fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            </span>
											<span class="menu-title">{{t('Student Tests')}}</span>
											<span class="menu-arrow"></span>
										</span>
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link @if(Request::is('school/lessons_tests*') )active @endif"
                               href="{{ route('school.lessons_tests.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Lessons Tests')}}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link @if(Request::is('school/stories_tests*') )active @endif"
                               href="{{ route('school.stories_tests.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Stories Tests')}}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{Request::is('school/lessons_assignments')|| Request::is('school/stories_assignments') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-some-files fs-2">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                    </i>
                                            </span>
											<span class="menu-title">{{t('Student Assignments')}}</span>
											<span class="menu-arrow"></span>
										</span>
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link @if(Request::is('school/lessons_assignments*') )active @endif"
                               href="{{ route('school.lessons_assignments.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Lessons Assignments')}}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link @if(Request::is('school/stories_assignments*') )active @endif"
                               href="{{ route('school.stories_assignments.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Stories Assignments')}}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{Request::is('school/students_works')|| Request::is('school/stories_records') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                    </i>
                                            </span>
											<span class="menu-title">{{t('Marking')}}</span>
											<span class="menu-arrow"></span>
										</span>
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{route('school.students_works.index')}}"
                               class="menu-link {{Request::is('school/students_works*')?'active':''}}">
                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Students Works')}}</span>
                            </a>
                        </div>


                        <div class="menu-item">
                            <a href="{{route('school.stories_records.index')}}"
                               class="menu-link {{Request::is('school/stories_records*')?'active':''}}">
                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Stories Recodes')}}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{Request::is('school/hidden_lesson')|| Request::is('school/hidden_story') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-book-open fs-2">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                        <i class="path3"></i>
                                        <i class="path4"></i>
                                    </i>
                                            </span>
											<span class="menu-title">{{t('Hiding control')}}</span>
											<span class="menu-arrow"></span>
										</span>
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{route('school.lessons.index')}}"
                               class="menu-link {{Request::is('school/lessons*')?'active':''}}">
                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Hidden Lessons')}}</span>
                            </a>
                        </div>


                        <div class="menu-item">
                            <a href="{{route('school.stories.index')}}"
                               class="menu-link {{Request::is('school/stories*')?'active':''}}">
                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                <span class="menu-title">{{t('Hidden Stories')}}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>

                <div class="menu-item">
                    <a href="{{route('school.report.pre_usage_report')}}"
                       class="menu-link {{request()->url() == route('school.report.pre_usage_report')?'active':''}}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-graph-2 fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{t('Usage Report')}}</span>
                    </a>
                </div>


            </div>


            <!--end::Menu-->


        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
<!--end::Sidebar-->
