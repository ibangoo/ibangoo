<?php

namespace Modules\Exam\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Exam\Entities\Test;

class TestsTransformer extends TransformerAbstract
{
    public function transform(Test $test)
    {
        return $test->toArray();
    }
}