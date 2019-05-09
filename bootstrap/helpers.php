<?php

if (!function_exists('is_active_route_group')){
    /**
     * 判断当前菜单是否属于当前路由组
     *
     * @param $routeName
     *
     * @return string
     */
    function is_active_route_group($routeName){
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
    function get_request_params(\Illuminate\Foundation\Http\FormRequest $request){
        return $request->only(array_keys($request->rules()));
    }
}