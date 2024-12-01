<?php

namespace App\Http\Controllers;

use App\Events\VisitProduct;
use App\Http\Requests\Product\UploadProductBannersRequest;
use App\Http\Requests\Product\UploadProductFileRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductDeleteRequest;
use App\Http\Requests\Product\ProductDownloadRequest;
use App\Http\Requests\Product\ProductLikeRequest;
use App\Http\Requests\Product\ProductUnlikeRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFavourite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Barryvdh\DomPDF\Facade as PDF;

class ProductController extends Controller
{
    public function list(Request $r)
    {
        $whereIns = [];
        $conditions = [];
        $categoryRequests = [];

        if ($r->param1) {
            $categoryRequests[] = Category::where(['type' => 'grade', 'slug' => $r->param1])->get();
        }

        if ($r->param2) {
            $categoryRequests[] = Category::where('slug', $r->param2)
                ->where(function ($query) use ($r) {
                    $query->whereHas('parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    })->orWhereHas('parent.parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    });
                })->get();
        }

        if ($r->param3) {
            $categoryRequests[] = Category::where(['type' => 'topic', 'slug' => $r->param3])
                ->whereHas('parent', function ($query) use ($r) {
                    $query->where('slug', $r->param2);
                })
                ->whereHas('parent.parent', function ($query) use ($r) {
                    $query->where('slug', $r->param1);
                })->get();
        }
        // if ($categoryRequests) {
        //     $ids = [];
        //     foreach ($categoryRequests as $categoryRequest) {
        //         $ids = array_merge($ids, Category::extractChildrenIds($categoryRequest));
        //     }
        //     $ids = array_unique($ids);

        //     $whereIns['grade_id'] = $ids;
        //     $whereIns['subject_id'] = $ids;
        //     $whereIns['topic_id'] = $ids;
        // }

        // $prices = explode('-', $r->price);
        // if ($prices[0]) $conditions[] = ['price', '>=', $prices[0]];
        // if ($prices[1]) $conditions[] = ['price', '<=', $prices[1]];

        $query = Product::query();

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

        if ($r->has('type') && $r->type !== 'all') $conditions[] = ['type', '=', $r->type];
        $query->where($conditions);
        foreach ($categoryRequests as $index => $categoryRequest) {
            if ($categoryRequest->isNotEmpty()) {
                $column = match ($index) {
                    0 => 'grade_id',
                    1 => 'subject_id',
                    2 => 'topic_id',
                    default => null,
                };
                if ($column) {
                    $query->whereIn($column, $categoryRequest->pluck('id')->toArray());
                }
            }
        }

        // foreach ($whereIns as $column => $values) {
        //     $query->orWhereIn($column, $values);
        // }

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

        $perPage = $r->per_page ?? 9;
        $products = $query->paginate($perPage);

        return response($products);
    }

    public function show(Request $r)
    {
        // $product = Product::where('slug_url', $r->id_slug)
        //     ->orWhere('id', $r->id_slug)
        //     ->where('state', 'accepted')
        //     ->firstOrFail();
        // event(new VisitProduct($product));
        // $product = $product->load('user', 'category');

        $product = $r->product;

        if (!$product) {
            return response(['message' => 'Product not found.'], 404);
        }
        event(new VisitProduct($product));

        $product->load('grade', 'subject', 'topic');
        $productData = $r->product->toArray();

        $conditions = [
            'product_id' => $r->product->id,
            'user_id' => auth('api')->check() ? auth('api')->id() : null,
        ];

        if (!auth('api')->check()) {
            $conditions['user_ip'] = client_ip();
        }
        $productData['liked'] = ProductFavourite::where($conditions)->count() > 0;

        if (auth('api')->check()) {
            $productData['is_in_cart'] = $product->cartItems()
                ->where('cart_id', auth('api')->user()->cart->id)
                ->exists();
        } else {
            $productData['is_in_cart'] = false;
        }

        return $productData;
    }

    public static function uploadBanner(UploadProductBannersRequest $r)
    {
        try {
            $banner = $r->file('banner');

            $bannerName = time() . Str::random(10) . '-banner.' . $banner->getClientOriginalExtension();
            // Storage::putFileAs('products/tmp', $banner, $bannerName);
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

    public static function uploadFile(UploadProductFileRequest $r)
    {
        try {
            $file = $r->file('file');
            $fileName = time() . Str::random(10) . '-file.' . $file->getClientOriginalExtension();
            // Storage::putFileAs('products/tmp', $file, $fileName);
            Storage::disk('products')->putFileAs('tmp', $file, $fileName);

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

    public function create(ProductCreateRequest $r)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $data = $r->validated();

            if (!$r->slug) {
                $slug = Str::slug($r->name);
                $data['slug'] = $slug;
            }

            $product = Product::create($data);

            if ($r->has('banner') && !empty($r->banner)) {
                $bannerName = $r->banner;
                Storage::disk('banners')->move('tmp/' . $bannerName, $bannerName);

                $product->banner = $bannerName;

                DB::table('temporary_files')->where('file_name', $bannerName)->delete();
            }

            if ($r->has('file_word') && !empty($r->file_word)) {
                $fileName = $r->file_word;
                $path = 'tmp/' . $fileName;

                Storage::disk('products')->move($path, $fileName);

                $product->file_word = $fileName;

                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            if ($r->has('file_pdf') && !empty($r->file_pdf)) {
                $fileName = $r->file_pdf;
                $path = 'tmp/' . $fileName;

                Storage::disk('products')->move($path, $fileName);

                $product->file_pdf = $fileName;

                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            $product->save();
            DB::commit();

            return response(['message' => 'کاربرگ جدید ایجاد شد'], 201);
            // return response($product, 201);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error($e->getTraceAsString());
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function update(ProductUpdateRequest $r)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $data = $r->validated();
            $product = $r->product;

            if ($r->has('banner') && !empty($r->banner)) {
                if ($product->banner) {
                    Storage::disk('banners')->delete($product->banner);
                }
                Storage::disk('banners')->move('tmp/' . $r->banner, $r->banner);
                $product->banner = $r->banner;
                DB::table('temporary_files')->where('file_name', $r->banner)->delete();
            }

            if ($r->has('file_word') && !empty($r->file_word)) {
                $fileName = $r->file_word;
                $path = 'tmp/' . $fileName;
                if ($product->file_word) {
                    Storage::disk('products')->delete($product->file_word);
                }
                Storage::disk('products')->move($path, $fileName);
                $product->file_word = $fileName;
                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            if ($r->has('file_pdf') && !empty($r->file_pdf)) {
                $fileName = $r->file_pdf;
                $path = 'tmp/' . $fileName;
                if ($product->file_pdf) {
                    Storage::disk('products')->delete($product->file_pdf);
                }
                Storage::disk('products')->move($path, $fileName);
                $product->file_pdf = $fileName;
                DB::table('temporary_files')->where('file_name', $fileName)->delete();
            }

            if (!$r->slug) {
                $slug = Str::slug($r->name);
                $data['slug'] = $slug;
            }

            $product->update($data);
            DB::commit();

            return response(['message' => 'کاربرگ بروزرسانی شد'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getTraceAsString());
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }


    public function delete(ProductDeleteRequest $r)
    {
        try {
            DB::beginTransaction();
            $product = $r->product;
            if ($product->banner) {
                // Storage::delete('products/' . $product->banner);
                // Storage::disk('products')->delete($product->banner);
                Storage::disk('public')->delete('products/' . $product->banner);
            }

            if ($product->file_word) {
                // Storage::delete('products/' . $product->file);
                Storage::disk('products')->delete($product->file_word);
                Storage::disk('products')->delete($product->file_pdf);
            }
            $product->delete();
            DB::commit();

            return response(['message' => 'با موفقیت حذف شد'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public static function like(ProductLikeRequest $r)
    {
        $userId =  $r->user()->id;
        $currentLikesCount = ProductFavourite::where('user_id', $userId)->count();
        if ($currentLikesCount >= 30) {
            ProductFavourite::where('user_id', $userId)
                ->orderBy('created_at', 'asc')
                ->limit(1)
                ->delete();
        }
        //ابتدا باید وضعیت product به accepted تغییر کند
        ProductFavourite::create([
            // 'user_id' => Auth::guard('api')->id(),
            'user_id' => $userId,
            'user_ip' => client_ip(),
            'product_id' => $r->product->id,
        ]);

        return response(['message' => 'به علاقه مندی ها اضافه شد'], 200);
    }

    public static function unlike(ProductUnlikeRequest $r)
    {
        $user = auth('api')->user();
        $conditions = [
            'product_id' => $r->product->id,
            'user_id' => $user ? $user->id : null
        ];

        if (empty($user)) {
            $conditions['user_ip'] = client_ip();
        }

        ProductFavourite::where($conditions)->delete();
        return response(['message' => 'از علاقه مندی ها حذف شد'], 200);
    }

    public static function favourites(Request $r)
    {
        $user = auth()->user();

        $whereIns = [];
        $conditions = [];
        $categoryRequests = [];

        // فیلتر کردن بر اساس دسته‌بندی
        if ($r->param1) {
            $categoryRequests[] = Category::where(['type' => 'grade', 'slug' => $r->param1])->get();
        }

        if ($r->param2) {
            $categoryRequests[] = Category::where('slug', $r->param2)
                ->where(function ($query) use ($r) {
                    $query->whereHas('parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    })->orWhereHas('parent.parent', function ($query) use ($r) {
                        $query->where('slug', $r->param1);
                    });
                })->get();
        }

        if ($r->param3) {
            $categoryRequests[] = Category::where(['type' => 'topic', 'slug' => $r->param3])
                ->whereHas('parent', function ($query) use ($r) {
                    $query->where('slug', $r->param2);
                })
                ->whereHas('parent.parent', function ($query) use ($r) {
                    $query->where('slug', $r->param1);
                })->get();
        }

        // if ($categoryRequests) {
        //     $ids = [];
        //     foreach ($categoryRequests as $categoryRequest) {
        //         $ids = array_merge($ids, Category::extractChildrenIds($categoryRequest));
        //     }
        //     $ids = array_unique($ids);

        //     $whereIns['grade_id'] = $ids;
        //     $whereIns['subject_id'] = $ids;
        //     $whereIns['topic_id'] = $ids;
        // }

        $query = $user->favouriteProducts();

        if ($r->type && $r->type != 'all') $conditions[] = ['type', '=', $r->type];

        $query->where($conditions);

        // $query->where(function ($query) use ($whereIns) {
        //     foreach ($whereIns as $column => $values) {
        //         $query->orWhereIn($column, $values);
        //     }
        // });

        foreach ($categoryRequests as $index => $categoryRequest) {
            if ($categoryRequest->isNotEmpty()) {
                $column = match ($index) {
                    0 => 'grade_id',
                    1 => 'subject_id',
                    2 => 'topic_id',
                    default => null,
                };
                if ($column) {
                    $query->whereIn($column, $categoryRequest->pluck('id')->toArray());
                }
            }
        }

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

        $perPage = $r->per_page ?? 9;
        $favouriteProducts = $query->paginate($perPage);

        return response($favouriteProducts);
    }

    public static function recents(Request $r)
    {
        $user = auth()->user();
        return response($user->recentProducts);
    }

    public function downloadPdf(ProductDownloadRequest $r)
    {
        $product = $r->product;

        $filePath = Storage::disk('products')->path($product->file_pdf);
        $fileName = $product->name . '.pdf';

        if (!Storage::disk('products')->exists($product->file_pdf))
            return response()->json(['message' => 'فایل یافت نشد'], 404);

        return response()->download($filePath, $fileName,);
    }

    public function downloadWord(ProductDownloadRequest $r)
    {
        $product = $r->product;

        $filePath = Storage::disk('products')->path($product->file_word);
        $fileName = $product->name . '.docx';

        if (!Storage::disk('products')->exists($product->file_pdf))
            return response()->json(['message' => 'فایل یافت نشد'], 404);

        return response()->download($filePath, $fileName);
    }


    private function convertDocxToPdf($docxPath, $pdfPath)
    {
        // $domPdfPath = base_path('vendor/dompdf/dompdf');
        // Settings::setPdfRendererPath($domPdfPath);
        // Settings::setPdfRendererName('DomPDF');
        // $phpWord = IOFactory::load(Storage::disk('products')->path($docxPath));
        // dd(Storage::disk('products')->path($docxPath));
        // $pdfWriter = IOFactory::createWriter($phpWord, "PDF");

        // $pdfWriter->save(Storage::disk('products')->path($pdfPath));

        // Load the DOCX file
        $phpWord = IOFactory::load(Storage::disk('products')->path($docxPath));

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
        Storage::disk('products')->put($pdfPath, $pdf->output());
    }
}
