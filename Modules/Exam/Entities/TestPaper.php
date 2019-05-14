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
        'is_judged',
        'answers',
        'content',
    ];
    const STATUS_FAIL = 'fail';
    const STATUS_PASS = 'pass';
    const STATUS_CREDIT = 'credit';
    const STATUS_DISTINCTION = 'distinction';
    const STATUS_HIGH_DISTINCTION = 'high_distinction';
    public static $statusMap = [
        self::STATUS_FAIL => '不及格',
        self::STATUS_PASS => '及格',
        self::STATUS_CREDIT => '中等',
        self::STATUS_DISTINCTION => '良好',
        self::STATUS_HIGH_DISTINCTION => '优秀',
    ];

    /**
     * 获取状态名称
     *
     * @return mixed
     */
    public function getStatusLabelAttribute()
    {
        return self::$statusMap[$this->status];
    }

    /**
     * 获取关联测试
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
