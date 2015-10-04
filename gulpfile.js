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

elixir(function(mix) {
    mix.less('app.less', 'resources/assets/css');

    mix.styles([
    	'libs/bootstrap.min.css',
    	'libs/jquery.bootgrid.min.css',
    	'libs/select2.min.css',
    	'app.css'
    ]);

    mix.scripts([
    	'libs/jquery.min.js',
    	'libs/bootstrap.min.js',
    	'libs/select2.min.js',
    	'libs/jquery.bootgrid.min.js'
    ])
    .scripts(['app.js'], 'public/js/app.js');

    mix.version(['public/js/app.js']);

});
