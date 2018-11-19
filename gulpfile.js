const gulp = require('gulp');
const nittro = require('gulp-nittro');
const ternary = require('ternary-stream');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const less = require('gulp-less');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const concat = require('gulp-concat');


function only(pattern, stream) {
    return ternary(f => pattern.test(f.path), stream);
}

function except (pattern, stream) {
    return ternary(f => !pattern.test(f.path), stream);
}



const publicBuilder = new nittro.Builder({
    vendor: {
        js: [
            'node_modules/photoswipe/dist/photoswipe.min.js',
            'node_modules/photoswipe/dist/photoswipe-ui-default.min.js'
        ],
        css: [
            'node_modules/source-sans-pro/source-sans-pro.css',
            'node_modules/tailwindcss/dist/tailwind.min.css',
            'node_modules/photoswipe/dist/photoswipe.css',
            'node_modules/photoswipe/dist/default-skin/default-skin.css'
        ]
    },
    base: {
        di: true,
        page: true,
        forms: true
    },
    libraries: {
        css: [
            'src/assets/css/styles.css'
        ]
    },
    bootstrap: {
        params: {
            page: {
                transitions: {
                    defaultSelector: '.flow-in'
                }
            }
        }
    },
    stack: true
});


gulp.task('public:js', function() {
    return nittro('js', publicBuilder)
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(except(/\.min\.js$/i, uglify({ compress: true, mangle: false })))
        .pipe(concat('public.min.js'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/js'));
});

gulp.task('public:css', function () {
    return nittro('css', publicBuilder)
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(only(/\.less$/i, less()))
        .pipe(except(/\.min\.css$/i, postcss([ autoprefixer(), cssnano() ])))
        .pipe(concat('public.min.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/css'));
});

gulp.task('public:assets:fonts', function () {
    return gulp.src([
        'node_modules/source-sans-pro/**/*.{woff,woff2,ttf,otf}'
    ]).pipe(gulp.dest('public/css'));
});

gulp.task('public:assets:photoswipe', function () {
    return gulp.src([
        'node_modules/photoswipe/dist/default-skin/*.{png,svg,gif}'
    ]).pipe(gulp.dest('public/css'));
});




const adminBuilder = new nittro.Builder({
    vendor: {
        js: [
            'node_modules/jquery/dist/jquery.slim.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/html5sortable/dist/html5sortable.min.js',
            'node_modules/flatpickr/dist/flatpickr.min.js',
            'node_modules/flatpickr/dist/l10n/cs.js'
        ],
        css: [
            'node_modules/bootstrap/dist/css/bootstrap.min.css',
            'node_modules/flatpickr/dist/flatpickr.min.css'
        ]
    },
    base: {
        di: true,
        page: true,
        forms: true,
        flashes: true
    },
    extras: {
        dialogs: true
    },
    libraries: {
        js: [
            'src/assets/js/BootstrapErrorRenderer.js'
        ],
        css: [
            'src/assets/css/admin.less'
        ]
    },
    bootstrap: {
        params: {
            dialogs: {
                baseZ: 1100
            }
        },
        services: {
            formErrorRenderer: 'App.Forms.BootstrapErrorRenderer()'
        }
    },
    stack: true
});



gulp.task('admin:js', function () {
    return nittro('js', adminBuilder)
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(except(/\.min\.js$/i, uglify({ compress: true, mangle: false })))
        .pipe(concat('admin.min.js'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/js'));
});


gulp.task('admin:css', function () {
    return nittro('css', adminBuilder)
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(only(/\.less$/i, less()))
        .pipe(except(/\.min\.css$/i, postcss([ autoprefixer(), cssnano() ])))
        .pipe(concat('admin.min.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/css'));
});



gulp.task('public:assets', gulp.parallel('public:assets:fonts', 'public:assets:photoswipe'));
gulp.task('public', gulp.parallel('public:js', 'public:css', 'public:assets'));
gulp.task('admin', gulp.parallel('admin:js', 'admin:css'));
gulp.task('default', gulp.parallel('public', 'admin'));

