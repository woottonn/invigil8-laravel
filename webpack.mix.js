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
    .sass('resources/sass/app.scss', 'public/css');

/*mix.copy('node_modules/@fortawesome/fontawesome-free/', 'public/webfonts')*/

// better option for now, although you have to include individually on the blade templates
mix.copy(
    'resources/js/custom/*.js',
    'public/js/custom/');


/* takes all JS files and compiles them into one file - good, but can be issues with duplicate IDs
mix.scripts(
    'resources/js/custom/*.js',
    'public/js/custom.js');

*/


