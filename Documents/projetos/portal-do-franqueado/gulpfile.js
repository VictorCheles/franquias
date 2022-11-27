var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var bower = '../../bower_components/';
    var admin = 'AdminLTE/';

    mix.sass([
        'app.scss'
    ], 'public/css/custom.css');

    mix.styles([
            bower + admin + 'bootstrap/css/bootstrap.min.css',
            bower + 'font-awesome/css/font-awesome.min.css',
            bower + 'Ionicons/css/ionicons.min.css',
            bower + admin + 'plugins/select2/select2.min.css',
            bower + admin + 'plugins/datepicker/datepicker3.css',
            bower + admin + 'plugins/fullcalendar/fullcalendar.min.css',
            bower + admin + 'plugins/datepicker/datepicker3.css',
            bower + admin + 'plugins/colorpicker/bootstrap-colorpicker.min.css',
            bower + admin + 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
            bower + admin + 'plugins/datatables/dataTables.bootstrap.css',
            bower + 'sweetalert/dist/sweetalert.css',
            bower + 'font-awesome-animation/dist/font-awesome-animation.min.css',
            bower + 'chosen/chosen.css',
            bower + 'datetimepicker/build/jquery.datetimepicker.min.css',
            bower + 'jquery-colorbox/example3/colorbox.css',
            bower + admin + 'plugins/daterangepicker/daterangepicker.css',
            bower + 'slick-carousel/slick/slick.css',
            bower + 'slick-carousel/slick/slick-theme.css',
            bower + admin + 'dist/css/AdminLTE.min.css',
            bower + admin + 'dist/css/skins/_all-skins.min.css',
            'public/css/custom.css'
        ], 'public/css/app.css', 'public/css')
        .scripts([
            bower + admin + 'plugins/daterangepicker/moment.js',
            bower + admin + 'plugins/jQuery/jquery-2.2.3.min.js',
            bower + 'bootstrap-maxlength/src/bootstrap-maxlength.js',
            bower + admin + 'plugins/jQueryUI/jquery-ui.min.js',
            bower + admin + 'bootstrap/js/bootstrap.min.js',
            bower + admin + 'plugins/slimScroll/jquery.slimscroll.min.js',
            bower + admin + 'plugins/fastclick/fastclick.min.js',
            bower + admin + 'plugins/select2/select2.full.min.js',
            bower + admin + 'plugins/select2/i18n/pt-BR.js',
            bower + admin + 'plugins/datepicker/bootstrap-datepicker.js',
            bower + admin + 'plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js',
            bower + admin + 'plugins/colorpicker/bootstrap-colorpicker.min.js',
            bower + admin + 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
            bower + admin + 'plugins/datatables/jquery.dataTables.min.js',
            bower + admin + 'plugins/datatables/dataTables.bootstrap.min.js',
            bower + 'jquery.maskedinput/dist/jquery.maskedinput.min.js',
            bower + 'sweetalert/dist/sweetalert.min.js',
            bower + 'chosen/chosen.jquery.js',
            bower + 'bootstrap3-typeahead/bootstrap3-typeahead.min.js',
            bower + 'jquery.maskMoney/dist/jquery.maskMoney.min.js',
            bower + 'mixitup/src/jquery.mixitup.js',
            bower + 'datetimepicker/build/jquery.datetimepicker.full.min.js',
            bower + 'jquery.countdown/dist/jquery.countdown.min.js',
            bower + 'jquery-colorbox/jquery.colorbox-min.js',
            bower + 'jquery-colorbox/i18n/jquery.colorbox-pt-BR.js',
            bower + 'bootstrap-checkbox/dist/js/bootstrap-checkbox.js',
            bower + 'bootstrap-checkbox/dist/js/i18n/pt.js',
            bower + admin + 'plugins/fullcalendar/fullcalendar.js',
            bower + admin + 'plugins/daterangepicker/daterangepicker.js',
            bower + 'slick-carousel/slick/slick.min.js',
            bower + admin + 'dist/js/app.min.js',
            'public/js/custom.js'
        ], 'public/js/app.js', 'public/js');

    mix.copy('public/../bower_components/AdminLTE/bootstrap/fonts', 'public/build/fonts');
    mix.copy('public/../bower_components/AdminLTE/plugins/colorpicker/img', 'public/build/css/img');
    mix.copy('public/../bower_components/font-awesome/fonts', 'public/build/fonts');
    mix.copy('public/../bower_components/Ionicons/fonts', 'public/build/fonts');
    mix.copy('public/../bower_components/chosen/*.png', 'public/build/css');
    mix.copy('public/../bower_components/jquery-colorbox/example3/images', 'public/build/css/images');
    mix.copy('public/../bower_components/slick-carousel/slick/fonts', 'public/build/css/fonts');
    mix.copy('public/../bower_components/slick-carousel/slick/ajax-loader.gif', 'public/build/css');
    mix.version(['css/app.css', 'js/app.js']);
});
