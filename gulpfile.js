/* eslint-disable no-console */
/* eslint-disable no-unused-vars */
'use strict';

//* Gulp Main
const {src, dest, watch, series, parallel} = require('gulp');

//* Gulp Common
const gulpif     = require('gulp-if');
const sourcemaps = require('gulp-sourcemaps');
const del        = require('del');
const rename     = require('gulp-rename');
const webp       = require('gulp-webp');
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
const sass         = require('gulp-sass');
sass.compiler      = require('node-sass');
const sassGlob     = require('gulp-sass-glob');
const postcss      = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano      = require('cssnano');
const gcmq         = require('gulp-group-css-media-queries');

//* Webpack
// const webpack       = require('webpack');
// const webpackConfig = require('./webpack.config');
// webpackConfig.mode  = 'production';
// const webpackStream = require('webpack-stream');

//* npm CommonJS
const path = require('path');
const c    = require('ansi-colors');
const {exec, execSync, spawn, spawnSync} = require('child_process');
const through2 = require('through2');
const argv     = require('yargs').argv;

//* FTP
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


//*****************************************************************************
//* Project Settings
//*****************************************************************************
const DEV     = true; // true | false
const USE_FTP = true; // true | false
//*****************************************************************************


const PROD = !DEV;
const mode = DEV ? 'dev' : 'prod';
logHeader(c.greenBright('Gulp START   '), c.cyanBright('Mode: '), mode);


const config = {};

config.src = 'src';
config.pub = 'public';

config.php = {};
config.php.globs = [
    'Main/**/*.php',
    'Admin/**/*.php',
    'User/**/*.php',
    'Api/**/*.php',
    'config/**/*.php',
];

config.js = {};
config.js.src      = `${config.src}/js`;
config.js.pub      = `${config.pub}/js`;
config.js.srcGlobs = `${config.js.src}/**/*.js`;
config.js.pubGlobs = `${config.js.pub}/**/*.js`;

config.scss = {};
config.scss.src   = `${config.src}/scss`;
config.scss.pub   = `${config.pub}/css`;
config.scss.globs = `${config.scss.src}/**/*.scss`;
config.scss.main  = `${config.scss.src}/main.scss`;
config.scss.admin = `${config.scss.src}/admin.scss`;

config.css = {};
config.css.src      = `${config.src}/css`;
config.css.pub      = `${config.pub}/css`;
config.css.srcGlobs = `${config.css.src}/**/*.css`;
config.css.pubGlobs = `${config.css.pub}/**/*.css`;

config.img = {};
config.img.src      = `${config.src}/img`;
config.img.pub      = `${config.pub}/img`;
config.img.srcGlobs = `${config.img.src}/**/*`;
config.img.pubGlobs = `${config.img.pub}/**/*`;

config.public = {};
config.public.srcGlobs = `${config.pub}/*`;
config.public.pubGlobs = `${config.pub}/**/*`;

config.vendor = {};
config.vendor.src = 'vendor/militer';
config.vendor.php = [
    `${config.vendor.src}/mvc-core/src/**/*.php`,
    `${config.vendor.src}/cms-core/src/**/*.php`,
];
config.vendor.assets = [
    `${config.vendor.src}/assets/src/**/*.js`,
    `${config.vendor.src}/assets/src/**/*.scss`,
];

config.watchGlobs = [
    config.php.globs,
    config.scss.globs,
    config.js.srcGlobs,
    config.public.srcGlobs,
    config.vendor.php,
    config.vendor.assets,
    'composer.json',
].flat();

ftp.globs = [
    config.php.globs,
    config.public.pubGlobs,
    config.vendor.php,
    '.htaccess',
    'composer.json',
].flat();
ftp.root = ftpConfig.root ? ftpConfig.root : '/';


const options = {};
options.watcher = {
    cwd   : '.',
    events: ['add', 'change', 'unlink', 'ready'],
};
// options.src = {
//     base: '.',
// };
options.srcCopy = {
    base  : '.',
    buffer: false
};
options.dest = {};


function watcher() {
    const watcher = watch(config.watchGlobs, options.watcher);
    watcher.on('change', filePath => dispatch(change, filePath));
    // watcher.on('add',    filePath => dispatch(add,    filePath));
    watcher.on('unlink', filePath => dispatch(unlink, filePath));

    const imgWatcher = watch(config.img.srcGlobs, options.watcher);
    imgWatcher.on('add', filePath => img(slash(filePath)));
    // imgWatcher.on('unlink', filePath => imgDel(slash(filePath)));
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
            js(filePath);
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
    if (USE_FTP) {
        const {ftpDest} = getDest(filePath);
        return src(filePath)
            .pipe(ftp.dest(ftpDest));
    }
}

function js(filePath) {
    if (filePath.startsWith(config.js.src)) {
        const {destPath} = getDest(filePath, config.js.src, config.js.pub);
        const name = path.basename(filePath, '.js');
        const globs = `${config.js.pub}/**/${name}.js?(.map)`;
        const command = `npx webpack --config ./webpack.gulp.js --env ${mode} --env name=${name} --env entry=${filePath} --env output=${destPath}`;
        webpack(command, () => {
            if (USE_FTP) {
                return src(globs, options.srcCopy)
                    .pipe(ftp.dest(ftp.root));
            }
        });
    } else {
        const command = `npx webpack --config ./webpack.main.js --env ${mode}`;
        webpack(command, () => {
            if (USE_FTP) {
                return src(`${config.js.pubGlobs}?(.map)`, options.srcCopy)
                    .pipe(ftp.newer(ftp.root))
                    .pipe(ftp.dest(ftp.root));
            }
        });
    }
}

function webpack(command, cb) {
    exec(command, (error, stdout, stderr) => {
        console.log(c.yellowBright('Command: '), command);
        error && console.error(error);
        console.log(stdout);
        stderr && console.error('stderr:', stderr);
        cb();
    });
}


function scssMain(filePath) {
    const baseName = path.basename(filePath);
    const main  = config.scss.main;
    const admin = config.scss.admin;
    scss(filePath);
    if (baseName !== path.basename(main) &&
        baseName !== path.basename(admin)) {
        scss(main);
        scss(admin);
    }
}

function scss(filePath) {
    const {destPath, ftpDest} = getDest(filePath, config.scss.src, config.scss.pub);
    return src(filePath)
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(gulpif(DEV, sourcemaps.identityMap()))
        .pipe(sassGlob())
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulpif(DEV,
            postcss([autoprefixer()]),
            postcss([autoprefixer(), cssnano()]))
        )
        .pipe(gulpif(DEV,
            sourcemaps.write('./maps', {sourceRoot: `/${config.scss.src}`}))
        )
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(destPath))
        .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)));
}

function css(filePath) {
    const {destPath, ftpDest} = getDest(filePath, config.css.src, config.css.pub);
    return src(filePath)
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(gulpif(DEV,
            postcss([autoprefixer()]),
            postcss([autoprefixer(), cssnano()]))
        )
        .pipe(gulpif(DEV, sourcemaps.write('./maps', {sourceRoot: `/${config.css.src}`})))
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(destPath))
        .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)));
}

function img(filePath) {
    const {destPath, ftpDest} = getDest(filePath, config.img.src, config.img.pub);
    logFile('Добавлен локальный файл изображения:', filePath);
    return src(filePath)
        // .pipe(webp(webpConfig))
        .pipe(webp())
        .pipe(dest(destPath))
        .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)));
}

// function imgDel(filePath) {
//     logFile('Удален локальный файл изображения:', filePath);
//     const file =
//     async() => {
//         const deleted = await del(file);
//         logFile('Удален локальный файл изображения:', deleted);
//     };
// }




// function add(filePath) {
//     logFile('Добавлен локальный файл:', filePath);
//     if (USE_FTP) {
//         const remoteFile = path.posix.join(ftp.root, filePath);
//         return src(filePath, options.srcCopy)
//             .pipe(ftp.dest(path.posix.dirname(remoteFile)))
//             .on('end', () => logFile('Добавлен файл на север:', remoteFile));
//     }
// }

function unlink(filePath) {
    logFile('Удален локальный файл:', filePath);
    if (USE_FTP) {
        const remoteFile = path.posix.join(ftp.root, filePath);
        ftp.delete(remoteFile, () => logFile('Удален файл с сервера:', remoteFile));
    }
}

function ftpRefresh(cb) {
    if (USE_FTP) {
        const cleanGlobs = ftp.globs.map(item => path.posix.join(ftp.root, item));
        const cleanOptions = {
            base  : ftp.root,
            buffer: false,
        };
        return src(ftp.globs, options.srcCopy)
            .pipe(ftp.newer(ftp.root))
            .pipe(ftp.dest(ftp.root))
            .pipe(ftp.clean(cleanGlobs, '.', cleanOptions));
    } else {
        logError('USE_FTP = false');
        cb();
    }
}


//* Helpers

function slash(filePath) {
    return filePath.replace(/\\/g, '/');
}


function getDest(filePath, src = '.', out = '.') {
    const rel = path.posix.relative(src, path.posix.dirname(filePath));
    const destPath = path.posix.join(out, rel);
    const ftpDest = path.posix.join(ftp.root, destPath);
    return {destPath: destPath, ftpDest: ftpDest};
}


//* console.log()

function logFile(message, filePath) {
    logHeader(c.greenBright(message), c.magentaBright(filePath));
}

function logHeader(...header) {
    console.log(c.cyanBright('#'.repeat(80)));
    log(...header);
    console.log(c.cyanBright('#'.repeat(80)));
}

function logError(...error) {
    log(c.redBright('ERROR'), ...error);
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
