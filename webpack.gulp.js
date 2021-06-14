'use strict';

const c      = require('ansi-colors');
const common = require('./webpack.common');

console.log(c.cyanBright('WebpackConfig:'), __filename); // eslint-disable-line no-console
console.log(c.greenBright('Mode:'), common.getMode()); // eslint-disable-line no-console

// console.log('common.env: ', common.env);

module.exports = {
    name:   common.getName(), //* 'name'; default 'main'
    entry:  common.getEntry(), //* common.getEntry('rel/entry'); default 'src/js/main.js'
    output: {
        filename:          common.getFilename(), //* common.getFilename('name') | 'name.js'; default '[name].js'
        path:              common.getPath(), //*  common.getPath('rel/path') | './rel/path'; default 'public/js'
        sourceMapFilename: common.getSourceMapFilename(), //* default 'maps/[file].map'
    },
    mode:         common.getMode(),
    resolve:      common.resolve,
    optimization: common.getOptimization(),
    devtool:      common.getDevtool(),
    target:       common.target,
};
