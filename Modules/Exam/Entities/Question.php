<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $fillable = ['type', 'content', 'content_image', 'options', 'explain', 'explain_image'];
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INPUT = 'input';
    const TYPE_TEXTAREA = 'textarea';
    public static $typeMap = [
        self::TYPE_RADIO => '单选题',
        self::TYPE_CHECKBOX => '多选题',
        self::TYPE_BOOLEAN => '判断题',
        self::TYPE_INPUT => '填空题',
        self::TYPE_TEXTAREA => '简答题',
    ];

    /**
     * 获取试题类型名称
     *
     * @return mixed
     */
    public function getTypeNameAttribute()
    {
        return self::$typeMap[$this->type];
    }

    /**
     * 获取所有标签名称
     *
     * @return string
     */
    public function getTagsToStringAttribute()
    {
        return implode('、', $this->tags->pluck('name')->toArray());
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
}
