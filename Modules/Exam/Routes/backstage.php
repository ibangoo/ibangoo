<?php

Route::name('backstage.')->group(function () {
    // 测试管理
    Route::get('tests', 'TestController@index');

    // 标签管理
    Route::resource('tags', 'TagController');

    // 题库管理
    Route::resource('questions', 'QuestionController');
    Route::post('questions/batch-destroy', 'QuestionController@batchDestroy')->name('questions.batch_destroy');
});
