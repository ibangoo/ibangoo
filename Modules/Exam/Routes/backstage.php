<?php

Route::name('backstage.')->group(function () {
    // 测试管理
    Route::patch('tests/{test}/change-status', 'TestController@changeStatus')->name('tests.change_status');
    Route::resource('tests', 'TestController', ['except' => ['show']]);
    Route::get('tests/{test}/questions', 'TestController@questions')->name('tests.questions');
    Route::get('tests/{test}/search-questions', 'TestController@searchQuestions')->name('tests.search_questions');
    Route::post('tests/{test}/attach-questions', 'TestController@attachQuestions')->name('tests.attach_questions');
    Route::delete('tests/{test}/detach-questions', 'TestController@detachQuestions')->name('tests.detach_questions');

    // 标签管理
    Route::resource('tags', 'TagController', ['except' => ['show']]);

    // 题库管理
    Route::delete('questions/batch-destroy', 'QuestionController@batchDestroy')->name('questions.batch_destroy');
    Route::resource('questions', 'QuestionController', ['except' => ['show']]);
});
