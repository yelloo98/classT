<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/admin/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info" style="padding-bottom: 10px;">
                <p style="margin-top : 5%; display: inline-block; margin-right: 2px;">{{Auth::guard('admins')->user()->name}}</p>
                <input class="btn btn-default btn-sm"  type="button" style="color: black" value="로그아웃" onclick="javascript:location.replace('/admin/logout');">
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">클래스이음</li>

            <li class="{{ (strpos(Request::path(), 'admin/program') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-pencil-square-o"></i> <span>교육프로그램 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/program') ? 'active' : ''}}"><a href="/admin/program"><i class="fa fa-circle-o"></i> 목록</a></li>
                    <li class="{{ (strpos(Request::path(), 'admin/program/detail') !== false) ? 'active' : ''}}"><a href="/admin/program/detail/0"><i class="fa fa-circle-o"></i> 상세 & 등록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/class') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-pencil-square"></i> <span>강의 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/class') ? 'active' : ''}}"><a href="/admin/class"><i class="fa fa-circle-o"></i> 목록</a></li>
                    {{--<li class="{{ (strpos(Request::path(), 'admin/class/detail') !== false) ? 'active' : ''}}"><a href="javascript:;"><i class="fa fa-circle-o"></i> 상세</a></li>--}}
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/request') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-pencil-square"></i> <span>프리미엄 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/request') ? 'active' : ''}}"><a href="/admin/request"><i class="fa fa-circle-o"></i> 목록</a></li>
                    {{--<li class="{{ (strpos(Request::path(), 'admin/request/detail') !== false) ? 'active' : ''}}"><a href="javascript:;"><i class="fa fa-circle-o"></i> 상세</a></li>--}}
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/proposal') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i> <span>제안서 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/proposal') ? 'active' : ''}}"><a href="/admin/proposal"><i class="fa fa-circle-o"></i> 목록</a></li>
                    {{--<li class="{{ (strpos(Request::path(), 'admin/proposal/detail') !== false) ? 'active' : ''}}"><a href="javascript:;"><i class="fa fa-circle-o"></i> 상세</a></li>--}}
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/partner') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-handshake-o"></i> <span>협력사 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/partner') ? 'active' : ''}}"><a href="/admin/partner"><i class="fa fa-circle-o"></i> 목록</a></li>
                    {{--<li class="{{ (strpos(Request::path(), 'admin/partner/detail') !== false) ? 'active' : ''}}"><a href="/admin/partner/detail/0"><i class="fa fa-circle-o"></i> 상세</a></li>--}}
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/lecture') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>강의분야</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/lecture') ? 'active' : ''}}"><a href="/admin/lecture"><i class="fa fa-circle-o"></i> 관리</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/notice') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-bell-o"></i> <span>공지사항 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/notice') ? 'active' : ''}}"><a href="/admin/notice"><i class="fa fa-circle-o"></i> 목록</a></li>
                    <li class="{{ (strpos(Request::path(), 'admin/notice/detail') !== false) ? 'active' : ''}}"><a href="/admin/notice/detail/0"><i class="fa fa-circle-o"></i> 상세 & 등록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/review') !== false) ? 'active' : '' }} treeview">
                <a href="/admin/review/company">
                    <i class="fa fa-pencil-square-o"></i> <span>강의후기</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ (strpos(Request::path(), 'admin/member/student') !== false) ? 'active' : '' }} treeview">
                        <a href="/admin/review/student"><i class="fa fa-circle-o"></i> 수강생 평가<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('admin/review/student') ? 'active' : '' }}"><a href="/admin/review/student"><i class="fa fa-circle-o"></i> 목록</a></li>
                            <li class="{{ Request::is('admin/question') ? 'active' : '' }}"><a href="/admin/question"><i class="fa fa-circle-o"></i> 평가항목</a></li>
                        </ul>
                    </li>
                    <li class="{{ (strpos(Request::path(), 'admin/review/company') !== false) ? 'active' : '' }} treeview">
                        <a href="/admin/review/company"><i class="fa fa-circle-o"></i> 기업 평가<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('admin/review/company') ? 'active' : '' }}"><a href="/admin/review/company"><i class="fa fa-circle-o"></i> 목록</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/qna') !== false) ? 'active' : '' }} treeview">
                <a href="javascript:admin/qna;"><i class="fa fa-comment"></i> <span>1:1 문의</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('admin/qna') ? 'active' : '' }}"><a href="/admin/qna"><i class="fa fa-circle-o"></i> 목록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/stats/classeum') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i> <span>통계</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/stats/classeum') ? 'active' : ''}}"><a href="/admin/stats/classeum"><i class="fa fa-circle-o"></i> 클래스이음 통계</a></li>
                </ul>
            </li>

        </ul>{{--end classEum sidebar-menu--}}

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">기업</li>

            <li class="{{ (strpos(Request::path(), 'admin/member/company') !== false) ? 'active' : '' }} treeview">
                <a href="/admin/member/company"><i class="fa fa-user"></i> 기업회원 관리<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('admin/member/company') ? 'active' : '' }}"><a href="/admin/member/company"><i class="fa fa-circle-o"></i> 목록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/calculate/company') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-calculator"></i> <span>정산 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/calculate/company') ? 'active' : ''}}"><a href="/admin/calculate/company"><i class="fa fa-circle-o"></i> 목록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/stats/company') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i> <span>통계</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/stats/company') ? 'active' : ''}}"><a href="/admin/stats/company"><i class="fa fa-circle-o"></i> 기업통계</a></li>
                </ul>
            </li>

        </ul>
        {{--end 기업 sidebar-menu--}}

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">강사</li>

            <li class="{{ (strpos(Request::path(), 'admin/member/teacher') !== false) ? 'active' : '' }} treeview">
                <a href="/admin/member/teacher"><i class="fa fa-user"></i> 강사회원 관리<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('admin/member/teacher') ? 'active' : '' }}"><a href="/admin/member/teacher"><i class="fa fa-circle-o"></i> 목록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/tGrade') !== false) ? 'active' : '' }} treeview">
                <a href="/admin/tGrade"><i class="fa fa-user"></i> 등급마스터<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('admin/tGrade') ? 'active' : '' }}"><a href="/admin/tGrade"><i class="fa fa-circle-o"></i> 목록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/calculate/teacher') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-calculator"></i> <span>정산 관리</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/calculate/teacher') ? 'active' : ''}}"><a href="/admin/calculate/teacher"><i class="fa fa-circle-o"></i> 목록</a></li>
                </ul>
            </li>

            <li class="{{ (strpos(Request::path(), 'admin/stats/teacher') !== false) ? 'active' : '' }} treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i> <span>통계</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('admin/stats/teacher') ? 'active' : ''}}"><a href="/admin/stats/teacher"><i class="fa fa-circle-o"></i> 강사별통계</a></li>
                </ul>
            </li>

        </ul>
        {{--end 강사 sidebar-menu--}}
    </section>
    <!-- /.sidebar -->
</aside>