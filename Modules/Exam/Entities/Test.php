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
        'status',
    ];
    const MODE_TAGS = 'tag';
    const MODE_QUESTIONS = 'question';
    public static $modeMap = [
        self::MODE_TAGS => '标签抽题',
        self::MODE_QUESTIONS => '题库选题',
    ];

    public function getModeNameAttribute()
    {
        return $this->mode === self::MODE_TAGS ? '标签选题' : '题库选题';
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

    /**
     * 关联中间表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questionables()
    {
        return $this->hasMany(Questionable::class, 'questionable_id');
    }
}
