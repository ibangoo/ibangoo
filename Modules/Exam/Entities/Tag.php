<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * 获取标签对应的所有试题
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function questions()
    {
        return $this->morphedByMany(Question::class, 'taggable');
    }

    /**
     * 获取标签关联的所有测试
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tests()
    {
        return $this->morphedByMany(Test::class, 'taggable');
    }
}
