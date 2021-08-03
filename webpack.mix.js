const mix = require('laravel-mix');
/*
|--------------------------------------------------------------------------
| Plugins
|--------------------------------------------------------------------------
*/
// Dropzone
mix.styles([
  'resources/_assets/plugins/dropzone/css/dropzone.css'
], 'public/_assets/plugins/dropzone/css/dropzone.css')
.js([
   'resources/_assets/plugins/dropzone/js/dropzone-amd-module.js'
], 'public/_assets/plugins/dropzone/js/dropzone.js');
