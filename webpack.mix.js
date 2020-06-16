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

mix.js('resources/js/app.js','public/js')
	.js(['resources/js/bootstrap-datepicker.es.min.js',
	'resources/js/bootstrap-datepicker.min.js',
	'resources/js/datatables.min.js'],'public/otros.js')
	.styles(['resources/css/datatables.min.css',
		'resources/css/all.min.css',
		'resources/css/bootstrap-datepicker3.min.css',
		'resources/css/font.css',
		'resources/css/toastr.min.css'],
		'public/otros.css')
   	.sass('resources/sass/app.scss', 'public/css');
