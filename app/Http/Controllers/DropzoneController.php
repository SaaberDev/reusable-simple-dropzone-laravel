<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dropzone\Dropzone;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * --------------------------------------------------------------------
     *                              NOTICE
     * --------------------------------------------------------------------
     *
     * This is a demo controller to demonstrate the example,
     * how you should call your methods for dropzone routes.
     * Please make sure to modify according to your system logic if needed.
     */

    /**
     * @param Dropzone $dropzone
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getMedia(Dropzone $dropzone, Request $request)
    {
        // 'request' is the parameter which is coming from the ajax response
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
