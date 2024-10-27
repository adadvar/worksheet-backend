<?php

namespace Database\Seeders;

use App\Models\Worksheet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorksheetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Worksheet::count()) {
            Worksheet::truncate();
        }

        $worksheets = [
            ['grade_id' => 1, 'subject_id' => 2, 'topic_id' => 3, 'name' => 'کاربرگ1', 'slug' => 'karbarg1', 'description' => 'توضیحات کاربرگ1', 'price' => 25000, 'banner' => 'worksheet1.jpg', 'file_pdf' => 'worksheet1.pdf'],
            ['grade_id' => 1, 'subject_id' => 2, 'topic_id' => 4, 'name' => 'کاربرگ2', 'slug' => 'karbarg2', 'description' => 'توضیحات کاربرگ2', 'price' => 20000, 'banner' => 'worksheet2.jpg', 'file_pdf' => 'worksheet2.pdf'],
            ['grade_id' => 1, 'subject_id' => 5, 'topic_id' => 6, 'name' => 'کاربرگ3', 'slug' => 'karbarg3', 'description' => 'توضیحات کاربرگ3', 'price' => 30000, 'banner' => 'worksheet3.jpg', 'file_pdf' => 'worksheet3.pdf'],
            ['grade_id' => 1, 'subject_id' => 5, 'topic_id' => 7, 'name' => 'کاربرگ4', 'slug' => 'karbarg4', 'description' => 'توضیحات کاربرگ4', 'price' => 15000, 'banner' => 'worksheet4.jpg', 'file_pdf' => 'worksheet4.pdf'],
            ['grade_id' => 8, 'subject_id' => 9, 'topic_id' => 10, 'name' => 'کاربرگ5', 'slug' => 'karbarg5', 'description' => 'توضیحات کاربرگ5', 'price' => 10000, 'banner' => 'worksheet5.jpg', 'file_pdf' => 'worksheet5.pdf'],
            ['grade_id' => 8, 'subject_id' => 9, 'topic_id' => 11, 'name' => 'کاربرگ6', 'slug' => 'karbarg6', 'description' => 'توضیحات کاربرگ6', 'price' => 30000, 'banner' => 'worksheet6.jpg', 'file_pdf' => 'worksheet6.pdf'],
            ['grade_id' => 8, 'subject_id' => 12, 'topic_id' => 13, 'name' => 'کاربرگ7', 'slug' => 'karbarg7', 'description' => 'توضیحات کاربرگ7', 'price' => 10000, 'banner' => 'worksheet7.jpg', 'file_pdf' => 'worksheet7.pdf'],
            ['grade_id' => 8, 'subject_id' => 12, 'topic_id' => 14, 'name' => 'کاربرگ8', 'slug' => 'karbarg6', 'description' => 'توضیحات کاربرگ6', 'price' => 15000, 'banner' => 'worksheet6.jpg', 'file_pdf' => 'worksheet6.pdf'],
            ['grade_id' => 15, 'subject_id' => 16, 'topic_id' => 17, 'name' => 'کاربرگ9', 'slug' => 'karbarg9', 'description' => 'توضیحات کاربرگ9', 'price' => 25000, 'banner' => 'worksheet9.jpg', 'file_pdf' => 'worksheet9.pdf'],
            ['grade_id' => 15, 'subject_id' => 16, 'topic_id' => 18, 'name' => 'کاربرگ10', 'slug' => 'karbarg10', 'description' => 'توضیحات کاربرگ10', 'price' => 20000, 'banner' => 'worksheet10.jpg', 'file_pdf' => 'worksheet10.pdf'],
            ['grade_id' => 15, 'subject_id' => 19, 'topic_id' => 20, 'name' => 'کاربرگ11', 'slug' => 'karbarg11', 'description' => 'توضیحات کاربرگ11', 'price' => 5000, 'banner' => 'worksheet11.jpg', 'file_pdf' => 'worksheet11.pdf'],
            ['grade_id' => 15, 'subject_id' => 19, 'topic_id' => 21, 'name' => 'کاربرگ12', 'slug' => 'karbarg12', 'description' => 'توضیحات کاربرگ12', 'price' => 20000, 'banner' => 'worksheet12.jpg', 'file_pdf' => 'worksheet12.pdf'],
        ];

        foreach ($worksheets as $worksheet) {
            Worksheet::create($worksheet);
        }
    }
}
