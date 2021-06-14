'use strict';

const path = require('path');

const env = {
    name:           process.env.name,
    entry:          process.env.entry,
    entryName:      process.env.entryName,
    entryPath:      process.env.entryPath,
    outputFilename: process.env.outputFilename,
    outputPath:     process.env.outputPath,
    mode:           process.env.mode,
};

// env.entry = env.entry && JSON.parse(env.entry); //* Node.js 14
env.entry &&= JSON.parse(env.entry); //* Node.js 15+

delete process.env.name;
delete process.env.entry;
delete process.env.entryName;
delete process.env.entryPath;
delete process.env.outputFilename;
delete process.env.outputPath;
delete process.env.mode;

const DEV = env.mode !== 'production';

function slash(filePath) {
    return filePath.split(path.sep).join(path.posix.sep);
}
function resolve(relPath) {
    return slash(path.resolve(__dirname, relPath));
}

const defaultName              = 'main';
const defaultEntryPath         = 'src/js/main.js';
const defaultOutputFilename    = '[name].js';
const defaultOutputPath        = 'public/js';
const defaultSourceMapFilename = 'maps/[file].map';


module.exports = {
    env,
    getName:  () => env.name ?? defaultName,
    getEntry: (entry = null) =>
        entry ? resolve(entry)
            : env.entry ?? {
                [env.entryName ?? defaultName]: env.entryPath ? resolve(env.entryPath) : resolve(defaultEntryPath)
            },
    getFilename: (name = null) =>
        name ? name + '.js'
            : env.outputFilename ?? defaultOutputFilename,
    getPath: (output = null) =>
        output ? resolve(output)
            : env.outputPath ? resolve(env.outputPath)
                : resolve(defaultOutputPath),
    getSourceMapFilename: () => defaultSourceMapFilename,
    getMode:              () => DEV ? 'development' : 'production',
    getDevtool:           () => DEV ? 'source-map' : 'nosources-source-map',
    getOptimization:      () => ({minimize: !DEV}),
    getDevServer:         contentBase => ({
        contentBase: resolve(contentBase),
        overlay:     true
    }),
    resolve: {
        extensions: ['.js'], //* ['.js', '.json', '.jsx', '.css']
        alias:      {
            'assets/modules': resolve('vendor/militer/assets/src/js/modules'),
            assets:           resolve('vendor/militer/assets/src'),
            modules:          resolve('src/js/modules'),

            // a list of module name aliases
            // aliases are imported relative to the current context
            //* 'module_n'    : 'new-module',
            // alias "module_n" -> "new-module" and "module_n/path/file" -> "new-module/path/file"
            //* 'only-module$': 'new-module',
            // alias "only-module" -> "new-module", but not "only-module/path/file" -> "new-module/path/file"
            //* 'module_one'  : path.resolve(__dirname, 'app/third/module.js'),
            // alias "module_one" -> "./app/third/module.js" and "module_one/file" results in error
            //* 'module_any'  : path.resolve(__dirname, 'app/third'),
            // alias "module_any" -> "./app/third" and "module_any/file" -> "./app/third/file"
            //* [path.resolve(__dirname, 'app/module.js')]: path.resolve(__dirname, 'app/alternative-module.js'),
            // alias "./app/module.js" -> "./app/alternative-module.js"
        },
        modules: [
            resolve('vendor/militer/assets/src'),
            resolve('src/js/modules'),
            'node_modules',
        ],
    },
    target: ['web', 'es6'],
};
