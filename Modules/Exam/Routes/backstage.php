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
    Route::patch('tests/{test}/sync-tags', 'TestController@syncTags')->name('tests.sync-tags');

    // 测试试题排序
    Route::get('tests/{test}/drag-questions', 'TestController@dragQuestions')->name('tests.drag-questions');
    Route::post('tests/{test}/sort-questions', 'TestController@sortQuestions')->name('tests.sort-questions');

    // 标签管理
    Route::resource('tags', 'TagController', ['except' => ['show']]);

    // 题库管理
    Route::delete('questions/batch-destroy', 'QuestionController@batchDestroy')->name('questions.batch-destroy');
    Route::resource('questions', 'QuestionController', ['except' => ['show']]);
    Route::get('questions/import', 'QuestionController@import')->name('questions.import.view');
    Route::post('questions/import', 'QuestionController@import')->name('questions.import.handle');
    Route::post('questions/download-excel-template', 'QuestionController@downloadExcelTemplate')->name('questions.download-excel-template');


    // 已提交试卷
    Route::patch('test-papers/{testPaper}/change-score', 'TestPaperController@changeScore')->name('test-papers.change-score');
    Route::patch('test-papers/{testPaper}/judged', 'TestPaperController@judged')->name('test-papers.judged');
    Route::resource('test-papers', 'TestPaperController');
});
