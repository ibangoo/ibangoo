<?php

if (!function_exists('is_active_route_group')) {
    /**
     * 判断当前菜单是否属于当前路由组
     *
     * @param $routeName
     *
     * @return string
     */
    function is_active_route_group($routeName)
    {
        return explode('.', request()->route()->getName())[1] === $routeName ? 'active' : '';
    }
}

if (!function_exists('get_request_params')) {
    /**
     * 获取请求参数
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    function get_request_params(\Illuminate\Http\Request $request)
    {
        return $request->only(array_keys($request->rules()));
    }
}

if (!function_exists('is_exist')) {
    /**
     * 判断变量是否存在
     *
     * @param $parameter
     *
     * @return null
     */
    function is_exist($parameter)
    {
        if (isset($parameter) && !empty($parameter)) {
            return $parameter;
        }

        return null;
    }
}

if (!function_exists('get_type_name_color')) {
    /**
     * 根据题型类型获取颜色
     *
     * @param $type
     *
     * @return mixed
     */
    function get_type_name_color($type)
    {
        $rules = [
            'radio' => 'primary',
            'checkbox' => 'success',
            'boolean' => 'secondary',
            'input' => 'info',
            'textarea' => 'danger',
        ];

        return $rules[$type];
    }
}

if (!function_exists('get_questions_sort_number')) {
    /**
     * 根据题型类型获取颜色
     *
     * @param $page
     * @param $sortNumber
     *
     * @return mixed
     */
    function get_questions_sort_number($sortNumber, $page = 0)
    {
        if ($page === 0 || $page === 1) {
            return $sortNumber;
        }

        return $sortNumber + ($page * config('modules.paginator.per_page'));
    }
}