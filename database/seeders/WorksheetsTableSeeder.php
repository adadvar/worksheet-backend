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
            ['category_id' => 3, 'name' => 'کاربرگ1', 'slug' => 'karbarg1', 'description' => 'توضیحات کاربرگ1', 'price' => 25000, 'banner' => 'worksheet1.jpg', 'file' => 'worksheet1.pdf'],
            ['category_id' => 4, 'name' => 'کاربرگ2', 'slug' => 'karbarg2', 'description' => 'توضیحات کاربرگ2', 'price' => 20000, 'banner' => 'worksheet2.jpg', 'file' => 'worksheet2.pdf'],
            ['category_id' => 6, 'name' => 'کاربرگ3', 'slug' => 'karbarg3', 'description' => 'توضیحات کاربرگ3', 'price' => 30000, 'banner' => 'worksheet3.jpg', 'file' => 'worksheet3.pdf'],
            ['category_id' => 7, 'name' => 'کاربرگ4', 'slug' => 'karbarg4', 'description' => 'توضیحات کاربرگ4', 'price' => 15000, 'banner' => 'worksheet4.jpg', 'file' => 'worksheet4.pdf'],
            ['category_id' => 10, 'name' => 'کاربرگ5', 'slug' => 'karbarg5', 'description' => 'توضیحات کاربرگ5', 'price' => 10000, 'banner' => 'worksheet5.jpg', 'file' => 'worksheet5.pdf'],
            ['category_id' => 11, 'name' => 'کاربرگ6', 'slug' => 'karbarg6', 'description' => 'توضیحات کاربرگ6', 'price' => 30000, 'banner' => 'worksheet6.jpg', 'file' => 'worksheet6.pdf'],
            ['category_id' => 13, 'name' => 'کاربرگ7', 'slug' => 'karbarg7', 'description' => 'توضیحات کاربرگ7', 'price' => 10000, 'banner' => 'worksheet7.jpg', 'file' => 'worksheet7.pdf'],
            ['category_id' => 14, 'name' => 'کاربرگ8', 'slug' => 'karbarg6', 'description' => 'توضیحات کاربرگ6', 'price' => 15000, 'banner' => 'worksheet6.jpg', 'file' => 'worksheet6.pdf'],
            ['category_id' => 17, 'name' => 'کاربرگ9', 'slug' => 'karbarg9', 'description' => 'توضیحات کاربرگ9', 'price' => 25000, 'banner' => 'worksheet9.jpg', 'file' => 'worksheet9.pdf'],
            ['category_id' => 18, 'name' => 'کاربرگ10', 'slug' => 'karbarg10', 'description' => 'توضیحات کاربرگ10', 'price' => 20000, 'banner' => 'worksheet10.jpg', 'file' => 'worksheet10.pdf'],
            ['category_id' => 20, 'name' => 'کاربرگ11', 'slug' => 'karbarg11', 'description' => 'توضیحات کاربرگ11', 'price' => 5000, 'banner' => 'worksheet11.jpg', 'file' => 'worksheet11.pdf'],
            ['category_id' => 21, 'name' => 'کاربرگ12', 'slug' => 'karbarg12', 'description' => 'توضیحات کاربرگ12', 'price' => 20000, 'banner' => 'worksheet12.jpg', 'file' => 'worksheet12.pdf'],
        ];

        foreach ($worksheets as $worksheet) {
            Worksheet::create($worksheet);
        }
    }
}
