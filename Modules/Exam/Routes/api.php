<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version(config('api.version'), [
    'namespace' => 'Modules\Exam\Http\Controllers\Api'
], function ($api) {

    // 获取测试列表
    $api->get('tests', 'TestController@index')->name('api.tests.index');

    // 获取测试详情
    $api->get('tests/{test}/questions', 'TestController@questions')->name('api.tests.questions');
});