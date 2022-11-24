var gulp = require('gulp'),
    watch = require('gulp-watch'),
    autoPrefixer = require('gulp-autoprefixer'),
    cssMin = require('gulp-cssmin'),
    jsMin = require('gulp-jsmin'),
    rigger = require('gulp-rigger'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    replace = require('gulp-replace'),
    sourceMaps = require('gulp-sourcemaps'),
	imageMin = require('gulp-imagemin'),
	browserSync = require('browser-sync').create();

var devURL = 'https://harbingermarketingtemplate.local';

var paths = {
    build: {
		branding: {
			img: 'assets/branding/img/',
			js: 'assets/branding/js/',
        	css: 'assets/branding/css/'
		},
		pdfs: {
			img: 'assets/pdfs/img/',
        	css: 'assets/pdfs/css/'
		},
		theme: {
			img: 'assets/theme/img/',
			js: 'assets/theme/js/',
        	css: 'assets/theme/css/',
            cssBlocks: 'assets/theme/css/blocks/',
            cssVendor: 'assets/theme/css/vendor/'
		},
        admin: {
            img: 'assets/admin/img/',
            js: 'assets/admin/js/',
            css: 'assets/admin/css/'
        }
	},
	
    src: {
		branding: {
			img: 'assets-src/branding/img/**',
			js: 'assets-src/branding/js/*.js',
        	scss: 'assets-src/branding/scss/main.scss'
		},
		pdfs: {
			img: 'assets-src/pdfs/img/**/**',
        	scss: 'assets-src/pdfs/scss/main.scss'
		},
		theme: {
			img: 'assets-src/theme/img/**/**',
			js: 'assets-src/theme/js/**/*.js',
        	scss: 'assets-src/theme/scss/main.scss',
            scssVendor: 'assets-src/theme/scss/vendor/*.scss',
        	scssBlocks: 'assets-src/theme/scss/blocks/*.scss'
		},
        admin: {
            img: 'assets-src/admin/img/**/**',
            js: 'assets-src/admin/js/*.js',
            scss: 'assets-src/admin/scss/*.scss'
        }
    },

    watch: {
		img: 'assets-src/**/img/**/**',
        js: 'assets-src/**/*.js',
        scss: 'assets-src/**/*.scss'
    }
};

gulp.task('images-branding', function () {
    return gulp.src(paths.src.branding.img)
        .pipe(imageMin([
            imageMin.gifsicle(),
            imageMin.mozjpeg(),
            imageMin.optipng()
        ]))
        .pipe(gulp.dest(paths.build.branding.img));
});

gulp.task('images-pdfs', function () {
    return gulp.src(paths.src.pdfs.img)
        .pipe(imageMin([
            imageMin.gifsicle(),
            imageMin.mozjpeg(),
            imageMin.optipng()
        ]))
        .pipe(gulp.dest(paths.build.pdfs.img));
});

gulp.task('images-theme', function () {
    return gulp.src(paths.src.theme.img)
        .pipe(imageMin(([
            imageMin.gifsicle(),
            imageMin.mozjpeg(),
            imageMin.optipng()
        ])))
        .pipe(gulp.dest(paths.build.theme.img));
});

gulp.task('images-admin', function () {
    return gulp.src(paths.src.admin.img)
        .pipe(imageMin(([
            imageMin.gifsicle(),
            imageMin.mozjpeg(),
            imageMin.optipng()
        ])))
        .pipe(gulp.dest(paths.build.admin.img));
});


gulp.task('js-branding', function () {
    return gulp.src(paths.src.branding.js)
        .pipe(rigger())
        .pipe(gulp.dest(paths.build.branding.js));
});

gulp.task('js-theme', function () {
    return gulp.src(paths.src.theme.js)
        .pipe(rigger())
        .pipe(gulp.dest(paths.build.theme.js));
});

gulp.task('js-admin', function () {
    return gulp.src(paths.src.admin.js)
        .pipe(rigger())
        .pipe(gulp.dest(paths.build.admin.js));
});

gulp.task('js-min-branding', function () {
    return gulp.src(paths.src.branding.js)
        .pipe(rigger())
        .pipe(jsMin())
        .pipe(gulp.dest(paths.build.branding.js));
});

gulp.task('js-min-theme', function () {
    return gulp.src(paths.src.theme.js)
        .pipe(rigger())
        .pipe(jsMin())
        .pipe(gulp.dest(paths.build.theme.js));
});

gulp.task('js-min-admin', function () {
    return gulp.src(paths.src.admin.js)
        .pipe(rigger())
        .pipe(jsMin())
        .pipe(gulp.dest(paths.build.admin.js));
});


gulp.task('css-branding', function () {
    return gulp.src(paths.src.branding.scss)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(rename('styles.css'))
        .pipe(gulp.dest(paths.build.branding.css))
        .pipe(browserSync.stream());
});

gulp.task('css-pdfs', function () {
    return gulp.src(paths.src.pdfs.scss)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(rename('styles.css'))
        .pipe(gulp.dest(paths.build.pdfs.css))
        .pipe(browserSync.stream());
});

gulp.task('css-theme', function () {
    return gulp.src(paths.src.theme.scss)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        // .pipe(
        //     autoPrefixer({
        //         cascade: false
        //     })
        // )
        .pipe(rename('styles.css'))
        .pipe(gulp.dest(paths.build.theme.css))
        .pipe(browserSync.stream());
});

gulp.task('css-theme-blocks', function () {
    return gulp.src(paths.src.theme.scssBlocks)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        // .pipe(
        //     autoPrefixer({
        //         cascade: false
        //     })
        // )
        .pipe(gulp.dest(paths.build.theme.cssBlocks))
        .pipe(browserSync.stream());
});

gulp.task('css-theme-vendor', function () {
    return gulp.src(paths.src.theme.scssVendor)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(gulp.dest(paths.build.theme.cssVendor))
        .pipe(browserSync.stream());
});

gulp.task('css-admin', function() {
    return gulp.src( paths.src.admin.scss, { allowEmpty: true } )
        .pipe( sourceMaps.init() )
        .pipe( sass().on('error', sass.logError) )
        .pipe( sourceMaps.write() )
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe( gulp.dest(paths.build.admin.css) )
        .pipe( browserSync.stream() );
});

gulp.task('css-min-branding', function () {
    return gulp.src(paths.src.branding.scss)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(cssMin())
        .pipe(rename('styles.css'))
        .pipe(gulp.dest(paths.build.branding.css));
});

gulp.task('css-min-pdfs', function () {
    return gulp.src(paths.src.pdfs.scss)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(cssMin())
        .pipe(rename('styles.css'))
        .pipe(gulp.dest(paths.build.pdfs.css));
});

gulp.task('css-min-theme', function () {
    return gulp.src(paths.src.theme.scss)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(cssMin())
        .pipe(rename('styles.css'))
        .pipe(gulp.dest(paths.build.theme.css));
});

gulp.task('css-min-theme-blocks', function () {
    return gulp.src(paths.src.theme.scssBlocks)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(cssMin())
        .pipe(gulp.dest(paths.build.theme.cssBlocks));
});

gulp.task('css-min-theme-vendor', function () {
    return gulp.src(paths.src.theme.scssVendor)
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourceMaps.write())
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe(cssMin())
        .pipe(gulp.dest(paths.build.theme.cssVendor));
});

gulp.task('css-min-admin', function () {
    return gulp.src( paths.src.admin.scss, { allowEmpty: true } )
        .pipe( sourceMaps.init() )
        .pipe( sass().on('error', sass.logError) )
        .pipe( sourceMaps.write() )
        .pipe(
            autoPrefixer({
                cascade: false
            })
        )
        .pipe( cssMin() )
        .pipe( rename('styles.css') )
        .pipe( gulp.dest(paths.build.admin.css) );
});


gulp.task('images', gulp.parallel('images-branding', 'images-pdfs', 'images-theme', 'images-admin'));
gulp.task('js', gulp.parallel('js-branding', 'js-theme', 'js-admin'));
gulp.task('js-min', gulp.parallel('js-min-branding', 'js-min-theme', 'js-min-admin'));
gulp.task('css', gulp.parallel('css-branding', 'css-pdfs', 'css-theme', 'css-theme-blocks', 'css-theme-vendor', 'css-admin'));
gulp.task('css-min', gulp.parallel('css-min-branding', 'css-min-pdfs', 'css-min-theme', 'css-min-theme-blocks', 'css-min-theme-vendor', 'css-min-admin'));
gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: devURL
    });
});

gulp.task('dev', gulp.parallel('images', 'js', 'css'));

gulp.task('dev-watch', function() {
	watch(paths.watch.img, function(event, cb) {
        gulp.series('images')();

		browserSync.reload();
	});

    watch(paths.watch.js, function(event, cb) {
        gulp.series('js')();

		browserSync.reload();
    });

	watch(paths.watch.scss, function(event, cb) {
        gulp.series('css')();
	});
});

gulp.task('prod', gulp.parallel('images', 'js-min', 'css-min'));

gulp.task('default', gulp.parallel('dev', 'browser-sync', 'dev-watch'));