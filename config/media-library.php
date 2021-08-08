<?php

return [
    /**
     * --------------------------------------------------------------------
     *                              NOTICE
     * --------------------------------------------------------------------
     *
     * This is demo file is for instruction. Your spatie config file will contain more options.
     * From there find "path_generator" and change from default to our own CustomPathGenerator.
     */
    'path_generator' => \App\Services\MediaLibrary\CustomPathGenerator::class,
];
