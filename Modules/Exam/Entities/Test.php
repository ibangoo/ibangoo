<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'total_score',
        'options',
        'mode',
        'is_auto',
        'status'
    ];

    public function getModeNameAttribute()
    {
        return $this->mode === 'tag' ? '标签选题' : '题库选题';
    }

    public function getIsAutoLabelAttribute()
    {
        return $this->is_auto ? '系统判卷' : '人工判卷';
    }

    public function getStatusLabelAttribute()
    {
        return $this->status ? '启用中' : '禁用中';
    }

    /**
     * 关联标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * 关联试题
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function questions()
    {
        return $this->morphToMany(Question::class, 'questionable');
    }
}
