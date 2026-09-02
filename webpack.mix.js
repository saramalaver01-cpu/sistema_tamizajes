const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);


mix.js('resources/js/violentometro.js', 'public/js')
   .css('resources/css/violentometro.css', 'public/css');


mix.js('resources/js/Bienestar-emocional.js', 'public/js')
   .css('resources/css/Bienestar-emocional.css', 'public/css');


mix.js('resources/js/carga-academica.js', 'public/js')
   .css('resources/css/carga-academica.css', 'public/css');

mix.js('resources/js/login.js', 'public/js')
   .css('resources/css/login.css', 'public/css');

mix.js('resources/js/admin.js', 'public/js')
   .css('resources/css/admin.css', 'public/css');