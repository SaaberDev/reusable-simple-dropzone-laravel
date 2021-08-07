<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dropzone\Dropzone;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * @param Dropzone $dropzone
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getMedia(Dropzone $dropzone, Request $request)
    {
        if ($request->get('request') === 'singleUploader') {
            return $dropzone->getMedia(Service::class,'service_thumb', 'id');
        }

        if ($request->get('request') === 'multipleUploader'){
            return $dropzone->getMedia(Service::class,'service', 'id');
        }
    }

    /**
     * @param Dropzone $dropzone
     * @return JsonResponse
     */
    public function storeMedia(Dropzone $dropzone): JsonResponse
    {
        return $dropzone->storeMedia();
    }

    /**
     * @param Dropzone $dropzone
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyMedia(Dropzone $dropzone, Request $request): JsonResponse
    {
        if ($request->input('single_media')) {
            return $dropzone->deleteMedia(Media::class,'single_media', 'uuid', 'spatie');
        }
        return $dropzone->deleteMedia(Media::class,'multiple_media', 'uuid', 'spatie');
    }
}
