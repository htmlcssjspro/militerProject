// /* eslint-disable no-unused-vars */
'use strict';

//* Gulp Main
//* const {src, dest, watch, series, parallel} = require('gulp');
const {src, dest, watch} = require('gulp');

//* Gulp Common
// const rename     = require('gulp-rename');
// const webpConfig = {
//     preset      : 'default', // string: *default | photo | picture | drawing | icon | text
//     quality     : 75, // number: 0 - 100, *75
//     alphaQuality: 100, // number: 0 - 100, *100
//     method      : 4, // 0 (fastest) and 6 (slowest), *4
//     // size        : 100, // number: target size in bytes
//     sns         : 80, // number: 0 - 100, *80, Set the amplitude of spatial noise shaping
//     filter      : 0, // number: deblocking filter strength between 0 (off) and 100
//     autoFilter  : false, // boolean: Adjust filter strength automatically
//     sharpness   : 0, // number: *0, filter sharpness between 0 (sharpest) and 7 (least sharp)
//     lossless    : false, // boolean: *false, Encode images losslessly
//     nearLossless: 100, // number: 0-100, *100, Encode losslessly with an additional lossy pre-processing step, with a quality factor between 0 (maximum pre-processing) and 100 (same as lossless)
//     // crop        : {x: number, y: number, width: number, height: number},
//     // resize  : { width: number, height: number },
//     metadata    : 'none', // string | string[], *none | all | exif | icc | xmp
//     // buffer: // Buffer: Buffer to optimize
// };
const gulpif     = require('gulp-if');
const sourcemaps = require('gulp-sourcemaps');
const del        = require('del');
const webp       = require('gulp-webp');

//* SCSS
const sass         = require('gulp-sass');
sass.compiler      = require('node-sass'); // eslint-disable-line no-multi-spaces
const sassGlob     = require('gulp-sass-glob');
const postcss      = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano      = require('cssnano');
const gcmq         = require('gulp-group-css-media-queries');

//* npm CommonJS
// const {exec, execSync, spawn, spawnSync} = require('child_process');
const {exec}   = require('child_process');
const path     = require('path');
const c        = require('ansi-colors');
const through2 = require('through2');

//* FTP
const vinylFtp  = require('vinyl-ftp');
const ftpConfig = require('./ftpConfig');
let ftp = {};
ftp = vinylFtp.create({
    host:           ftpConfig.host,
    user:           ftpConfig.user,
    password:       ftpConfig.password,
    parallel:       8,
    maxConnections: 1024,
    reload:         true,
    log:            logFTP,
});
ftp.root = ftpConfig.root ? ftpConfig.root : '/';


//*****************************************************************************
//*** Project Settings
//*****************************************************************************
const DEV     = true;   // * true | false
const USE_FTP = true;   // * true | false
//*****************************************************************************

const PROD = !DEV;
const mode = DEV ? 'development' : 'production';

logHeader(
    c.greenBright('Gulp START'),
    c.greenBright('Mode: ') + c.yellowBright(mode),
    c.greenBright('USE_FTP: ') + c.magentaBright(USE_FTP)
);

const config = {};

config.src = 'src';
config.pub = 'public';

config.php = {};
config.phpGlobs = [
    'Main/**/*.php',
    'Admin/**/*.php',
    'User/**/*.php',
    'Api/**/*.php',
    'config/**/*.php',
];

config.js = {};
config.js.src = `${config.src}/js`;
config.js.srcMod = `${config.js.src}/modules`;
config.js.pub = `${config.pub}/js`;
config.js.srcGlobs = `${config.js.src}/**/*.js`;
config.js.srcModGlobs = `${config.js.srcMod}/**/*.js`;
config.js.pubGlobs = `${config.js.pub}/**/*.js?(.map)`;

config.scss = {};
config.scss.src = `${config.src}/scss`;
config.scss.pub = `${config.pub}/css`;
config.scss.srcGlobs = `${config.scss.src}/**/*.scss`;
config.scss.sm = './maps';
config.scss.smOpt = {sourceRoot: `/${config.scss.src}`};

config.css = {};
config.css.src = `${config.src}/css`;
config.css.pub = `${config.pub}/css`;
config.css.srcGlobs = `${config.css.src}/**/*.css`;
config.css.pubGlobs = `${config.css.pub}/**/*.css`;
config.css.sm = './maps';
config.css.smOpt = {sourceRoot: `/${config.css.src}`};

config.img = {};
config.img.src = `${config.src}/img`;
config.img.pub = `${config.pub}/img`;
config.img.srcGlobs = `${config.img.src}/**`;
config.img.pubGlobs = `${config.img.pub}/**`;

config.public = {
    srcGlobs: `${config.pub}/*`,
    pubGlobs: `${config.pub}/**`,
};

config.vendor = {};
config.vendor.src = 'vendor/militer';
config.vendor.assets = `${config.vendor.src}/assets/src`;
config.vendor.phpGlobs = [
    `${config.vendor.src}/mvc-core/src/**/*.php`,
    `${config.vendor.src}/cms-core/src/**/*.php`,
    'vendor/composer/*.php',
    'vendor/autoload.php',
];
config.vendor.assetsGlobs = [
    `${config.vendor.assets}/**/*.js`,
    `${config.vendor.assets}/**/*.scss`,
];


config.ftpGlobs = [
    config.phpGlobs,
    config.public.pubGlobs,
    config.vendor.phpGlobs,
    '.htaccess',
    'composer.json',
].flat();

config.srcGlobs = [
    config.js.srcGlobs,
    config.scss.srcGlobs,
    config.css.srcGlobs,
    config.vendor.assetsGlobs
].flat();




const options = {};
options.src = {
    base: '.',
};
options.srcNoBuf = {
    base:   '.',
    buffer: false
};
options.ftpClean = {
    base:   ftp.root,
    buffer: false,
};
options.dest = {};
options.watch = {
    cwd:                    process.cwd(),
    delay:                  300,
    ignorePermissionErrors: true,
    events:                 ['all'],
};


//******************************************************************************
//*** Watchers
//******************************************************************************

function watcher() {
    //* options.watch.events = ['add', 'addDir', 'change', 'unlink', 'unlinkDir', 'ready'];
    const scanMessage = 'scan complete. Ready for changes';


    //* FTP Watcher
    options.watch.events = ['all'];
    const ftpWatcher = watch(config.ftpGlobs, options.watch);
    ftpWatcher.on('ready', () => logYellowFirst(pad('FTP', 3), scanMessage));
    ftpWatcher.on('change', filePath => dispatch(ftpCopy, filePath, 'CHANGELoc'));
    ftpWatcher.on('add', filePath => dispatch(ftpCopy, filePath, 'ADDLoc'));
    ftpWatcher.on('addDir', folderPath => dispatch(ftpCopy, folderPath, 'ADDDirLoc'));
    ftpWatcher.on('unlink', filePath => dispatch(ftpUnlink, filePath, 'DELLoc'));
    ftpWatcher.on('unlinkDir', folderPath => dispatch(ftpUnlinkDir, folderPath, 'RMDirLoc'));


    //* SRC Watcher
    options.watch.events = ['change', 'ready'];
    const srcWatcher = watch(config.srcGlobs, options.watch);
    srcWatcher.on('ready', () => logYellowFirst(pad('SRC', 3), scanMessage));
    srcWatcher.on('change', filePath => dispatch(change, filePath, c.bold.inverse(pad('CHANGESRC'))));


    //* IMG Watcher
    options.watch.events = ['add', 'unlink', 'change', 'ready'];
    const imgWatcher = watch(config.img.srcGlobs, options.watch);
    imgWatcher.on('ready', () => logYellowFirst(pad('IMG', 3), scanMessage));
    imgWatcher.on('add', filePath => dispatch(img, filePath, 'ADDImgLoc'));
    imgWatcher.on('change', filePath => dispatch(img, filePath, 'CHGImgLoc'));
    imgWatcher.on('unlink', filePath => dispatch(imgUnlink, filePath, 'DELImgLoc'));
}


function dispatch(func, filePath, message) {
    filePath = slash(filePath);
    logFile(message, filePath);
    setTimeout(() => func(filePath), 300);
}

function change(filePath) {
    const ext = path.extname(filePath);

    switch (ext) {
        // case '.php':
        // case '.html':
        // case '.json':
        // case '.htaccess':
        //     ftpCopy(filePath);
        //     break;
        case '.js':
            js(filePath);
            break;
        case '.scss':
            scss(filePath);
            break;
        case '.css':
            css(filePath);
            break;
    }
}



//******************************************************************************
//*** JavaScript
//******************************************************************************
function js(filePath) {
    const command = 'npx webpack --config ./webpack.gulp.js';
    process.env.mode = mode;
    if (filePath.startsWith(config.js.srcMod) ||
        filePath.startsWith(config.vendor.assets)
    ) {
        const entry = {};
        return src([config.js.srcGlobs, '!' + config.js.srcModGlobs])
            .pipe(through2.obj(function(file, enc, cb) {
                const entry = slash(file.path);
                const ext = path.extname(file.path);
                const basename = path.basename(file.path, ext);
                const rel = slash(path.dirname(path.relative(file.base, file.path)));
                const name = path.posix.join(rel, basename);
                const data = {[name]: entry};
                // this.push(data);
                cb(null, data);
            }))
            .on('data', data => Object.assign(entry, data))
            .on('end', () => {
                process.env.name = 'All JS files';
                process.env.entry = JSON.stringify(entry);
                // process.env.entryName = 'name'; //* 'main' is default in webpack.common.js
                // process.env.entryPath = 'src/path'; //* abslute or relative path, 'src/js/main.js' is default in webpack.common.js
                // process.env.output = JSON.stringify({
                //     filename: '[name].js',
                //     path:     resolve(config.js.pub), //* must be an abslute path
                // });
                // process.env.outputFilename = '[name].js'; //* '[name].js' is default in webpack.common.js
                process.env.outputPath = config.js.pub; //* abslute or relative path, 'public/js' is default in webpack.common.js
                execute(command);
                // execute(command, () => console.log('-- All JS files -- callback --'));
                // execute(command, () => USE_FTP && ftpCopyNewer(config.js.pubGlobs));
            });
    } else {
        const {destPath} = getDest(filePath, config.js.src, config.js.pub);
        const ext = path.extname(filePath);
        const baseName = path.basename(filePath, ext);
        process.env.name = baseName;
        process.env.entry = JSON.stringify({
            [baseName]: resolve(filePath)
        });
        // process.env.entryName = 'name'; //* 'main' is default in webpack.common.js
        // process.env.entryPath = 'src/path'; //* abslute or relative path, 'src/js/main.js' is default in webpack.common.js
        // process.env.output = JSON.stringify({
        //     filename: '[name].js',
        //     path:     resolve(destPath), //* must be an abslute path
        // });
        // process.env.outputFilename = '[name].js'; //* '[name].js' is default in webpack.common.js
        process.env.outputPath = destPath; //* abslute or relative path, 'public/js' is default in webpack.common.js
        execute(command, () => console.log(`-- ${baseName} -- callback --`));
        // execute(command, () => USE_FTP && ftpCopy(path.posix.join(config.js.pub, destPath, baseName + '.js?(.map)')));
    }
}


//******************************************************************************
//*** SCSS, CSS
//******************************************************************************
function scss(filePath) {
    const baseName = path.basename(filePath);
    const globs = baseName.startsWith('_') ? [config.scss.srcGlobs, '!**/_*.scss'] : filePath;
    const {destPath} = getDest(globs, config.scss.src, config.scss.pub);
    return src(globs)
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(gulpif(DEV, sourcemaps.identityMap()))
        .pipe(sassGlob())
        .pipe(sass({
            includePaths: [
                config.scss.src,
                config.vendor.src,
            ],
        }).on('error', sass.logError))
        .pipe(gulpif(DEV,
            postcss([autoprefixer()]),
            postcss([autoprefixer(), cssnano()]))
        )
        .pipe(gulpif(DEV,
            sourcemaps.write(config.scss.sm, config.scss.smOpt))
        )
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(destPath));
    // .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)));
}

function css(filePath) {
    const {destPath} = getDest(filePath, config.css.src, config.css.pub);
    return src(filePath)
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(gulpif(DEV,
            postcss([autoprefixer()]),
            postcss([autoprefixer(), cssnano()])
        ))
        .pipe(gulpif(DEV,
            sourcemaps.write(config.css.sm, config.css.smOpt)))
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(destPath));
    // .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)));
}


//******************************************************************************
//*** FTP
//******************************************************************************

function ftpCopy(globs) {
    return USE_FTP && src(globs, options.srcNoBuf)
        .pipe(ftp.dest(ftp.root))
        .on('data', file => logFile('COPYFTP', getDest(file.path).ftpFile));
}
function ftpCopyNewer(globs) {
    return USE_FTP && src(globs, options.srcNoBuf)
        .pipe(ftp.newer(ftp.root))
        .pipe(ftp.dest(ftp.root));
}
function ftpUnlink(filePath) {
    if (USE_FTP) {
        const {ftpFile} = getDest(filePath);
        ftp.delete(ftpFile, () => logFile('DELFTP', ftpFile));
    }
}
function ftpUnlinkDir(folderPath) {
    if (USE_FTP) {
        const {ftpFile} = getDest(folderPath);
        ftp.rmdir(ftpFile, () => logFile('RMDIRFTP', ftpFile));
    }
}


//******************************************************************************
//*** Image
//******************************************************************************
function img(filePath) {
    const {destPath} = getDest(filePath, config.img.src, config.img.pub);
    return src(filePath)
        .pipe(dest(destPath))
        // .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)))
        // .pipe(webp(webpConfig))
        .pipe(webp())
        .pipe(dest(destPath));
    // .pipe(gulpif(USE_FTP, ftp.dest(ftpDest)));
}
function imgUnlink(filePath) {
    const ext = path.extname(filePath);
    const baseNameGlobs = path.basename(filePath, ext) + '.*';
    const {destPath} = getDest(filePath, config.img.src, config.img.pub);
    const globs = path.posix.join(destPath, baseNameGlobs);
    // const remoteImgs = path.posix.join(ftpDest, baseNameGlobs);
    del(globs);
    // (async() => await del(globs))();
    // USE_FTP && ftp.delete(remoteImgs,
    //     (param1, param2) => {
    //         logFile('Удален файл изображения с сервера:', remoteImgs);
    //         console.log('param1: ', param1);
    //         console.log('param2: ', param2);
    //     });
}



function ftpRefresh(cb) {
    if (USE_FTP) {
        const cleanGlobs = config.ftpGlobs.map(item => path.posix.join(ftp.root, item));
        cleanGlobs.push('/*');
        return src(config.ftpGlobs, options.srcNoBuf)
            .pipe(ftp.newer(ftp.root))
            .pipe(ftp.dest(ftp.root))
            .on('end', () => ftp.clean(cleanGlobs, '.', options.ftpClean));
    } else {
        logWarning('USE_FTP = false');
        cb();
    }
}


//******************************************************************************
//*** Helpers
//******************************************************************************

function getDest(globs, src = '.', out = '.') {
    let filePath = Array.isArray(globs) ? globs[0] : globs;
    filePath = filePath.replace('/**/', '/');
    const rel = slash(path.relative(src, filePath));
    const destPath = path.posix.join(out, path.posix.dirname(rel));
    const ftpDest  = path.posix.join(ftp.root, destPath);
    const ftpFile  = path.posix.join(ftp.root, out, rel);
    return {destPath, ftpDest, ftpFile};
}
function slash(filePath) {
    return filePath.split(path.sep).join(path.posix.sep);
}
function resolve(relPath) {
    return slash(path.resolve(__dirname, relPath));
}
function execute(command, cb = () => {}) {
    exec(command, (error, stdout, stderr) => {
        logYellowFirst('Command:', command);
        error && logError(error);
        console.log(stdout); // eslint-disable-line no-console
        stderr && logError('stderr:', stderr);
        cb();
    });
}

// function spawner(command, args, options = undefined, cb) {
//     logYellowFirst('Command:', command);
//     let child;
//     if (options) {
//         child = spawn(command, args, options);
//     } else {
//         child = spawn(command, args);
//     }

//     child.stdout.on('data', data => {
//         console.log(data);
//     });
//     child.stderr.on('data', data => {
//         console.error(data);
//     });
//     child.on('close', code => {
//         console.log(`child process exited with code ${code}`);
//         cb(code);
//     });
// }


//******************************************************************************
//*** console.log()
//******************************************************************************
function logFile(message, filePath) {
    log(c.greenBright(pad(message)), c.magentaBright(filePath));
}
function logFTP(first, ...other) {
    log(pad(first), ...other); // eslint-disable-line no-console
}
function logHeader(...header) {
    console.log(title()); // eslint-disable-line no-console
    logLength(...header); // eslint-disable-line no-console
    console.log(title()); // eslint-disable-line no-console
}
function logLength(...args) {
    const text = [...args].join(' ' + title(3) + ' ');
    const textLength = text.replace(/\x1B\[\d\dm/g, '').length; // eslint-disable-line no-control-regex

    const strLength = 80;
    const startLength = 10;
    const padLength = strLength - startLength - textLength - 2;

    console.log(title(startLength), text, title(padLength)); // eslint-disable-line no-console
}
function title(length = 80) {
    return c.cyanBright('#'.repeat(length));
}
function pad(message, length = 10) {
    return message.padEnd(length, ' ');
}
function logYellowFirst(first, ...other) {
    console.log(c.yellowBright(first), ...other); // eslint-disable-line no-console
}
function logWarning(...warning) {
    console.log(c.yellowBright('WARNING'), ...warning); // eslint-disable-line no-console
}
function logError(...error) {
    console.log(c.redBright('ERROR'), ...error); // eslint-disable-line no-console
}
// function log() {
//     let time = new Date().toTimeString().slice(0, 8);
//     time = `[${c.gray(time)}]`;
//     process.stdout.write(time + ' ');
//     console.log.apply(console, arguments);
//     return this;
// }

// function getTime() {
//     const time = new Date().toTimeString().slice(0, 8);
//     return `[${c.gray(time)}]`;
// }
function log(...args) {
    let time = new Date().toTimeString().slice(0, 8);
    time = `[${c.gray(time)}]`;
    console.log(time, ...args); // eslint-disable-line no-console
}



function test(cb) {
    logHeader(c.greenBright('Gulp TEST'));

    const globs = 'public/css/Main/**';
    ftpUnlink(globs);


    cb(); // work
}



exports.default = watcher;

exports.ftpRefresh = ftpRefresh;

exports.test = test;
