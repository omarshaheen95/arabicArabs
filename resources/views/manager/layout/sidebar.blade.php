<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{route('manager.home')}}">
            <img alt="Logo" src="{{!settingCache('logo')? asset('logo.svg'):asset(settingCache('logo'))}}"
                 class="h-45px app-sidebar-logo-default"/>
            <img alt="Logo" src="{{!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))}}"
                 class="h-30px app-sidebar-logo-minimize "/>
        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <!--begin::Minimized sidebar setup:
if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
    1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
    2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
    3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
    4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
}
-->
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

                @can('show statistics')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/home*') ) active @endif"
                           href="{{ route('manager.home') }}">
                            <span class="menu-icon">
                          <i class="ki-duotone ki-chart-pie-simple fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                            </i>
                            </span>
                            <span class="menu-title">{{t('Dashboard')}}</span>
                        </a>
                    </div>
                @endcan


                @can('show managers')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/manager*') ) active @endif"
                           href="{{ route('manager.manager.index') }}">
                            <span class="menu-icon">
                               <i class="ki-duotone ki-briefcase fs-2">
                                 <i class="path1"></i>
                                 <i class="path2"></i>
                                </i>
                            </span>
                            <span class="menu-title">{{t('Managers')}}</span>
                        </a>
                    </div>
                @endcan


                @can('show schools')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/school*') || Request::is('manager/scheduling*')&& !Request::is('manager/school_level') ) active @endif"
                           href="{{ route('manager.school.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-teacher fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                            </i>
                        </span>
                            <span class="menu-title">{{t('Schools')}}</span>
                        </a>
                    </div>
                @endcan


                @can('show supervisors')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/supervisor*') ) active @endif"
                           href="{{ route('manager.supervisor.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-user-tick fs-2">
                                         <i class="path1"></i>
                                         <i class="path2"></i>
                                         <i class="path3"></i>
                                        </i>
                            </span>
                            <span class="menu-title">{{t('Supervisors')}}</span>
                        </a>
                    </div>
                @endcan


                @can('show users')
                    <div class="menu-item">
                        <a class="menu-link @if(Route::is('manager.user.index')||Route::is('manager.user.edit')||Route::is('manager.user.create'))active @endif"
                           href="{{ route('manager.user.index') }}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-profile-user fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                             <i class="path3"></i>
                             <i class="path4"></i>
                            </i>
                        </span>
                            <span class="menu-title">{{t('Students')}}</span>
                        </a>
                    </div>
                @endcan

                @can('show teachers')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/teacher*'))active @endif "
                           href="{{ route('manager.teacher.index') }}">
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

                @endcan

                @can('teacher tracking')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/tracking_teachers*'))active @endif "
                           href="{{ route('manager.teacher.tracking') }}">
                            <span class="menu-icon">
                               <i class="ki-duotone ki-graph-2 fs-2">
                                 <i class="path1"></i>
                                 <i class="path2"></i>
                                 <i class="path3"></i>
                                </i>
                            </span>
                            <span class="menu-title">{{t('Track Teachers')}}</span>
                        </a>
                    </div>
                @endcan

                @can('show lessons')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::routeIs('manager.lesson.index') || Request::is('manager/assessment*')|| Request::is('manager/lesson/*'))active @endif "
                           href="{{route('manager.lesson.index')}}">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                </span>
                            <span class="menu-title">{{t('Lessons')}}</span>
                        </a>
                    </div>
                @endcan


                @can('show stories')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::routeIs('manager.story.index') || Request::is('manager/story/*'))active @endif "
                           href="{{route('manager.story.index')}}">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                </span>
                            <span class="menu-title">{{t('Stories')}}</span>
                        </a>
                    </div>
                @endcan

                @if(Auth::guard('manager')->user()->hasAnyDirectPermission(['show lesson tests','show story tests']))
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion {{Request::is('manager/lessons_tests')|| Request::is('manager/stories_tests') ?'here show':''}}">
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
                            @can('show lesson tests')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is('manager/lessons_tests*') )active @endif"
                                       href="{{ route('manager.lessons_tests.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Lessons Tests')}}</span>
                                    </a>
                                </div>
                            @endcan
                            @can('show story tests')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is('manager/stories_tests*') )active @endif"
                                       href="{{ route('manager.stories_tests.index') }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                        <span class="menu-title">{{t('Stories Tests')}}</span>
                                    </a>
                                </div>
                            @endcan

                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endif

                @if(Auth::guard('manager')->user()->hasAnyDirectPermission(['show lesson assignments','show story assignments']))
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion {{Request::is('manager/lesson_assignment')|| Request::is('manager/story_assignment') ?'here show':''}}">
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
                            @can('show lesson assignments')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is('manager/lesson_assignment*') )active @endif"
                                       href="{{ route('manager.lesson_assignment.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Lessons Assignments')}}</span>
                                    </a>
                                </div>
                            @endcan

                            @can('show story assignments')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is('manager/story_assignment*') )active @endif"
                                       href="{{ route('manager.story_assignment.index') }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                        <span class="menu-title">{{t('Stories Assignments')}}</span>
                                    </a>
                                </div>
                            @endcan


                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endif
                @can('show motivational certificate')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is('manager/motivational_certificate*'))active @endif" href="{{ route('manager.motivational_certificate.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-teacher fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                            </i>
                    </span>
                            <span class="menu-title">{{t('Motivational Certificates')}}</span>
                        </a>
                    </div>
                @endcan

                @if(Auth::guard('manager')->user()->hasAnyDirectPermission(['show hidden lessons','show hidden stories']))
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion {{Request::is('manager/hidden_lesson')|| Request::is('manager/hidden_story') ?'here show':''}}">
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

                            @can('show hidden lessons')
                                <div class="menu-item">
                                    <a href="{{route('manager.hidden_lesson.index')}}"
                                       class="menu-link {{Request::is('manager/hidden_lesson*')?'active':''}}">
                                                        <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                        <span class="menu-title">{{t('Hidden Lessons')}}</span>
                                    </a>
                                </div>
                            @endcan
                            @can('show hidden stories')
                                <div class="menu-item">
                                    <a href="{{route('manager.hidden_story.index')}}"
                                       class="menu-link {{Request::is('manager/hidden_story*')?'active':''}}">
                                                        <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                        <span class="menu-title">{{t('Hidden Stories')}}</span>
                                    </a>
                                </div>
                            @endcan

                        </div>

                        <!--end:Menu sub-->
                    </div>
                @endif

                @if(Auth::guard('manager')->user()->hasAnyDirectPermission(['show user works','show user records']))
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion {{Request::is('manager/students_works')|| Request::is('manager/stories_records') ?'here show':''}}">
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
                            @can('show user works')
                                <div class="menu-item">
                                    <a href="{{route('manager.students_works.index')}}"
                                       class="menu-link {{Request::is('manager/students_works*')?'active':''}}">
                                                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Students Works')}}</span>
                                    </a>
                                </div>
                            @endcan
                            @can('show user records')
                                <div class="menu-item">
                                    <a href="{{route('manager.stories_records.index')}}"
                                       class="menu-link {{Request::is('manager/stories_records*')?'active':''}}">
                                                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Stories Recodes')}}</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!--end:Menu sub-->
                    </div>

                @endif


                @can('show packages')
                    <div class="menu-item">
                        <a href="{{route('manager.package.index')}}"
                           class="menu-link {{Request::is('manager/package*')?'active':''}}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-package fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                            </i>
                        </span>
                            <span class="menu-title">{{t('Packages')}}</span>
                        </a>
                    </div>
                @endcan


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{Route::is('manager.year.*') ||Request::is('manager/activity-log*') || Request::is('manager/settings*') || Request::is('manager/text_translation') ||  Request::is('manager/import_files*') || Request::is('manager/login_sessions*') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-setting-4 fs-2">
                                    </i>
                                            </span>
											<span class="menu-title">{{t('Settings')}}</span>
											<span class="menu-arrow"></span>
										</span>
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can('show years')
                            <div class="menu-item">
                                <a href="{{route('manager.year.index')}}"
                                   class="menu-link {{Route::is('manager.year.*')?'active':''}}">
                                <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                    <span class="menu-title">{{t('Years')}}</span>
                                </a>
                            </div>
                        @endcan
                        @can('import files')
                            <div class="menu-item">
                                <a href="{{route('manager.import_files.index')}}"
                                   class="menu-link {{Request::is('manager/import_files*')?'active':''}}">
                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                    <span class="menu-title">{{t('Import Files')}}</span>
                                </a>
                            </div>
                        @endcan
                        @can('show translation')
                            <div class="menu-item">
                                <a href="{{route('manager.text_translation.index')}}"
                                   class="menu-link {{Request::is('manager/text_translation*')?'active':''}}">
                                <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                    <span class="menu-title">{{t('Text translation')}}</span>
                                </a>
                            </div>
                        @endcan
                        @can('show settings')
                            <div class="menu-item">
                                <a href="{{route('manager.settings.general')}}"
                                   class="menu-link {{Request::is('manager/settings*')?'active':''}}">
                                                <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                    <span class="menu-title">{{t('General Settings')}}</span>
                                </a>
                            </div>
                        @endcan
                        @can('show activity logs')
                            <div class="menu-item">
                                <a class="menu-link @if(Request::is('manager/activity-log*') )active @endif"
                                   href="{{ route('manager.activity-log.index') }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                    <span class="menu-title">{{t('Activity Log')}}</span>
                                </a>
                            </div>

                        @endcan
                        @can('show login sessions')
                            <div class="menu-item">
                                <a class="menu-link @if(Request::is('manager/login_sessions*') )active @endif"
                                   href="{{ route('manager.login_sessions.index') }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                    <span class="menu-title">{{t('Login Sessions')}}</span>
                                </a>
                            </div>

                        @endcan
                    </div>
                    <!--end:Menu sub-->
                </div>


            </div>


            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
<!--end::Sidebar-->
