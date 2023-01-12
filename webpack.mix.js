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
    .setPublicPath('public')
    /* JS */
    .js('resources/js/vue.js', 'vue.js')

    /* Options */
    .options({
        processCssUrls: false
    })
    .disableNotifications()
    .vue({ version: 3 })
    .version();
