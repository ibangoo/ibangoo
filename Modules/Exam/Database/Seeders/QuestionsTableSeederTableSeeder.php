<?php

namespace Modules\Exam\Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Modules\Exam\Entities\Question;
use Modules\Exam\Entities\Tag;

class QuestionsTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);
        $typeMap = array_keys(Question::$typeMap);

        $items = factory(Question::class)
            ->times(200)
            ->make()
            ->each(function ($item) use ($faker, $typeMap) {
                $type = $faker->randomElement($typeMap);
                $item->type = $type;
                $item->content = $faker->sentence;
                $item->content_image = $faker->imageUrl();
                $item->explain = $faker->paragraph;
                $item->explain_image = $faker->imageUrl();

                switch ($type) {
                    case Question::TYPE_RADIO:
                        $answer = $faker->randomElement(['A', 'B', 'C', 'D']);
                        $item->answer = $answer;
                        $item->options = json_encode([
                            [
                                'code' => 'A',
                                'is_right' => $answer === 'A',
                                'body' => 'A.'.$faker->sentence,
                            ],
                            [
                                'code' => 'B',
                                'is_right' => $answer === 'B',
                                'body' => 'B.'.$faker->sentence,
                            ],
                            [
                                'code' => 'C',
                                'is_right' => $answer === 'C',
                                'body' => 'C.'.$faker->sentence,
                            ],
                            [
                                'code' => 'D',
                                'is_right' => $answer === 'D',
                                'body' => 'D.'.$faker->sentence,
                            ],
                        ]);
                        break;
                    case Question::TYPE_CHECKBOX:
                        $answers = $faker->randomElements(['A', 'B', 'C', 'D'], $faker->numberBetween(1, 4));
                        $item->answer = implode('、', $answers);
                        $item->options = json_encode([
                            [
                                'code' => 'A',
                                'is_right' => in_array('A', $answers, true),
                                'body' => 'A.'.$faker->sentence,
                            ],
                            [
                                'code' => 'B',
                                'is_right' => in_array('B', $answers, true),
                                'body' => 'B.'.$faker->sentence,
                            ],
                            [
                                'code' => 'C',
                                'is_right' => in_array('C', $answers, true),
                                'body' => 'C.'.$faker->sentence,
                            ],
                            [
                                'code' => 'D',
                                'is_right' => in_array('D', $answers, true),
                                'body' => 'D.'.$faker->sentence,
                            ],
                        ]);
                        break;
                    case Question::TYPE_INPUT:
                        $options = [];
                        $answers = $faker->randomElements(['刘德华', '张学友', '黎明', '谢霆锋', '周杰伦', '麦浚龙', '谢安琪', '蔡徐坤'], $faker->numberBetween(1, 8));
                        foreach ($answers as $answer) {
                            $options[] = ['body' => $answer];
                        }
                        $item->answer = implode('、', $answers);
                        $item->options = json_encode($options);
                        break;
                    case Question::TYPE_BOOLEAN:
                        $answer = $faker->randomElement([Question::BOOLEAN_TRUE, Question::BOOLEAN_FALSE]);
                        $options = [];
                        foreach ([Question::BOOLEAN_TRUE, Question::BOOLEAN_FALSE] as $body) {
                            $options[] = [
                                'is_right' => $body === $answer,
                                'body' => $body,
                            ];
                        }
                        $item->answer = $answer;
                        $item->options = json_encode($options);
                        break;
                    case Question::TYPE_TEXTAREA:
                        $item->answer = null;
                        $item->options = $faker->paragraph;
                        break;
                }
            })
            ->toArray();

        Question::query()->insert($items);

        // 关联标签
        $tags = Tag::all()->pluck('id');
        $questions = Question::all();
        foreach ($questions as $question) {
            $question->tags()->attach($faker->randomElement($tags));
        }
    }
}
