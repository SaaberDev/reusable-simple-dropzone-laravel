<?php
return [
  /**
   * --------------------------------------------------------------------
   *                              NOTICE
   * --------------------------------------------------------------------
   *
   * This demo file is for instruction. For dropzone tmp file upload.
   * From there find "disks" and add new disk here.
   */
  
  'disks' => [
        'tmp' => [
            'driver' => 'local',
            'root' => storage_path('tmp'),
        ]
    ]
];
