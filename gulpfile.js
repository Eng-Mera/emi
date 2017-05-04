var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

    mix.styles([
        'bootstrap.css',
        'AdminLTE.css',
        'bootstrap-datepicker3.min.css',
        'skins/skin-blue.css',
        'dataTables.bootstrap.min.css',
        'multi-select.css',
        'blue.css',
        'blueimp-gallery.min.css',
        'fontawesome-iconpicker.min.css',
        'custom.css'
    ]);

    mix.scripts([
        'resumable.js',
        'jquery.min.js',
        'bootstrap.min.js',
        'bootstrap-datepicker.min.js',
        'jquery.dataTables.min.js',
        'dataTables.bootstrap.min.js',
        'jquery.highlight.js',
        'jsrender.min.js',
        'jquery.multi-select.js',
        'jquery.quicksearch.js',
        'app.min.js',
        'jquery.blueimp-gallery.min.js',
        'fontawesome-iconpicker.js',
        'custom.js',
        'plugins/jQueryUI/jquery-ui.min.js',
        'admin-coupons.js'
    ]);

    mix.version(['css/all.css', 'js/all.js']);

});