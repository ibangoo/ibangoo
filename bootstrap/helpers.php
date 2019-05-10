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
     * @param \Illuminate\Foundation\Http\FormRequest $request
     *
     * @return array
     */
    function get_request_params(\Illuminate\Foundation\Http\FormRequest $request)
    {
        return $request->only(array_keys($request->rules()));
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