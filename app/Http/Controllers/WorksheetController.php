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
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;

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

        // $conditions['state'] = 'accepted';

        if ($categoryRequest) {
            $ids = (Category::extractChildrenIds($categoryRequest));
            $whereIns['category_id'] = $ids;
        }

        $query = Worksheet::query();

        $query->where($conditions);
        foreach ($whereIns as $column => $values) {
            $query->whereIn($column, $values);
        }

        $query->with(['grade', 'subject', 'topic']);


        if ($r->o == 'n' || $r->o == null) $query->orderBy('id', 'desc');
        if ($r->o == 'pa') $query->orderBy('price', 'asc');
        if ($r->o == 'pd') $query->orderBy('price', 'desc');

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

        $worksheet = Worksheet::find($r->worksheet);

        if (!$worksheet) {
            return response(['message' => 'Worksheet not found.'], 404);
        }
        $worksheet->load('grade', 'subject', 'topic');

        return $worksheet;
    }

    public static function uploadBanner(UploadWorksheetBannersRequest $r)
    {
        try {
            $banner = $r->file('banner');

            $bannerName = time() . Str::random(10) . '-banner.' . $banner->getClientOriginalExtension();
            // Storage::putFileAs('worksheets/tmp', $banner, $bannerName);
            Storage::disk('worksheets')->putFileAs('tmp', $banner, $bannerName);

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
                // Storage::move('worksheets/tmp/' . $bannerName, 'worksheets/' . $bannerName);
                Storage::disk('worksheets')->move('tmp/' . $bannerName, $bannerName);

                $worksheet->banner = $bannerName;

                DB::table('temporary_files')->where('file_name', $bannerName)->delete();
            }

            if ($r->has('file') && !empty($r->file)) {
                $fileName = $r->file;
                $docxPath = 'tmp/' . $fileName;
                $pdfPath = str_replace('.docx', '.pdf', $fileName);

                $this->convertDocxToPdf($docxPath, $pdfPath);

                // Storage::move('worksheets/tmp/' . $fileName, 'worksheets/' . $fileName);
                Storage::disk('worksheets')->move($docxPath, $fileName);

                $worksheet->file_word = $fileName;
                $worksheet->file_pdf = $fileName;

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

            if ($r->has('banner') && !empty($r->banner)) {
                if ($worksheet->banner) {
                    // Storage::delete('worksheets/' . $worksheet->banner);
                    Storage::disk('worksheets')->delete($worksheet->banner);
                }
                // Storage::move('worksheets/tmp/' . $data['banner'], 'worksheets/' . $data['banner']);
                Storage::disk('worksheets')->move('tmp/' . $data['banner'], $data['banner']);
            }

            if ($r->has('file') && !empty($r->file)) {
                if ($worksheet->file_word) {
                    // Storage::delete('worksheets/' . $worksheet->file);
                    Storage::disk('worksheets')->delete($worksheet->file_word);
                    Storage::disk('worksheets')->delete($worksheet->file_pdf);
                }
                // Storage::move('worksheets/tmp/' . $data['file'], 'worksheets/' . $data['file']);
                $fileName = $r->file;
                $docxPath = 'tmp/' . $fileName;
                $pdfPath = str_replace('.docx', '.pdf', $fileName);

                // Convert DOCX to PDF
                $this->convertDocxToPdf($docxPath, $pdfPath);

                // Move DOCX file
                Storage::disk('worksheets')->move($docxPath, $fileName);

                $worksheet->file_word = $fileName;
                $worksheet->pdf_file = $pdfPath;

                DB::table('temporary_files')->where('file_name', $fileName)->delete();
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
            $worksheet = $r->worksheet;
            if ($worksheet->banner) {
                // Storage::delete('worksheets/' . $worksheet->banner);
                Storage::disk('worksheets')->delete($worksheet->banner);
            }

            if ($worksheet->file_word) {
                // Storage::delete('worksheets/' . $worksheet->file);
                Storage::disk('worksheets')->delete($worksheet->file_word);
                Storage::disk('worksheets')->delete($worksheet->file_pdf);
            }
            $worksheet->delete();
            DB::commit();

            return response(['message' => 'با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است !'], 500);
        }
    }

    private function convertDocxToPdf($docxPath, $pdfPath)
    {
        // Convert DOCX to PDF
        $phpWord = IOFactory::load(Storage::disk('worksheets')->path($docxPath));
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

        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Storage::disk('worksheets')->put($pdfPath, $dompdf->output());
    }
}
