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
            ['name' => 'کلاس اول', 'slug' => 'first-elementary', 'type' => 'grade'],
            ['name' => 'کلاس دوم', 'slug' => 'dovom-elementary', 'type' => 'grade'],
            ['name' => 'کلاس سوم', 'slug' => 'sevom-elementary', 'type' => 'grade'],
            ['name' => 'کلاس چهارم', 'slug' => 'chaharom-elementary', 'type' => 'grade'],
            ['name' => 'کلاس پنجم', 'slug' => 'panjom-elementary', 'type' => 'grade'],
            ['name' => 'کلاس ششم', 'slug' => 'sheshom-elementary', 'type' => 'grade'],
        ];

        $subjects = [
            'first-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
                ['name' => 'نگارش فارسی', 'slug' => 'farsi-writing', 'type' => 'subject'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject'],
            ],
            'second-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
                ['name' => 'نگارش فارسی', 'slug' => 'farsi-writing', 'type' => 'subject'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject'],
            ],
            'third-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
                ['name' => 'نگارش فارسی', 'slug' => 'farsi-writing', 'type' => 'subject'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject'],
            ],
            'fourth-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
                ['name' => 'نگارش فارسی', 'slug' => 'farsi-writing', 'type' => 'subject'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject'],
            ],
            'fifth-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
                ['name' => 'نگارش فارسی', 'slug' => 'farsi-writing', 'type' => 'subject'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject'],
            ],
            'sixth-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject'],
                ['name' => 'نگارش فارسی', 'slug' => 'farsi-writing', 'type' => 'subject'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject'],
                ['name' => 'تفکر و پژوهش', 'slug' => 'thinking-research', 'type' => 'subject'],
                ['name' => 'کار و فناوری', 'slug' => 'work-technology', 'type' => 'subject'],
            ],
        ];

        $topics = [
            'first-elementary' => [
                'quran' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'second-elementary' => [
                'quran' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'third-elementary' => [
                'quran' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'quran' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'fourth-elementary' => [
                'quran' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'fifth-elementary' => [
                'quran' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'sixth-elementary' => [
                'quran' => [
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
