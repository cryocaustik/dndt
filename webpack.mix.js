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

mix.copy('vendor/almasaeed2010/adminlte/dist/css', 'public/adminlte/css');
mix.copy('vendor/almasaeed2010/adminlte/dist/js', 'public/adminlte/js');
mix.copy('vendor/almasaeed2010/adminlte/plugins', 'public/adminlte/plugins');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
