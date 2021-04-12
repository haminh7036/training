const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .css('resources/css/app.css','public/css')
    .css('resources/css/admin/admin.css', 'public/css')
    .js('node_modules/popper.js/dist/popper.js', 'public/js')
    .js('resources/js/admin/user/index.js', 'public/js/admin/user')
    .js('resources/js/admin/order/customer/index.js', 'public/js/admin/order/customer')
    .js('resources/js/admin/product/index.js', 'public/js/admin/product')
    .sourceMaps();
