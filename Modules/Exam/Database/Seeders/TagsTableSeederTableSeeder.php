<?php

namespace Modules\Exam\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Exam\Entities\Tag;

class TagsTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            '标签一',
            '标签二',
            '标签三',
            '标签四',
            '标签五',
        ];

        $data = [];
        foreach ($names as $name) {
            $now = now();
            $data[] = [
                'name' => $name,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Tag::query()->insert($data);
    }
}
