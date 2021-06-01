'use strict';

const path = require('path');
const c    = require('ansi-colors');

// eslint-disable-next-line no-unused-vars
module.exports = function(env, argv) {
    // eslint-disable-next-line no-console
    console.log(c.cyanBright('WebpackConfig: '), __filename);

    return {
        name : 'main+admin',
        entry: {
            main : './src/js/main.js',
            admin: './src/js/admin.js',
        },
        output: {
            filename: '[name].js',
            path    : path.resolve(__dirname, './public/js'),
        },
        mode        : env.dev ? 'development' : 'production',
        devtool     : env.dev ? 'source-map' : 'nosources-source-map',
        target      : ['web', 'es6'],
        optimization: {
            minimize: env.dev ? false : true,
        },
    };
};
