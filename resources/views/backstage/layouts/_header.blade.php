<div class="navbar-custom">
    <ul class="list-unstyled topbar-right-menu float-right mb-0">
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
               aria-expanded="false">
                <span class="account-user-avatar">
{{--                    <img src="" alt="user-image" class="rounded-circle">--}}
                </span>
                <span>
                    <span class="account-user-name">用户昵称</span>
                    <span class="account-position">用户角色</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">欢迎 !</h6>
                </div>
                <a id="user-logout" href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-logout"></i>
                    <span>退出登录</span>
                </a>
                <form id="logout-form" action="javascript:void(0);" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    </ul>
    <button class="button-menu-mobile open-left disable-btn">
        <i class="mdi mdi-menu"></i>
    </button>
</div>
