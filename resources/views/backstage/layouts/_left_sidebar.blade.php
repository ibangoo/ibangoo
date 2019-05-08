<div class="left-side-menu">
    <div class="slimscroll-menu">
        {{-- LOGO --}}
        <a href="javascript:void(0);" class="logo text-center">
            <span class="logo-lg">
                <img src="{{ asset('/images/logo.png') }}" alt="Logo" height="16">
            </span>
            <span class="logo-sm">
                <img src="{{ asset('/images/logo_sm.png') }}" alt="Logo" height="16">
            </span>
        </a>
        {{-- Sidebar --}}
        <ul class="metismenu side-nav">
            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
                    <i class="dripicons-copy"></i>
                    <span> 题库管理 </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level collapse" aria-expanded="false">
                    <li>
                        <a href="">题库列表</a>
                    </li>
                    <li>
                        <a href="">手动录入试题</a>
                    </li>
                    <li>
                        <a href="">批量导入试题</a>
                    </li>
                </ul>
            </li>
            <li class="side-nav-item">
                <a href="javascript:void(0);" class="side-nav-link" aria-expanded="false">
                    <i class="dripicons-document"></i>
                    <span> 测试管理 </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level collapse" aria-expanded="false">
                    <li>
                        <a href="javascript:void(0);">测试列表</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">新增测试试卷</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">已提交试卷</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
