<?php

namespace App\Http\Controllers;

use App\Http\Requests\Worksheet\UploadWorksheetBannersRequest;
use App\Http\Requests\Worksheet\UploadWorksheetFileRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Worksheet\WorksheetCreateRequest;
use App\Http\Requests\Worksheet\WorksheetDeleteRequest;
use App\Http\Requests\Worksheet\WorksheetUpdateRequest;
use App\Models\Category;
use App\Models\Worksheet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WorksheetController extends Controller
{
    public function list(Request $r)
    {
        $whereIns = [];
        $conditions = [];
        $categoryRequest = null;

        if ($r->param1 && !$r->param2 && !$r->param3) {
            $categoryRequest = Category::whereSlug($r->param1)->first();
        }

        if ($r->param1 && $r->param2 && !$r->param3) {
            $categoryRequest = Category::whereSlug($r->param2)->first();
        }

        if ($r->param1 && $r->param2 && $r->param3) {
            $categoryRequest = Category::whereSlug($r->param2)->first();
        }


        if ($r->price) {
            $prices = explode('-', $r->price);
            if ($prices[0]) $conditions[] = ['price', '>=', $prices[0]];
            if ($prices[1]) $conditions[] = ['price', '<=', $prices[1]];
        }

        $conditions['state'] = 'accepted';

        if ($categoryRequest) {
            $ids = (Category::extractChildrenIds($categoryRequest));
            $whereIns['category_id'] = $ids;
        }

        $query = Worksheet::query();

        $query->where($conditions);
        foreach ($whereIns as $column => $values) {
            $query->whereIn($column, $values);
        }

        $query->with(['category']);

        if ($r->o == 'n' || $r->o == null) $query->orderBy('id', 'desc');
        if ($r->o == 'pa') $query->orderBy('price', 'asc');
        if ($r->o == 'pd') $query->orderBy('price', 'desc');

        $perPage = $r->per_page ?? 10;
        $worksheets = $query->paginate($perPage);

        return response($worksheets);
    }

    public function show(Request $r)
    {
        $worksheet = Worksheet::where('slug_url', $r->id_slug)
            ->orWhere('id', $r->id_slug)
            ->where('state', 'accepted')
            ->firstOrFail();
        // event(new VisitWorksheet($worksheet));
        $worksheet = $worksheet->load('user', 'category');
        return $worksheet;
    }

    public static function uploadBanner(UploadWorksheetBannersRequest $r)
    {
        try {
            $banner = $r->file('banner');

            $bannerName = time() . Str::random(10) . '-banner.' . $banner->getClientOriginalExtension();
            Storage::putFileAs('worksheets/tmp', $banner, $bannerName);

            DB::table('temporary_files')->insert([
                'file_name' => $bannerName,
                'created_at' => now(),
            ]);

            return response([
                'banner' => $bannerName
            ], 200);
        } catch (Exception $e) {
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public static function uploadFile(UploadWorksheetFileRequest $r)
    {
        try {
            $file = $r->file('file');
            $fileName = time() . Str::random(10) . '-file.' . $file->getClientOriginalExtension();
            Storage::putFileAs('worksheets/tmp', $file, $fileName);

            DB::table('temporary_files')->insert([
                'file_name' => $fileName,
                'created_at' => now(),
            ]);

            return response([
                'file' => $fileName
            ], 200);
        } catch (Exception $e) {
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public function create(WorksheetCreateRequest $r)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $data = $r->validated();
            // $data['user_id'] = $user->id;
            $category = $r->category;
            $data['category_id'] = $category->id;

            // $imageArr = [];
            // if ($r->hasFile('images')) {
            //     // foreach ($r->file('images') as $file) {
            //     //     $image = $file;
            //     $image = $r->file('image');
            //     $imageName = time() . bin2hex(random_bytes(5)) . '-image';
            //     Storage::disk('worksheets')->put('/' . $imageName, $image->get());
            //     $imageArr[] = $imageName;
            // }
            // }
            // $data['images'] = ($imageArr);
            // $slug = bin2hex(random_bytes(5));

            if (!$r->slug) {
                $slug = Str::slug($r->name);
                $data['slug'] = $slug;
            }
            // if ($r->hasFile('file_path')) {
            //     $file = $r->file('file_path');
            //     $fileName = time() . bin2hex(random_bytes(5)) . '-file';
            //     Storage::disk('worksheets')->put('/' . $fileName, $file->get());
            //     $data['file_path'] = $fileName;
            // }

            $worksheet = $category->worksheets()->create($data);

            if ($r->has('banner')) {
                $bannerName = $r->banner;
                Storage::move('worksheets/tmp/' . $bannerName, 'worksheets/' . $bannerName);
                $worksheet->banner = $bannerName;

                DB::table('temporary_files')->where('file_name', $bannerName)->delete();
            }

            if ($r->has('file')) {
                $fileName = $r->file;
                Storage::move('worksheets/tmp/' . $fileName, 'worksheets/' . $fileName);
                $worksheet->file = $fileName;

                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            $worksheet->save();
            DB::commit();

            return response($worksheet, 201);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error($e->getTraceAsString());
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function update(WorksheetUpdateRequest $r)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $data = $r->validated();
            $worksheet = $r->worksheet;

            if ($r->has('banner')) {
                if ($worksheet->banner) {
                    Storage::delete('worksheets/' . $worksheet->banner);
                }
                Storage::move('worksheets/tmp/' . $data['banner'], 'worksheets/' . $data['banner']);
            }

            if ($r->has('file')) {
                if ($worksheet->file) {
                    Storage::delete('worksheets/' . $worksheet->file);
                }
                Storage::move('worksheets/tmp/' . $data['file'], 'worksheets/' . $data['file']);
            }

            if (!$r->slug) {
                $slug = Str::slug($r->name);
                $data['slug'] = $slug;
            }

            $worksheet->update($data);
            DB::commit();

            return response($worksheet, 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getTraceAsString());
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }


    public function delete(WorksheetDeleteRequest $r)
    {
        try {
            DB::beginTransaction();
            $r->worksheet->delete();
            DB::commit();
            return response(['message' => 'با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است !'], 500);
        }
    }
}
