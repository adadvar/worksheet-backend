<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Product::count()) {
            Product::truncate();
        }

        $products = [
            ['grade_id' => 1, 'subject_id' => 2, 'topic_id' => 3, 'name' => 'کاربرگ1', 'slug' => 'karbarg1', 'description' => 'توضیحات کاربرگ1', 'price' => 25000, 'banner' => 'product1.jpg', 'file_pdf' => 'product1.pdf'],
            ['grade_id' => 1, 'subject_id' => 2, 'topic_id' => 4, 'name' => 'کاربرگ2', 'slug' => 'karbarg2', 'description' => 'توضیحات کاربرگ2', 'price' => 20000, 'banner' => 'product2.jpg', 'file_pdf' => 'product2.pdf'],
            ['grade_id' => 1, 'subject_id' => 5, 'topic_id' => 6, 'name' => 'کاربرگ3', 'slug' => 'karbarg3', 'description' => 'توضیحات کاربرگ3', 'price' => 30000, 'banner' => 'product3.jpg', 'file_pdf' => 'product3.pdf'],
            ['grade_id' => 1, 'subject_id' => 5, 'topic_id' => 7, 'name' => 'کاربرگ4', 'slug' => 'karbarg4', 'description' => 'توضیحات کاربرگ4', 'price' => 15000, 'banner' => 'product4.jpg', 'file_pdf' => 'product4.pdf'],
            ['grade_id' => 10, 'subject_id' => 11, 'topic_id' => 12, 'name' => 'کاربرگ5', 'slug' => 'karbarg5', 'description' => 'توضیحات کاربرگ5', 'price' => 10000, 'banner' => 'product5.jpg', 'file_pdf' => 'product5.pdf'],
            ['grade_id' => 10, 'subject_id' => 11, 'topic_id' => 13, 'name' => 'کاربرگ6', 'slug' => 'karbarg6', 'description' => 'توضیحات کاربرگ6', 'price' => 30000, 'banner' => 'product6.jpg', 'file_pdf' => 'product6.pdf'],
            ['grade_id' => 10, 'subject_id' => 14, 'topic_id' => 15, 'name' => 'کاربرگ7', 'slug' => 'karbarg7', 'description' => 'توضیحات کاربرگ7', 'price' => 10000, 'banner' => 'product7.jpg', 'file_pdf' => 'product7.pdf'],
            ['grade_id' => 10, 'subject_id' => 14, 'topic_id' => 16, 'name' => 'کاربرگ8', 'slug' => 'karbarg8', 'description' => 'توضیحات کاربرگ8', 'price' => 15000, 'banner' => 'product8.jpg', 'file_pdf' => 'product8.pdf'],
            ['grade_id' => 20, 'subject_id' => 21, 'topic_id' => 22, 'name' => 'کاربرگ9', 'slug' => 'karbarg9', 'description' => 'توضیحات کاربرگ9', 'price' => 25000, 'banner' => 'product9.jpg', 'file_pdf' => 'product9.pdf'],
            ['grade_id' => 20, 'subject_id' => 21, 'topic_id' => 23, 'name' => 'کاربرگ10', 'slug' => 'karbarg10', 'description' => 'توضیحات کاربرگ10', 'price' => 20000, 'banner' => 'product10.jpg', 'file_pdf' => 'product10.pdf'],
            ['grade_id' => 20, 'subject_id' => 24, 'topic_id' => 25, 'name' => 'کاربرگ11', 'slug' => 'karbarg11', 'description' => 'توضیحات کاربرگ11', 'price' => 5000, 'banner' => 'product11.jpg', 'file_pdf' => 'product11.pdf'],
            ['grade_id' => 20, 'subject_id' => 24, 'topic_id' => 26, 'name' => 'کاربرگ12', 'slug' => 'karbarg12', 'description' => 'توضیحات کاربرگ12', 'price' => 20000, 'banner' => 'product12.jpg', 'file_pdf' => 'product12.pdf'],
        ];

        // foreach ($products as $product) {
        //     Product::create($product);
        // }
    }
}
