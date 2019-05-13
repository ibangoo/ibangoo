<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version(config('api.version'), [
    'namespace' => 'Modules\Exam\Http\Controllers\Api'
], function ($api) {

    // 获取测试列表接口
    $api->get('tests', 'TestController@index')->name('api.tests.index');
});