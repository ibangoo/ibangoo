<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestPaper extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'test_id',
        'minutes',
        'total_score',
        'actual_score',
        'status',
        'is_judge',
        'answers',
        'content'
    ];
}
