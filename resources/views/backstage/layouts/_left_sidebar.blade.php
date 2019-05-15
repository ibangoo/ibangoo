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
            {{-- 标签管理 --}}
            <li class="side-nav-item {{ is_active_route_group('tags') }}">
                <a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
                    <i class="dripicons-copy"> </i>
                    <span> 标签管理 </span>
                    <span class="menu-arrow"> </span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('backstage.tags.index') }}">标签列表</a>
                    </li>
                    <li>
                        <a href="{{ route('backstage.tags.create') }}">新增标签</a>
                    </li>
                </ul>
            </li>
            {{-- 题库管理 --}}
            <li class="side-nav-item {{ is_active_route_group('questions') }}">
                <a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
                    <i class="dripicons-copy"> </i>
                    <span> 题库管理 </span>
                    <span class="menu-arrow"> </span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li class="{{ Route::currentRouteName() === 'backstage.questions.index' ? 'active' : null }}">
                        <a href="{{ route('backstage.questions.index') }}">题库列表</a>
                    </li>
                    <li class="{{ Route::currentRouteName() === 'backstage.questions.create' ? 'active' : null }}">
                        <a href="{{ route('backstage.questions.create', ['type' => 'radio']) }}">手动录入试题</a>
                    </li>
                    <li>
                        <a href="{{ route('backstage.questions.import.view') }}">批量导入试题</a>
                    </li>
                </ul>
            </li>
            <li class="side-nav-item {{ is_active_route_group('tests') }}">
                <a href="javascript:void(0);" class="side-nav-link" aria-expanded="false">
                    <i class="dripicons-document"> </i>
                    <span> 测试管理 </span>
                    <span class="menu-arrow"> </span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('backstage.tests.index') }}">测试列表</a>
                    </li>
                    <li>
                        <a href="{{ route('backstage.tests.create') }}">新增测试</a>
                    </li>
                    <li>
                        <a href="{{ route('backstage.test-papers.index') }}">已提交试卷</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
