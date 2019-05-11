<?php

Route::name('backstage.')->group(function () {
    // 测试管理
    Route::resource('tests', 'TestController');

    // 标签管理
    Route::resource('tags', 'TagController');

    // 题库管理
    Route::delete('questions/batch-destroy', 'QuestionController@batchDestroy')->name('questions.batch_destroy');
    Route::resource('questions', 'QuestionController');
});
