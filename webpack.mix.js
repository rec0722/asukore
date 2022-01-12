const { sass } = require('laravel-mix');
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

mix
    .js('resources/js/app.js', 'public/js')
    .sourceMaps()
    .autoload({
        jquery: ['$', 'window.jQuery']
    });

mix
    .scripts([
            'resources/js/bundle.js'
        ],
        'public/js/bundle.js'
    )
    .scripts([
            'resources/js/fullcalendar.js'
        ],
        'public/js/fullcalendar.js'
    );

mix
    .sass('resources/sass/style.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css', []);

//.sass('resources/sass/app.scss', 'public/css');
//.postCss('resources/css/app.css', 'public/css', []);