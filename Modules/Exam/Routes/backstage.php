<?php

Route::name('backstage.')->group(function () {
    // 测试管理
    Route::get('tests', 'TestController@index');

    // 标签管理
    Route::resource('tags', 'TagController');

    // 题库管理
    Route::get('questions', 'QuestionController@index');
    Route::get('questions/create', 'QuestionController@create');
});
