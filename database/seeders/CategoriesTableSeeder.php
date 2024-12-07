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
                'quran' => [
                    ['name' => 'درس اول: به نام خدا، بسم الله', 'slug' => 'lesson1', 'type' => 'topic'],
                    ['name' => 'درس دوم: نعمت های خدا', 'slug' => 'lesson2', 'type' => 'topic'],
                    ['name' => 'درس سوم: خانه ی ما', 'slug' => 'lesson3', 'type' => 'topic'],
                    ['name' => 'درس چهارم: قرآن بخوانیم', 'slug' => 'lesson4', 'type' => 'topic'],
                    ['name' => 'درس پنجم: کودک مسلمان', 'slug' => 'lesson5', 'type' => 'topic'],
                    ['name' => 'درس ششم: مدرسه ی ما', 'slug' => 'lesson6', 'type' => 'topic'],
                    ['name' => 'درس هفتم: پیامبران خدا ', 'slug' => 'lesson7', 'type' => 'topic'],
                ],
                'farsi' => [
                    ['name' => 'نگاره‌ی 1: به خانه‌ی ما خوش آمدی', 'slug' => 'image1', 'type' => 'topic'],
                    ['name' => 'نگاره‌ 2: بچه ها، آماده!', 'slug' => 'image2', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 3: یک٬ دو و سه راه مدرسه', 'slug' => 'image3', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 4: به مدرسه رسیدیم', 'slug' => 'image4', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 5: از کلاس ما چه خبر؟', 'slug' => 'image5', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 6: بازی، بازی، تماشا', 'slug' => 'image6', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 7: به به چه روستایی', 'slug' => 'image7', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 8: چه دنیای قشنگی', 'slug' => 'image8', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 9: در مسجد محله', 'slug' => 'image9', 'type' => 'topic'],
                    ['name' => 'نگاره‌ی 10: نوروز در خانه‌ی ما', 'slug' => 'image10', 'type' => 'topic'],
                    ['name' => 'درس 1: آ ا ــ بـ ب', 'slug' => 'lesson1', 'type' => 'topic'],
                    ['name' => 'درس 2: اَ ـَ ــ د', 'slug' => 'lesson2', 'type' => 'topic'],
                    ['name' => 'درس 3: مـ م ــ سـ س', 'slug' => 'lesson3', 'type' => 'topic'],
                    ['name' => 'درس 4: او و ــ تـ ت', 'slug' => 'lesson4', 'type' => 'topic'],
                    ['name' => 'درس 5: ر ــ نـ ن', 'slug' => 'lesson5', 'type' => 'topic'],
                    ['name' => 'درس 6: ایـ یـ ی ای ــ ز', 'slug' => 'lesson6', 'type' => 'topic'],
                    ['name' => 'درس 7: اِ ـِ ــ ـه ه ــ شـ ش', 'slug' => 'lesson7', 'type' => 'topic'],
                    ['name' => 'درس 8: یـ ى ــ اُ ـُ', 'slug' => 'lesson8', 'type' => 'topic'],
                    ['name' => 'درس 9: کـ ک ــ و', 'slug' => 'lesson9', 'type' => 'topic'],
                    ['name' => 'درس 10: پـ پ ــ گـ گ', 'slug' => 'lesson10', 'type' => 'topic'],
                    ['name' => 'درس 11: فـ ف ــ خـ خ', 'slug' => 'lesson11', 'type' => 'topic'],
                    ['name' => 'درس 12: قـ ق ــ لـ ل', 'slug' => 'lesson12', 'type' => 'topic'],
                    ['name' => 'درس 13: جـ ج ــ ـُ استثنا', 'slug' => 'lesson13', 'type' => 'topic'],
                    ['name' => 'درس 14: هـ ـهـ ـه ه ــ چـ چ', 'slug' => 'lesson14', 'type' => 'topic'],
                    ['name' => 'درس 15: ژ ــ خوا', 'slug' => 'lesson15', 'type' => 'topic'],
                    ['name' => 'درس 16: در بازار ” تشدید ـّ “', 'slug' => 'lesson16', 'type' => 'topic'],
                    ['name' => 'درس 17: صدایِ موج ”صـ ص“ ــ سفرِ دلپذیر ”ذ“', 'slug' => 'lesson17', 'type' => 'topic'],
                    ['name' => 'درس 18: علی و معصومه ”عـ ـعـ ـع ع“ ــ مثلِ خورشید ”ثـ ث“', 'slug' => 'lesson18', 'type' => 'topic'],
                    ['name' => 'درس 19: حَلَزون ”حـ ح“', 'slug' => 'lesson19', 'type' => 'topic'],
                    ['name' => 'درس 20: رضا ”ضـ ض“ ــ خاطرات انقلاب ”ط“', 'slug' => 'lesson20', 'type' => 'topic'],
                    ['name' => 'درس 21: لاک‌پشت و مرغابی‌ها ”غـ ـغـ ـغ غ“', 'slug' => 'lesson21', 'type' => 'topic'],
                    ['name' => 'درس 22: پیامبر مهربان “ظ”', 'slug' => 'lesson22', 'type' => 'topic'],

                ],
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
                'science' => [
                    ['name' => 'فصل 1: زنگ علوم', 'slug' => 'season1', 'type' => 'topic'],
                    ['name' => 'فصل 2: سلام، به من نگاه کن!', 'slug' => 'season2', 'type' => 'topic'],
                    ['name' => 'فصل 3: سالم باش، شاداب باش', 'slug' => 'season3', 'type' => 'topic'],
                    ['name' => 'فصل 4: دنیای جانوران', 'slug' => 'season4', 'type' => 'topic'],
                    ['name' => 'فصل 5: دنیای گیاهان', 'slug' => 'season5', 'type' => 'topic'],
                    ['name' => 'فصل 6: زمین خانه ی پر آب ما', 'slug' => 'season6', 'type' => 'topic'],
                    ['name' => 'فصل 7: زمین خانه ی سنگی ما', 'slug' => 'season7', 'type' => 'topic'],
                    ['name' => 'فصل 8: چه می خواهم بسازم؟', 'slug' => 'season8', 'type' => 'topic'],
                    ['name' => 'فصل 9: زمین خانه ی خاکی ما', 'slug' => 'season9', 'type' => 'topic'],
                    ['name' => 'فصل 10: در اطراف ما هوا وجود دارد', 'slug' => 'season10', 'type' => 'topic'],
                    ['name' => 'فصل 11: دنیای سرد و گرم', 'slug' => 'season12', 'type' => 'topic'],
                    ['name' => 'فصل 12: از خانه تا مدرسه', 'slug' => 'season13', 'type' => 'topic'],
                    ['name' => 'فصل 13: آهن ربای من', 'slug' => 'season13', 'type' => 'topic'],
                    ['name' => 'فصل 14: از گذشته تا آینده', 'slug' => 'season14', 'type' => 'topic'],
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
                    ['name' => 'هنری', 'slug' => 'season1', 'type' => 'topic'],
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
