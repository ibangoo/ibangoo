<?php

Route::name('backstage.')->group(function () {
    // 测试管理
    Route::patch('tests/{test}/change-status', 'TestController@changeStatus')->name('tests.change-status');
    Route::resource('tests', 'TestController', ['except' => ['show']]);

    // 测试试题关联
    Route::get('tests/{test}/questions', 'TestController@questions')->name('tests.questions');
    Route::get('tests/{test}/search-questions', 'TestController@searchQuestions')->name('tests.search-questions');
    Route::post('tests/{test}/attach-questions', 'TestController@attachQuestions')->name('tests.attach-questions');
    Route::delete('tests/{test}/detach-questions', 'TestController@detachQuestions')->name('tests.detach-questions');

    // 测试试题排序
    Route::get('tests/{test}/drag-questions', 'TestController@dragQuestions')->name('tests.drag-questions');
    Route::post('tests/{test}/sort-questions', 'TestController@sortQuestions')->name('tests.sort-questions');

    // 标签管理
    Route::resource('tags', 'TagController', ['except' => ['show']]);

    // 题库管理
    Route::delete('questions/batch-destroy', 'QuestionController@batchDestroy')->name('questions.batch-destroy');
    Route::resource('questions', 'QuestionController', ['except' => ['show']]);

    // 已提交试卷
    Route::patch('test-papers/{testPaper}/change-score', 'TestPaperController@changeScore')->name('test-papers.change-score');
    Route::patch('test-papers/{testPaper}/publish', 'TestPaperController@publish')->name('test-papers.publish');
    Route::resource('test-papers', 'TestPaperController');
});
