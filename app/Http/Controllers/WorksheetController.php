<?php

namespace App\Http\Controllers;

use App\Events\VisitWorksheet;
use App\Http\Requests\Worksheet\UploadWorksheetBannersRequest;
use App\Http\Requests\Worksheet\UploadWorksheetFileRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Worksheet\WorksheetCreateRequest;
use App\Http\Requests\Worksheet\WorksheetDeleteRequest;
use App\Http\Requests\Worksheet\WorksheetDownloadRequest;
use App\Http\Requests\Worksheet\WorksheetLikeRequest;
use App\Http\Requests\Worksheet\WorksheetUnlikeRequest;
use App\Http\Requests\Worksheet\WorksheetUpdateRequest;
use App\Models\Category;
use App\Models\Worksheet;
use App\Models\WorksheetFavourite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Barryvdh\DomPDF\Facade as PDF;

class WorksheetController extends Controller
{
    public function list(Request $r)
    {
        $whereIns = [];
        $conditions = [];
        $categoryRequests = null;

        if ($r->param1) {
            $categoryRequests = Category::where(['type' => 'grade', 'slug' => $r->param1])->get();
        }

        if ($r->param2) {
            $categoryRequests = Category::where('slug', $r->param2)
                ->where(function ($query) use ($r) {
                    $query->whereHas('parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    })->orWhereHas('parent.parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    });
                })->get();
        }

        if ($r->param3) {
            $categoryRequests = Category::where(['type' => 'topic', 'slug' => $r->param3])
                ->whereHas('parent', function ($query) use ($r) {
                    $query->where('slug', $r->param2);
                })
                ->whereHas('parent.parent', function ($query) use ($r) {
                    $query->where('slug', $r->param1);
                })->get();
        }

        if ($categoryRequests) {
            $ids = [];
            foreach ($categoryRequests as $categoryRequest) {
                $ids = array_merge($ids, Category::extractChildrenIds($categoryRequest));
            }
            $ids = array_unique($ids);

            $whereIns['grade_id'] = $ids;
            $whereIns['subject_id'] = $ids;
            $whereIns['topic_id'] = $ids;
        }

        // $prices = explode('-', $r->price);
        // if ($prices[0]) $conditions[] = ['price', '>=', $prices[0]];
        // if ($prices[1]) $conditions[] = ['price', '<=', $prices[1]];

        $query = Worksheet::query();

        if ($r->search) {
            $searchTerm = $r->search;
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('grade', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('subject', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('topic', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $query->where($conditions);
        foreach ($whereIns as $column => $values) {
            $query->orWhereIn($column, $values);
        }

        $query->with(['grade', 'subject', 'topic']);
        $query->withCount('viewers as views_count');
        if ($r->sortBy === 'date-desc' || !$r->sortBy) $query->orderBy('updated_at', 'desc');
        if ($r->sortBy === 'view-desc') $query->orderBy('views_count', 'desc');
        if ($r->sortBy === 'order-desc') $query->orderBy('id', 'desc');
        if ($r->sortBy === 'price-asc') $query->orderBy('price', 'asc');
        if ($r->sortBy === 'price-desc') $query->orderBy('price', 'desc');


        // if ($r->o == 'n' || $r->o == null) $query->orderBy('id', 'desc');
        // if ($r->o == 'pa') $query->orderBy('price', 'asc');
        // if ($r->o == 'pd') $query->orderBy('price', 'desc');

        $perPage = $r->per_page ?? 10;
        $worksheets = $query->paginate($perPage);

        return response($worksheets);
    }

    public function show(Request $r)
    {
        // $worksheet = Worksheet::where('slug_url', $r->id_slug)
        //     ->orWhere('id', $r->id_slug)
        //     ->where('state', 'accepted')
        //     ->firstOrFail();
        // event(new VisitWorksheet($worksheet));
        // $worksheet = $worksheet->load('user', 'category');

        $worksheet = $r->worksheet;

        if (!$worksheet) {
            return response(['message' => 'Worksheet not found.'], 404);
        }
        event(new VisitWorksheet($worksheet));

        $worksheet->load('grade', 'subject', 'topic');
        $worksheetData = $r->worksheet->toArray();

        $conditions = [
            'worksheet_id' => $r->worksheet->id,
            'user_id' => auth('api')->check() ? auth('api')->id() : null,
        ];

        if (!auth('api')->check()) {
            $conditions['user_ip'] = client_ip();
        }
        $worksheetData['liked'] = WorksheetFavourite::where($conditions)->count() > 0;

        if (auth('api')->check()) {
            $worksheetData['is_in_cart'] = $worksheet->cartItems()
                ->where('cart_id', auth('api')->user()->cart->id)
                ->exists();
        } else {
            $worksheetData['is_in_cart'] = false;
        }

        return $worksheetData;
    }

    public static function uploadBanner(UploadWorksheetBannersRequest $r)
    {
        try {
            $banner = $r->file('banner');

            $bannerName = time() . Str::random(10) . '-banner.' . $banner->getClientOriginalExtension();
            // Storage::putFileAs('worksheets/tmp', $banner, $bannerName);
            Storage::disk('banners')->putFileAs('tmp', $banner, $bannerName);

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
            // Storage::putFileAs('worksheets/tmp', $file, $fileName);
            Storage::disk('worksheets')->putFileAs('tmp', $file, $fileName);

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

            if (!$r->slug) {
                $slug = Str::slug($r->name);
                $data['slug'] = $slug;
            }

            $worksheet = Worksheet::create($data);

            if ($r->has('banner') && !empty($r->banner)) {
                $bannerName = $r->banner;
                Storage::disk('banners')->move('tmp/' . $bannerName, $bannerName);

                $worksheet->banner = $bannerName;

                DB::table('temporary_files')->where('file_name', $bannerName)->delete();
            }

            if ($r->has('file_word') && !empty($r->file_word)) {
                $fileName = $r->file_word;
                $path = 'tmp/' . $fileName;

                Storage::disk('worksheets')->move($path, $fileName);

                $worksheet->file_word = $fileName;

                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            if ($r->has('file_pdf') && !empty($r->file_pdf)) {
                $fileName = $r->file_pdf;
                $path = 'tmp/' . $fileName;

                Storage::disk('worksheets')->move($path, $fileName);

                $worksheet->file_pdf = $fileName;

                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            $worksheet->save();
            DB::commit();

            return response(['message' => 'کاربرگ جدید ایجاد شد'], 201);
            // return response($worksheet, 201);
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

            if ($r->has('banner') && !empty($r->banner)) {
                if ($worksheet->banner) {
                    Storage::disk('banners')->delete($worksheet->banner);
                }
                Storage::disk('banners')->move('tmp/' . $r->banner, $r->banner);
                $worksheet->banner = $r->banner;
                DB::table('temporary_files')->where('file_name', $r->banner)->delete();
            }

            if ($r->has('file_word') && !empty($r->file_word)) {
                $fileName = $r->file_word;
                $path = 'tmp/' . $fileName;
                if ($worksheet->file_word) {
                    Storage::disk('worksheets')->delete($worksheet->file_word);
                }
                Storage::disk('worksheets')->move($path, $fileName);
                $worksheet->file_word = $fileName;
                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            if ($r->has('file_pdf') && !empty($r->file_pdf)) {
                $fileName = $r->file_pdf;
                $path = 'tmp/' . $fileName;
                if ($worksheet->file_pdf) {
                    Storage::disk('worksheets')->delete($worksheet->file_pdf);
                }
                Storage::disk('worksheets')->move($path, $fileName);
                $worksheet->file_pdf = $fileName;
                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            if (!$r->slug) {
                $slug = Str::slug($r->name);
                $data['slug'] = $slug;
            }

            $worksheet->update($data);
            DB::commit();

            return response(['message' => 'کاربرگ بروزرسانی شد'], 201);
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
            $worksheet = $r->worksheet;
            if ($worksheet->banner) {
                // Storage::delete('worksheets/' . $worksheet->banner);
                // Storage::disk('worksheets')->delete($worksheet->banner);
                Storage::disk('public')->delete('worksheets/' . $worksheet->banner);
            }

            if ($worksheet->file_word) {
                // Storage::delete('worksheets/' . $worksheet->file);
                Storage::disk('worksheets')->delete($worksheet->file_word);
                Storage::disk('worksheets')->delete($worksheet->file_pdf);
            }
            $worksheet->delete();
            DB::commit();

            return response(['message' => 'با موفقیت حذف شد'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public static function like(WorksheetLikeRequest $r)
    {
        $userId =  $r->user()->id;
        $currentLikesCount = WorksheetFavourite::where('user_id', $userId)->count();
        if ($currentLikesCount >= 30) {
            WorksheetFavourite::where('user_id', $userId)
                ->orderBy('created_at', 'asc')
                ->limit(1)
                ->delete();
        }
        //ابتدا باید وضعیت worksheet به accepted تغییر کند
        WorksheetFavourite::create([
            // 'user_id' => Auth::guard('api')->id(),
            'user_id' => $userId,
            'user_ip' => client_ip(),
            'worksheet_id' => $r->worksheet->id,
        ]);

        return response(['message' => 'به علاقه مندی ها اضافه شد'], 200);
    }

    public static function unlike(WorksheetUnlikeRequest $r)
    {
        $user = auth('api')->user();
        $conditions = [
            'worksheet_id' => $r->worksheet->id,
            'user_id' => $user ? $user->id : null
        ];

        if (empty($user)) {
            $conditions['user_ip'] = client_ip();
        }

        WorksheetFavourite::where($conditions)->delete();
        return response(['message' => 'از علاقه مندی ها حذف شد'], 200);
    }

    public static function favourites(Request $r)
    {
        $user = auth()->user();

        $whereIns = [];
        $conditions = [];
        $categoryRequests = null;

        // فیلتر کردن بر اساس دسته‌بندی
        if ($r->param1) {
            $categoryRequests = Category::where(['type' => 'grade', 'slug' => $r->param1])->get();
        }

        if ($r->param2) {
            $categoryRequests = Category::where('slug', $r->param2)
                ->where(function ($query) use ($r) {
                    $query->whereHas('parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    })->orWhereHas('parent.parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    });
                })->get();
        }

        if ($r->param3) {
            $categoryRequests = Category::where(['type' => 'topic', 'slug' => $r->param3])
                ->whereHas('parent', function ($query) use ($r) {
                    $query->where('slug', $r->param2);
                })
                ->whereHas('parent.parent', function ($query) use ($r) {
                    $query->where('slug', $r->param1);
                })->get();
        }

        if ($categoryRequests) {
            $ids = [];
            foreach ($categoryRequests as $categoryRequest) {
                $ids = array_merge($ids, Category::extractChildrenIds($categoryRequest));
            }
            $ids = array_unique($ids);

            $whereIns['grade_id'] = $ids;
            $whereIns['subject_id'] = $ids;
            $whereIns['topic_id'] = $ids;
        }

        $query = $user->favouriteWorksheets();

        $query->where(function ($query) use ($whereIns) {
            foreach ($whereIns as $column => $values) {
                $query->orWhereIn($column, $values);
            }
        });

        $query->with(['grade', 'subject', 'topic']);
        $query->withCount('viewers as views_count');

        switch ($r->sortBy) {
            case 'view-desc':
                $query->orderBy('views_count', 'desc');
                break;
            case 'order-desc':
                $query->orderBy('id', 'desc');
                break;
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'date-desc':
            default:
                $query->orderBy('updated_at', 'desc');
                break;
        }

        $perPage = $r->per_page ?? 10;
        $favouriteWorksheets = $query->paginate($perPage);

        return response($favouriteWorksheets);
    }

    public static function recents(Request $r)
    {
        $user = auth()->user();
        return response($user->recentWorksheets);
    }

    public function downloadPdf(WorksheetDownloadRequest $r)
    {
        $worksheet = $r->worksheet;

        $filePath = Storage::disk('worksheets')->path($worksheet->file_pdf);
        $fileName = $worksheet->name . '.pdf';

        if (!Storage::disk('worksheets')->exists($worksheet->file_pdf))
            return response()->json(['message' => 'فایل یافت نشد'], 404);

        return response()->download($filePath, $fileName,);
    }

    public function downloadWord(WorksheetDownloadRequest $r)
    {
        $worksheet = $r->worksheet;

        $filePath = Storage::disk('worksheets')->path($worksheet->file_word);
        $fileName = $worksheet->name . '.docx';

        if (!Storage::disk('worksheets')->exists($worksheet->file_pdf))
            return response()->json(['message' => 'فایل یافت نشد'], 404);

        return response()->download($filePath, $fileName);
    }


    private function convertDocxToPdf($docxPath, $pdfPath)
    {
        // $domPdfPath = base_path('vendor/dompdf/dompdf');
        // Settings::setPdfRendererPath($domPdfPath);
        // Settings::setPdfRendererName('DomPDF');
        // $phpWord = IOFactory::load(Storage::disk('worksheets')->path($docxPath));
        // dd(Storage::disk('worksheets')->path($docxPath));
        // $pdfWriter = IOFactory::createWriter($phpWord, "PDF");

        // $pdfWriter->save(Storage::disk('worksheets')->path($pdfPath));

        // Load the DOCX file
        $phpWord = IOFactory::load(Storage::disk('worksheets')->path($docxPath));

        // Convert the DOCX to HTML
        $htmlContent = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getElements')) {
                    foreach ($element->getElements() as $childElement) {
                        if (method_exists($childElement, 'getElements')) {
                            foreach ($childElement->getElements() as $childChildElement) {
                                $htmlContent .= $childChildElement->getHtml();
                            }
                        } else {
                            $htmlContent .= $childElement->getHtml();
                        }
                    }
                } else {
                    $htmlContent .= $element->getHtml();
                }
            }
        }

        // Convert HTML to PDF using Dompdf
        $pdf = PDF::loadHTML($htmlContent);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        // Save the PDF to the specified path
        Storage::disk('worksheets')->put($pdfPath, $pdf->output());
    }
}
