<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryDeleteRequest;
use App\Http\Requests\Category\CategoryListRequest;
use App\Http\Requests\Category\CategoryShowRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function list(CategoryListRequest $r)
    {
        $query = Category::query();
        if ($r->q) {
            $query->where('name', 'LIKE', '%' . $r->q . '%');
        }
        $query->where('parent_id', null)->with('child')->get();
        if ($r->page)
            return $query->paginate($r->per_page ?? 10);
        return $query->get();
    }


    public function show(CategoryShowRequest $r)
    {
        $results = [];

        if ($r->grade) {
            $results[] = Category::where(['type' => 'grade', 'slug' => $r->grade])->first();
        }

        if ($r->subject) {
            $results[] = Category::where(['type' => 'subject', 'slug' => $r->subject])
                ->whereHas('parent', function ($query) use ($r) {
                    $query->where('slug', $r->grade);
                })->orWhereHas('parent.parent', function ($query) use ($r) {
                    $query->where('slug', $r->grade);
                })->first();
        }

        if ($r->topic) {
            $results[] = Category::where(['type' => 'topic', 'slug' => $r->topic])
                ->whereHas('parent', function ($query) use ($r) {
                    $query->where('slug', $r->subject);
                })
                ->whereHas('parent.parent', function ($query) use ($r) {
                    $query->where('slug', $r->grade);
                })->first();
        }

        return ($results);
    }

    public function create(CategoryCreateRequest $r)
    {
        try {
            DB::beginTransaction();
            $data = $r->validated();
            $user = auth()->user();
            $category = Category::create($data);

            DB::commit();
            return response($category, 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public function update(CategoryUpdateRequest $r)
    {
        try {
            DB::beginTransaction();
            $data = $r->validated();
            $category = $r->category;
            $category = tap($category)->update($data);

            DB::commit();
            return response($category, 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public function delete(CategoryDeleteRequest $r)
    {
        try {
            DB::beginTransaction();
            $category = $r->category;
            if (!$category) {
                return response(['message' => 'دسته بندی یافت نشد!'], 404);
            }
            $category->delete();
            DB::commit();
            return response(['message' => 'با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است !'], 500);
        }
    }
}
