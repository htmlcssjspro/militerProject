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

config.css = {};
config.css.src = `${config.src}/css`;
config.css.pub = `${config.pub}/css`;
config.css.srcGlobs = `${config.css.src}/**/*.css`;
config.css.pubGlobs = `${config.css.pub}/**/*.css`;

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
    noBuf: {
        base:   '.',
        buffer: false
    },
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
    options.watch.events = ['change', 'add', 'unlink', 'ready'];
    const srcWatcher = watch(config.srcGlobs, options.watch);
    srcWatcher.on('ready', () => logYellowFirst(pad('SRC', 3), scanMessage));
    srcWatcher.on('change', filePath => dispatch(change, filePath, c.bold.inverse(pad('CHANGESRC'))));
    srcWatcher.on('add', filePath => dispatch(change, filePath, c.bold.inverse(pad('ADDSRC'))));
    srcWatcher.on('unlink', filePath => dispatch(srcUnlink, filePath, c.bold.inverse(pad('DELSRC'))));


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
    {
        //* process.env.entry = JSON.stringify({name: path});
        //* process.env.entryName = 'name'; //* default 'main' in webpack.common.js
        //* process.env.entryPath = 'src/path'; //* abslute or relative path; default 'src/js/main.js' in webpack.common.js
        //* process.env.outputFilename = '[name].js'; //* default '[name].js' in webpack.common.js
        //* process.env.outputPath = config.js.pub; //* abslute or relative path, default 'public/js' in webpack.common.js
    }

    const command = 'npx webpack --config ./webpack.gulp.js';
    process.env.mode = mode;
    if (filePath.startsWith(config.js.srcMod) ||
        filePath.startsWith(config.vendor.assets)
    ) {
        const entry = {};
        return src([config.js.srcGlobs, '!' + config.js.srcModGlobs], {base: config.js.src})
            .pipe(through2.obj(function(file, enc, cb) {
                let {name} = path.parse(file.path);
                const {relDir} = getDest(file.path, file.base);
                name = path.posix.join(relDir, name);
                const entry = slash(file.path);
                const data = {[name]: entry};
                // this.push(data);
                cb(null, data);
            }))
            .on('data', data => Object.assign(entry, data))
            .on('end', () => {
                process.env.name = 'All JS files';
                process.env.entry = JSON.stringify(entry);
                process.env.outputPath = config.js.pub;
                execute(command);
            });
    } else {
        const {name} = path.parse(filePath);
        const {destDir} = getDest(filePath, config.js.src, config.js.pub);
        process.env.name = name;
        process.env.entryName = name;
        process.env.entryPath = filePath;
        process.env.outputPath = destDir;
        execute(command);
    }
}


//******************************************************************************
//*** SCSS, CSS
//******************************************************************************
function scss(filePath) {
    const globs = path.basename(filePath).startsWith('_')
        ? [config.scss.srcGlobs, '!**/_*.scss'] : filePath;
    const sourceMapPath = 'maps';
    const sourceRoot = path.posix.join('/', config.scss.src);
    return src(globs, {base: config.scss.src})
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
            sourcemaps.write(sourceMapPath, {sourceRoot}))
        )
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(config.scss.pub));
}

function css(filePath) {
    return src(filePath, {base: config.css.src})
        .pipe(gulpif(DEV, sourcemaps.init()))
        .pipe(gulpif(DEV,
            postcss([autoprefixer()]),
            postcss([autoprefixer(), cssnano()])
        ))
        .pipe(gulpif(DEV,
            sourcemaps.write('./maps', {sourceRoot: path.posix.join('/', config.css.src)})))
        .pipe(gulpif(PROD, gcmq()))
        .pipe(dest(config.css.pub));
}


function srcUnlink(filePath) {
    const ext = path.extname(filePath);
    const js   = ext === '.js';
    const scss = ext === '.scss';
    const css  = ext === '.css';

    const src = js && config.js.src || scss && config.scss.src || css && config.css.src;
    const out = js && config.js.pub || scss && config.scss.pub || css && config.css.pub;
    filePath = scss ? filePath.replace('.scss', '.css') : filePath;
    const {rel, destFile} = getDest(filePath, src, out);
    del(destFile);
    del(path.posix.join(out, 'maps', rel + '.map'));
}


//******************************************************************************
//*** FTP
//******************************************************************************

function ftpCopy(globs) {
    return USE_FTP && src(globs, options.src.noBuf)
        .pipe(ftp.dest(ftp.root))
        .on('data', file => logFile('COPYFTP', getDest(file.path).ftpFile));
}
function ftpCopyNewer(globs) {
    return USE_FTP && src(globs, options.src.noBuf)
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
    return src(filePath, {base: config.img.src})
        .pipe(dest(config.img.pub))
        // .pipe(webp(webpConfig))
        .pipe(webp())
        .pipe(dest(config.img.pub));
}
function imgUnlink(filePath) {
    const {name} = path.parse(filePath);
    const {destDir} = getDest(filePath, config.img.src, config.img.pub);
    del(path.posix.join(destDir, name + '.*'));
}


//******************************************************************************
//*** Refresh Remote Files
//******************************************************************************
function ftpRefresh(cb) {
    if (USE_FTP) {
        const cleanGlobs = config.ftpGlobs.map(item => path.posix.join(ftp.root, item));
        cleanGlobs.push('/*');
        return src(config.ftpGlobs, options.src.noBuf)
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
    const rel      = slash(path.relative(src, filePath));
    const relDir   = path.posix.dirname(rel);
    const destFile = path.posix.join(out, rel);
    const destDir  = path.posix.join(out, path.posix.dirname(rel));
    const ftpFile  = path.posix.join(ftp.root, destFile);
    const ftpDir   = path.posix.join(ftp.root, destDir);
    return {rel, relDir, destFile, ftpFile, destDir, ftpDir};
}
function slash(filePath) {
    return filePath.split(path.sep).join(path.posix.sep);
}
function resolve(relPath) {
    return slash(path.resolve(__dirname, relPath));
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
function execute(command, cb = () => {}) {
    exec(command, (error, stdout, stderr) => {
        logYellowFirst('Command:', command);
        error && logError(error);
        console.log(stdout); // eslint-disable-line no-console
        stderr && logError('stderr:', stderr);
        cb();
    });
}


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

    const globs = 'src/scss/Main/**';
    return src(globs, {base: config.scss.src})
        .pipe(dest('test/public'))
        .on('data', file => console.log(file.history));

    cb(); // work
}



exports.default = watcher;

exports.ftpRefresh = ftpRefresh;

exports.test = test;
