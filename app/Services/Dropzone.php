<?php


    namespace App\Services\Dropzone;


    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    class Dropzone
    {
        private Request $request;

        public function __construct(Request $request)
        {
            $this->request = $request;
        }

        /**
         * @param $model
         * @param $mediaKey
         * @param $param
         * @return JsonResponse
         */
        public function getMedia($model, $mediaKey, $param): JsonResponse
        {
            $models = $model::findOrFail($this->request->get($param));
            $medias = $models->getMedia($mediaKey);

            return \response()->json($medias);
        }

        /**
         * @return JsonResponse
         */
        public function storeMedia(): JsonResponse
        {
            $path = storage_path('tmp/uploads');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $this->request->file('file');

            $name = trim($file->getClientOriginalName());

            $file->move($path, $name);

            return response()->json([
                'name'          => $name,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        /**
         * @param $model
         * @param $requestInput
         * @param $mediaId
         * @param null $library
         * @return JsonResponse
         */
        public function deleteMedia($model, $requestInput, $mediaId, $library = null): JsonResponse
        {
            $file = $this->request->get($requestInput);
            $id = $this->request->get($mediaId);

            if (!$id) {
                if (\Storage::disk('tmp')->exists('uploads/' . $file)) {
                    \Storage::disk('tmp')->delete('uploads/' . $file);
                }
            } else {
                if ($library === 'spatie') {
                    $model::findByUuid($id)->delete();
                } else {
                    $model::findOrFail($id)->delete();
                }
            }

            return response()->json();
        }
    }
