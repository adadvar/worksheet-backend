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
                // ['name' => 'تفکر و پژوهش', 'slug' => 'thinking-research', 'type' => 'subject', 'icon' => 'HiOutlineLightBulb'],
                // ['name' => 'کار و فناوری', 'slug' => 'work-technology', 'type' => 'subject', 'icon' => 'HiOutlineWrenchScrewdriver'],
            ],
        ];

        $topics = [
            'first-elementary' => [
                'math' => [
                    ['name' => 'تم1', 'slug' => 'theme1', 'type' => 'topic'],
                    ['name' => 'تم2', 'slug' => 'theme2', 'type' => 'topic'],
                    ['name' => 'تم3', 'slug' => 'theme3', 'type' => 'topic'],
                    ['name' => 'تم4', 'slug' => 'theme4', 'type' => 'topic'],
                    ['name' => 'تم5', 'slug' => 'theme5', 'type' => 'topic'],
                    ['name' => 'تم6', 'slug' => 'theme6', 'type' => 'topic'],
                    ['name' => 'تم7', 'slug' => 'theme7', 'type' => 'topic'],
                    ['name' => 'تم8', 'slug' => 'theme8', 'type' => 'topic'],
                    ['name' => 'تم9', 'slug' => 'theme9', 'type' => 'topic'],
                    ['name' => 'تم10', 'slug' => 'theme10', 'type' => 'topic'],
                    ['name' => 'تم11', 'slug' => 'theme11', 'type' => 'topic'],
                    ['name' => 'تم12', 'slug' => 'theme12', 'type' => 'topic'],
                    ['name' => 'تم13', 'slug' => 'theme13', 'type' => 'topic'],
                    ['name' => 'تم14', 'slug' => 'theme14', 'type' => 'topic'],
                    ['name' => 'تم15', 'slug' => 'theme15', 'type' => 'topic'],
                    ['name' => 'تم16', 'slug' => 'theme16', 'type' => 'topic'],
                    ['name' => 'تم17', 'slug' => 'theme17', 'type' => 'topic'],
                    ['name' => 'تم18', 'slug' => 'theme18', 'type' => 'topic'],
                    ['name' => 'تم19', 'slug' => 'theme19', 'type' => 'topic'],
                    ['name' => 'تم20', 'slug' => 'theme20', 'type' => 'topic'],
                    ['name' => 'تم21', 'slug' => 'theme21', 'type' => 'topic'],
                    ['name' => 'تم22', 'slug' => 'theme22', 'type' => 'topic'],
                    ['name' => 'تم23', 'slug' => 'theme23', 'type' => 'topic'],
                    ['name' => 'تم24', 'slug' => 'theme24', 'type' => 'topic'],
                    ['name' => 'تم25', 'slug' => 'theme25', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'second-elementary' => [
                'math' => [
                    ['name' => 'فصل1', 'slug' => 'season1', 'type' => 'topic'],
                    ['name' => 'فصل2', 'slug' => 'season2', 'type' => 'topic'],
                    ['name' => 'فصل3', 'slug' => 'season3', 'type' => 'topic'],
                    ['name' => 'فصل4', 'slug' => 'season4', 'type' => 'topic'],
                    ['name' => 'فصل5', 'slug' => 'season5', 'type' => 'topic'],
                    ['name' => 'فصل6', 'slug' => 'season6', 'type' => 'topic'],
                    ['name' => 'فصل7', 'slug' => 'season7', 'type' => 'topic'],
                    ['name' => 'فصل8', 'slug' => 'season8', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'هنری', 'slug' => 'art', 'type' => 'topic'],
                    ['name' => 'رنگی', 'slug' => 'coloring', 'type' => 'topic'],
                ],
            ],
            'third-elementary' => [
                'math' => [
                    ['name' => 'فصل1', 'slug' => 'season1', 'type' => 'topic'],
                    ['name' => 'فصل2', 'slug' => 'season2', 'type' => 'topic'],
                    ['name' => 'فصل3', 'slug' => 'season3', 'type' => 'topic'],
                    ['name' => 'فصل4', 'slug' => 'season4', 'type' => 'topic'],
                    ['name' => 'فصل5', 'slug' => 'season5', 'type' => 'topic'],
                    ['name' => 'فصل6', 'slug' => 'season6', 'type' => 'topic'],
                    ['name' => 'فصل7', 'slug' => 'season7', 'type' => 'topic'],
                    ['name' => 'فصل8', 'slug' => 'season8', 'type' => 'topic'],
                ],
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
                'math' => [
                    ['name' => 'فصل1', 'slug' => 'season1', 'type' => 'topic'],
                    ['name' => 'فصل2', 'slug' => 'season2', 'type' => 'topic'],
                    ['name' => 'فصل3', 'slug' => 'season3', 'type' => 'topic'],
                    ['name' => 'فصل4', 'slug' => 'season4', 'type' => 'topic'],
                    ['name' => 'فصل5', 'slug' => 'season5', 'type' => 'topic'],
                    ['name' => 'فصل6', 'slug' => 'season6', 'type' => 'topic'],
                    ['name' => 'فصل7', 'slug' => 'season7', 'type' => 'topic'],
                ],
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
                'math' => [
                    ['name' => 'فصل1', 'slug' => 'season1', 'type' => 'topic'],
                    ['name' => 'فصل2', 'slug' => 'season2', 'type' => 'topic'],
                    ['name' => 'فصل3', 'slug' => 'season3', 'type' => 'topic'],
                    ['name' => 'فصل4', 'slug' => 'season4', 'type' => 'topic'],
                    ['name' => 'فصل5', 'slug' => 'season5', 'type' => 'topic'],
                    ['name' => 'فصل6', 'slug' => 'season6', 'type' => 'topic'],
                    ['name' => 'فصل7', 'slug' => 'season7', 'type' => 'topic'],
                ],
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
                'math' => [
                    ['name' => 'فصل1', 'slug' => 'season1', 'type' => 'topic'],
                    ['name' => 'فصل2', 'slug' => 'season2', 'type' => 'topic'],
                    ['name' => 'فصل3', 'slug' => 'season3', 'type' => 'topic'],
                    ['name' => 'فصل4', 'slug' => 'season4', 'type' => 'topic'],
                    ['name' => 'فصل5', 'slug' => 'season5', 'type' => 'topic'],
                    ['name' => 'فصل6', 'slug' => 'season6', 'type' => 'topic'],
                    ['name' => 'فصل7', 'slug' => 'season7', 'type' => 'topic'],
                ],
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
