<?php

namespace Modules\Exam\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Exam\Entities\Question;

class QuestionsImport implements ToModel
{
    public function model(array $row)
    {
        list($type, $tags, $content, $answer, $explain, $options) = $row;

        return new Question([
            'type' => $type,
            'tags' => $tags,
            'content' => $content,
            'answer' => $answer,
            'explain' => $explain,
            'options' => $options,
        ]);
    }
}