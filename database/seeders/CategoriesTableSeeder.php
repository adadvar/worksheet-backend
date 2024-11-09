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
            ['name' => 'کلاس دوم', 'slug' => 'second-elementary', 'type' => 'grade'],
            ['name' => 'کلاس سوم', 'slug' => 'third-elementary', 'type' => 'grade'],
            ['name' => 'کلاس چهارم', 'slug' => 'fourth-elementary', 'type' => 'grade'],
            ['name' => 'کلاس پنجم', 'slug' => 'fifth-elementary', 'type' => 'grade'],
            ['name' => 'کلاس ششم', 'slug' => 'sixth-elementary', 'type' => 'grade'],
        ];

        $subjects = [
            'first-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject', 'icon' => 'HiOutlineBookOpen'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject', 'icon' => 'HiOutlinePencilSquare'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject', 'icon' => 'HiOutlineCalculator'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject', 'icon' => 'HiOutlineBeaker'],
            ],
            'second-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject', 'icon' => 'HiOutlineBookOpen'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject', 'icon' => 'HiOutlineHandRaised'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject', 'icon' => 'HiOutlinePencilSquare'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject', 'icon' => 'HiOutlineCalculator'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject', 'icon' => 'HiOutlineBeaker'],
            ],
            'third-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject', 'icon' => 'HiOutlineBookOpen'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject', 'icon' => 'HiOutlineHandRaised'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject', 'icon' => 'HiOutlinePencilSquare'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject', 'icon' => 'HiOutlineCalculator'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject', 'icon' => 'HiOutlineBeaker'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject', 'icon' => 'HiOutlineUserGroup'],
            ],
            'fourth-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject', 'icon' => 'HiOutlineBookOpen'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject', 'icon' => 'HiOutlineHandRaised'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject', 'icon' => 'HiOutlinePencilSquare'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject', 'icon' => 'HiOutlineCalculator'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject', 'icon' => 'HiOutlineBeaker'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject', 'icon' => 'HiOutlineUserGroup'],
            ],
            'fifth-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject', 'icon' => 'HiOutlineBookOpen'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject', 'icon' => 'HiOutlineHandRaised'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject', 'icon' => 'HiOutlinePencilSquare'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject', 'icon' => 'HiOutlineCalculator'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject', 'icon' => 'HiOutlineBeaker'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject', 'icon' => 'HiOutlineUserGroup'],
            ],
            'sixth-elementary' => [
                ['name' => 'آموزش قرآن', 'slug' => 'quran', 'type' => 'subject', 'icon' => 'HiOutlineBookOpen'],
                ['name' => 'هدیه‌های آسمان', 'slug' => 'hedyeh-aseman', 'type' => 'subject', 'icon' => 'HiOutlineHandRaised'],
                ['name' => 'فارسی', 'slug' => 'farsi', 'type' => 'subject', 'icon' => 'HiOutlinePencilSquare'],
                ['name' => 'ریاضی', 'slug' => 'math', 'type' => 'subject', 'icon' => 'HiOutlineCalculator'],
                ['name' => 'علوم تجربی', 'slug' => 'science', 'type' => 'subject', 'icon' => 'HiOutlineBeaker'],
                ['name' => 'مطالعات اجتماعی', 'slug' => 'social-studies', 'type' => 'subject', 'icon' => 'HiOutlineUserGroup'],
                ['name' => 'تفکر و پژوهش', 'slug' => 'thinking-research', 'type' => 'subject', 'icon' => 'HiOutlineLightBulb'],
                ['name' => 'کار و فناوری', 'slug' => 'work-technology', 'type' => 'subject', 'icon' => 'HiOutlineWrenchScrewdriver'],
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
                    'icon' => $subjectData['icon'],
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
