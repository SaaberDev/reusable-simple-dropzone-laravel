@extends('layouts.app')
@section('title', 'Edit Dropzone')

@push('styles')
    {{-- Internal Styles --}}
    <link rel="stylesheet" href="{{ mix('_assets/plugins/dropzone/css/dropzone.css') }}">
    <style>
        .dropzone {
            min-height: 140px;
            background-color: #e8efe87a;
            padding: 0;
            border: 1px dashed #038D08!important;
        }

        .dropzone .dz-message {
            text-align: center;
            margin: 0;
            font-size: 16px;
        }

        .dropzone .dz-message .dz-button {
            background: none;
            color: inherit;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
            font-weight: 300;
        }

        .dropzone .dz-preview .dz-error-message {
            pointer-events: none;
            z-index: 1000;
            position: absolute;
            display: block;
            display: none;
            opacity: 0;
            -webkit-transition: opacity 0.3s ease;
            -moz-transition: opacity 0.3s ease;
            -ms-transition: opacity 0.3s ease;
            -o-transition: opacity 0.3s ease;
            transition: opacity 0.3s ease;
            border-radius: 8px;
            font-size: 13px;
            top: 150px;
            left: -10px;
            width: 140px;
            background: #be2626;
            background: linear-gradient(to bottom, #be2626, #a92222);
            padding: 0.5em 1.2em;
            color: white;
        }

        .dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * {
            cursor: pointer;
            font-weight: 300;
            line-height: 40px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .dz-thumb {
            width: 100%;
            height: 100%;
            object-fit: contain!important;
        }
        .dropzone .dz-preview.dz-image-preview {
            background: transparent;
        }
    </style>
@endpush

@section('content')
<div class="row m-0 justify-content-around mt-4 ">
  <div class="col-md-6">
      {{-- Multiple Upload Edit --}}
      <div class="form-group">
          <label for="">
              <h5>Multiple Upload Edit</h5>
          </label>

          <div class="row">
              <div class="col-sm-12">
                  <div class="form-group">
                      <div class="needsclick dropzone" id="multiple-media-dropzone">
                          <div class="dz-message" data-dz-message>
                              <span>Drop files here or click to upload.</span> <br>
                              <span style="color: #dc3545;font-size: 13px;">Maximum allowed file size 2MB. Allowed file types are jpeg, png.</span>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

      </div>
  </div>


  {{-- Single Upload Edit --}}
  <div class="col-md-6">
      <div class="form-group">
          <label for="">
              <h5>Single Upload Edit</h5>
          </label>

          <div class="row">
              <div class="col-sm-12">
                  <div class="form-group">
                      <div class="needsclick dropzone" id="single-media-dropzone">
                          <div class="dz-message" data-dz-message>
                              <span>Drop files here or click to upload.</span> <br>
                              <span style="color: #dc3545;font-size: 13px;">Maximum allowed file size 2MB. Allowed file types are jpeg, png.</span>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

      </div>
  </div>
</div>
@endsection

@push('scripts')
{{-- Internal Scripts --}}
<script src="{{ mix('_assets/plugins/dropzone/js/dropzone.js') }}"></script>
@include('plugins.dropzone.edit.single', [
        'dropzone' => Str::camel('single-media-dropzone'), // dropzone div id
        'getRequestParam' => 'service_thumb',
        'fileInputName' => 'single_media',
        'get' => route('getMedia'),
        'store' => route('storeMedia'),
        'delete' => route('deleteMedia'),
        'model' => $service, // your model name for query
        'maxFilesize' => 2,
        'maxFiles' => 1,
        'acceptedFiles' => 'image/jpeg, image/png',
    ])

@include('plugins.dropzone.edit.multiple', [
    'dropzone' => Str::camel('multiple-media-dropzone'), // dropzone div id
    'getRequestParam' => 'service',
    'fileInputName' => 'multiple_media',
    'get' => route('getMedia'),
    'store' => route('storeMedia'),
    'delete' => route('deleteMedia'),
    'model' => $service, // your model name for query
    'maxFilesize' => 2,
    'acceptedFiles' => 'image/jpeg, image/png',
])
@endpush
