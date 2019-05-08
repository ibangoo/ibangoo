<?php

Route::get('tests', 'TestController@index');

// 题库列表
Route::get('questions', 'QuestionController@index');
Route::get('questions/create', 'QuestionController@create');