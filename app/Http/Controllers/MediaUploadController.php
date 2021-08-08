<?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Services\Dropzone\Dropzone;
    use App\Services\MediaLibrary\MediaHandler;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    class MediaUploadController extends Controller
    {
        public function store(Request $request, MediaHandler $mediaHandler)
        {
            DB::beginTransaction();
            try {
                $your_model = YourModel::firstOrCreate([
                    'title' => $request->input('service_title'),
                    'status' => $request->input('status'),
                ]);
                // Service Image
                $mediaHandler->uploadMultipleMedia($your_model, 'multiple_media', 'service');
                // Service Thumbnail Image
                $mediaHandler->uploadSingleMedia($your_model, 'single_media', 'service_thumb');

                DB::commit();
                return redirect()->route('somewhere');
            } catch (\Exception $exception) {
                DB::rollBack();
                report($exception->getMessage());
                return redirect()->back();
            }
        }
    }