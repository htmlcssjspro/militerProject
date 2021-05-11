'use strict';

//* Gulp Main
const {src, dest, watch, series, parallel} = require('gulp');

//* Gulp Common
const del = require('del');
const gulpif     = require('gulp-if');
const rename     = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const webp = require('gulp-webp');
const webpConfig = {
    preset      : 'default', // string: *default | photo | picture | drawing | icon | text
    quality     : 75, // number: 0 - 100, *75
    alphaQuality: 100, // number: 0 - 100, *100
    method      : 4, // 0 (fastest) and 6 (slowest), *4
    // size        : 100, // number: target size in bytes
    sns         : 80, // number: 0 - 100, *80, Set the amplitude of spatial noise shaping
    filter      : 0, // number: deblocking filter strength between 0 (off) and 100
    autoFilter  : false, // boolean: Adjust filter strength automatically
    sharpness   : 0, // number: *0, filter sharpness between 0 (sharpest) and 7 (least sharp)
    lossless    : false, // boolean: *false, Encode images losslessly
    nearLossless: 100, // number: 0-100, *100, Encode losslessly with an additional lossy pre-processing step, with a quality factor between 0 (maximum pre-processing) and 100 (same as lossless)
    // crop        : {x: number, y: number, width: number, height: number},
    // resize  : { width: number, height: number },
    metadata    : 'none', // string | string[], *none | all | exif | icc | xmp
    // buffer: // Buffer: Buffer to optimize
};

//* SCSS
const autoprefixer = require('autoprefixer');
const cssnano      = require('cssnano');
const postcss      = require('gulp-postcss');
const sass         = require('gulp-sass');
sass.compiler      = require('node-sass');
const sassGlob     = require('gulp-sass-glob');
const gcmq         = require('gulp-group-css-media-queries');

//* Webpack
// const webpack       = require('webpack');
// const webpackConfig = require('./webpack.config');
// webpackConfig.mode  = 'production';
// const webpackStream = require('webpack-stream');

//* npm CommonJS
// const args     = require('yargs').argv;
const c        = require('ansi-colors');
const path     = require('path');
const through2 = require('through2');
const {exec, execSync, spawn, spawnSync} = require('child_process');


//* FTP (Gulp)
const vinylFtp  = require('vinyl-ftp');
const ftpConfig = require('./ftpConfig');
const ftp       = vinylFtp.create({
    host          : ftpConfig.host,
    user          : ftpConfig.user,
    password      : ftpConfig.password,
    parallel      : 8,
    maxConnections: 64,
    reload        : true,
    log           : log,
});

const DEV  = true;
const PROD = !DEV;
const mode = DEV ? 'DEVELOPMENT' : 'PRODUCTION';
console.log(`Mode: ${mode}`);

const config = {
    useFTP: true,
    ftp   : {
        globs: [
            'Main/**/*.php',
            'Admin/**/*.php',
            'User/**/*.php',
            'Api/**/*.php',
            'config/**/*.php',
            'public/**/*',
            'vendor/militer/**/*.php',
            '.htaccess',
            'composer.json',
        ],
        path: ftpConfig.path ? ftpConfig.path : '/'
    },
    js: {
        globs       : './public/js/**/*',
        remoteFolder: 'public/js'
    },
    css: {
        src   : './src/css',
        output: './public/css'
    },
    scss: {
        src   : './src/scss',
        output: './public/css',
        main  : './src/scss/Main/main.scss',
        admin : './src/scss/Admin/admin.scss',
    },
    img: {
        globs: [
            'src/img/**/*'
        ],
        src   : './src/img',
        output: './public/img'
    },
    globs: [
        '(Main|Admin|User|Api|config)/**/*.php',
        'src/scss/**/*.scss',
        'src/js/**/*.js',
        'public/*',
        'D:/WebDevelopment/Projects/LIBS/**/(*.scss|*.js)',
        'vendor/militer/**/*.php',
        'composer.json',
    ],
    options: {
        src: {
            base  : '.',
            buffer: false
        },
        dest   : {},
        watcher: {
            cwd   : '.',
            events: ['add', 'change', 'unlink', 'ready'],
        }
    },
};

function watcher() {
    const watcher = watch(config.globs, config.options.watcher);
    watcher.on('change', filePath => dispatch(change, filePath));
    // watcher.on('add',    filePath => dispatch(add,    filePath));
    watcher.on('unlink', filePath => dispatch(unlink, filePath));

    const imgWatcher = watch(config.img.globs, config.options.watcher);
    imgWatcher.on('add', filePath => img(slash(filePath)));
    // imgWatcher.on('unlink', filePath => imgDel(slash(filePath)));

    // const adminWatcher = watch(config.admin.globs, config.options.watcher);
    // adminWatcher.on('change', filePath => setTimeout(() => admin(slash(filePath)), 200));
}

function dispatch(func, filePath) {
    filePath = slash(filePath);
    setTimeout(() => func(filePath), 200);
}

function change(filePath) {
    logFile('Изменен файл:', filePath);
    const ext = path.extname(filePath);

    switch (ext) {
        case '.php':
        case '.html':
        case '.json':
            php(filePath);
            break;
        case '.js':
            webpack();
            break;
        case '.scss':
            scssMain(filePath);
            break;
        case '.css':
            css(filePath);
            break;
    }
}

function php(filePath) {
    if (config.useFTP) {
        const {ftpDest} = getDest(filePath);
        return src(filePath).pipe(ftp.dest(ftpDest));
    }
}

function webpack() {
    exec('npx webpack', (error, stdout, stderr) => {
        if (error) {
            console.error(error);
            return;
        }
        console.log(stdout);
        if (stderr) {
            console.error('stderr:', stderr);
        }
        if (config.useFTP) {
            const remoteJsFolder = path.posix.join(config.ftp.path, config.js.remoteFolder);
            return src(config.js.globs, config.options.src)
                .pipe(ftp.newer(remoteJsFolder))
                .pipe(ftp.dest(config.ftp.path));
        }
    });
}


function scssMain(filePath) {
    const baseName = path.basename(filePath);
    const main = config.scss.main;
    const admin = config.scss.admin;
    scss(filePath);
    if (baseName !== path.basename(main) &&
        baseName !== path.basename(admin)) {
        scss(main);
        scss(admin);
    }
}

function scss(filePath) {
    let {destPath, ftpDest} = getDest(filePath, config.scss.src, config.scss.output);
    destPath = path.dirname(destPath);
    ftpDest  = path.dirname(ftpDest);
    return src(filePath)
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(sassGlob())
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulpif(DEV,
            postcss([
                autoprefixer(),
            ]),
            postcss([
                autoprefixer(),
                cssnano()
            ]))
        )
        .pipe(gulpif(DEV, sourcemaps.write('.')))
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(destPath))
        .pipe(gulpif(config.useFTP, ftp.dest(ftpDest)));
}

function css(filePath) {
    const {destPath, ftpDest} = getDest(filePath, config.css.src, config.css.output);
    return src(filePath)
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(gulpif(DEV,
            postcss([
                autoprefixer(),
            ]),
            postcss([
                autoprefixer(),
                cssnano()
            ]))
        )
        .pipe(gulpif(DEV, sourcemaps.write('.')))
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(destPath))
        .pipe(gulpif(config.useFTP, ftp.dest(ftpDest)));
}

function img(filePath) {
    const {destPath, ftpDest} = getDest(filePath, config.img.src, config.img.output);
    logFile('Добавлен локальный файл изображения:', filePath);
    return src(filePath)
        // .pipe(webp(webpConfig))
        .pipe(webp())
        .pipe(dest(destPath))
        .pipe(gulpif(config.useFTP, ftp.dest(ftpDest)));
}

// function imgDel(filePath) {
//     logFile('Удален локальный файл изображения:', filePath);
//     const file =
//     async() => {
//         const deleted = await del(file);
//         logFile('Удален локальный файл изображения:', deleted);
//     };
// }




function add(filePath) {
    logFile('Добавлен локальный файл:', filePath);
    if (config.useFTP) {
        const remoteFile = path.posix.join(config.ftp.path, filePath);
        return src(filePath)
            .pipe(ftp.dest(path.dirname(remoteFile)))
            .on('end', () => logFile('Добавлен файл на север:', remoteFile));
    }
}

function unlink(filePath) {
    logFile('Удален локальный файл:', filePath);
    if (config.useFTP) {
        const remoteFile = path.posix.join(config.ftp.path, filePath);
        ftp.delete(remoteFile, () => logFile('Удален файл с сервера:', remoteFile));
    }
}

function ftpRefresh() {
    if (config.useFTP) {
        return src(config.ftp.globs, config.options.src)
            .pipe(ftp.newer(config.ftp.path))
            .pipe(ftp.dest(config.ftp.path))
            .pipe(ftp.clean(config.ftp.globs, '.', {base: config.ftp.path, buffer: false}));
    }
}


//* Helpers

function slash(filePath) {
    return filePath.replace(/\\/g, '/');
}

function getDest(filePath, src = '.', output = '.') {
    const rel = path.relative(src, path.dirname(filePath));
    const destPath = path.posix.join(output, rel);
    const ftpDest = path.posix.join(config.ftp.path, destPath);
    return {destPath: destPath, ftpDest: ftpDest};
}

function logFile(message, filePath) {
    console.log(c.cyanBright('#'.repeat(80)));
    log(c.greenBright(message), c.magentaBright(filePath));
    console.log(c.cyanBright('#'.repeat(80)));
}

function log() {
    const time = getTime();
    process.stdout.write(time + ' ');
    console.log.apply(console, arguments);
    return this;
}

function getTime() {
    const time = new Date().toTimeString().slice(0, 8);
    return `[${c.gray(time)}]`;
}




//? Функция, которая может работать внутри .pipe(): .pipe(myFunction())
function myFunction() {
    return through2.obj(function(file, enc, cb) {
        // Paths are resolved by gulp
        const filepath = file.path;
        console.log('filepath: ', filepath);
        const cwd = file.cwd;
        console.log('cwd: ', cwd);
        const relative = path.relative(cwd, filepath);
        console.log('relative: ', relative);

        //* Do something

        cb();
    });
}

function checkStream() {
    return through2.obj(function(file, enc, cb) {
        // Paths are resolved by gulp
        const filepath = file.path;
        console.log('filepath: ', filepath);
        const cwd = file.cwd;
        console.log('cwd: ', cwd);
        const relative = path.relative(cwd, filepath);
        console.log('relative: ', relative);
        cb();
    });
}




exports.default    = watcher;
exports.ftpRefresh = ftpRefresh;
