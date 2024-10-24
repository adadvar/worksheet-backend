<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Transliterator;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count()) {
            Category::truncate();
        }

        $grades = [
            ['name' => 'اول ابتدایی', 'slug' => 'aval-ebtedayi', 'type' => 'grade'],
            ['name' => 'دوم ابتدایی', 'slug' => 'dovom-ebtedayi', 'type' => 'grade'],
            ['name' => 'سوم ابتدایی', 'slug' => 'sevom-ebtedayi', 'type' => 'grade'],
            ['name' => 'چهارم ابتدایی', 'slug' => 'chaharom-ebtedayi', 'type' => 'grade'],
            ['name' => 'پنجم ابتدایی', 'slug' => 'panjom-ebtedayi', 'type' => 'grade'],
            ['name' => 'ششم ابتدایی', 'slug' => 'sheshom-ebtedayi', 'type' => 'grade'],
        ];

        $subjects = [
            'aval-ebtedayi' => [
                ['name' => 'ریاضی', 'slug' => 'riazi', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
            ],
            'dovom-ebtedayi' => [
                ['name' => 'ریاضی', 'slug' => 'riazi', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
            ],
            'sevom-ebtedayi' => [
                ['name' => 'ریاضی', 'slug' => 'riazi', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
            ],
            'chaharom-ebtedayi' => [
                ['name' => 'ریاضی', 'slug' => 'riazi', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
            ],
            'panjom-ebtedayi' => [
                ['name' => 'ریاضی', 'slug' => 'riazi', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
            ],
            'sheshom-ebtedayi' => [
                ['name' => 'ریاضی', 'slug' => 'riazi', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
            ],
        ];

        $topics = [
            'aval-ebtedayi' => [
                'riazi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'dovom-ebtedayi' => [
                'riazi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'sevom-ebtedayi' => [
                'riazi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'chaharom-ebtedayi' => [
                'riazi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'panjom-ebtedayi' => [
                'riazi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'sheshom-ebtedayi' => [
                'riazi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
        ];

        foreach ($grades as $gradeData) {
            $grade = Category::create($gradeData);

            $gradeSubjects = $subjects[$gradeData['slug']] ?? [];

            foreach ($gradeSubjects as $subjectData) {
                $subject = Category::create([
                    'parent_id' => $grade->id,
                    'name' => $subjectData['name'],
                    'slug' => $subjectData['slug'],
                    'type' => $subjectData['type'],
                ]);

                $subjectTopics = $topics[$gradeData['slug']][$subjectData['slug']] ?? [];

                foreach ($subjectTopics as $topicData) {
                    Category::create([
                        'parent_id' => $subject->id,
                        'name' => $topicData['name'],
                        'slug' => $topicData['slug'],
                        'type' => $topicData['type'],
                    ]);
                }
            }
        }
    }
}
