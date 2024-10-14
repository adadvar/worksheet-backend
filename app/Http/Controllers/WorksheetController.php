<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\Worksheet\WorksheetCreateRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
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

        $query = Advert::query();

        $query->where($conditions);
        foreach ($whereIns as $column => $values) {
            $query->whereIn($column, $values);
        }

        $query->with(['user', 'category']);

        if ($r->o == 'n' || $r->o == null) $query->orderBy('id', 'desc');
        if ($r->o == 'pa') $query->orderBy('price', 'asc');
        if ($r->o == 'pd') $query->orderBy('price', 'desc');

        $perPage = $r->per_page ?? 10;
        $adverts = $query->paginate($perPage);

        return response($adverts);
    }

    public function show(Request $r)
    {
        $advert = Advert::where('slug_url', $r->id_slug)
            ->orWhere('id', $r->id_slug)
            ->where('state', 'accepted')
            ->firstOrFail();
        event(new VisitAdvert($advert));
        $advert = $advert->load('user', 'category');
        return $advert;
    }

    public function create(WorksheetCreateRequest $r)
    {
        try {
            $user = auth()->user();
            $data = $r->validated();
            // $data['user_id'] = $user->id;
            $category = $r->category;
            $data['category_id'] = $category->id;
            $imageArr = [];
            if (!empty($r->file('images'))) {
                foreach ($r->file('images') as $file) {
                    $image = $file;
                    $imageName = time() . bin2hex(random_bytes(5)) . '-image';
                    Storage::disk('worksheets')->put('/' . $imageName, $image->get());
                    $imageArr[] = $imageName;
                }
            }
            $data['images'] = ($imageArr);
            // $slug = bin2hex(random_bytes(5));
            $slug = Str::slug($r->name);
            $data['slug'] = $slug;
            $worksheet = $category->worksheets()->create($data);
            return response($worksheet, 200);
        } catch (Exception $e) {

            Log::error($e->getTraceAsString());
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function update(AdvertUpdateRequest $r)
    {

        try {
            $user = auth()->user();
            $advert = $r->advert;
            $category = $advert->category;
            $data = $r->validated();
            $data['user_id'] = $user->id;
            $data['category_id'] = $category->id;
            $imageArr = [];
            if (!empty($r->file('images'))) {
                foreach ($r->file('images') as $file) {
                    $image = $file;
                    $imageName = time() . bin2hex(random_bytes(5)) . '-image';
                    Storage::disk('adverts')->put('/' . $user->id . '/' . $imageName, $image->get());
                    $imageArr[] = $imageName;
                }
            }
            $data['images'] = ($imageArr);
            if (isset($r->title) && isset($r->city_id)) {
                $cat_title = $category->title;
                $city = City::find($r->city_id);
                $slug_url = str_replace(' ', '-', env('APP_NAME') . ' ' . $city->name . ' ' . $r->title . ' ' . $cat_title . ' ' . bin2hex(random_bytes(4)));
                $slug = bin2hex(random_bytes(5));
                $data['slug'] = $slug;
                $data['slug_url'] = $slug_url;
            }


            tap($advert)->update($data);
            return response($advert, 200);
        } catch (Exception $e) {
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }


    public function delete(AdvertDeleteRequest $r)
    {
        try {
            DB::beginTransaction();
            $r->advert->delete();
            DB::commit();
            return response(['message' => 'با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است !'], 500);
        }
    }
}
