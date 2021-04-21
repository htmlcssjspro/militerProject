'use strict';

const path = require('path');

module.exports = function(env, argv) {

    const mode       = (env && env.development) ? 'development' : 'production';
    const outputPath = path.resolve(__dirname, './public/js');
    const publicPath = 'js/';
    const output = {
        filename  : '[name].js',
        path      : outputPath,
        publicPath: publicPath
    };
    const devServer  = {
        contentBase: path.resolve(__dirname, './public'),
        overlay    : true
    };
    const devtool = 'source-map';
    const target = ['web', 'es6'];
    const optimization = {
        minimize: true, // *true, false
    };

    return [
        {
            name : 'main',
            entry: {
                main: './src/js/index.js'
            },
            output      : output,
            mode        : mode,
            devServer   : devServer,
            devtool     : devtool,
            target      : target,
            optimization: optimization,
        },
        {
            name : 'admin',
            entry: {
                admin: './src/js/admin.js'
            },
            output      : output,
            devServer   : devServer,
            mode        : mode,
            devtool     : devtool,
            target      : target,
            optimization: optimization,
        },
        // {
        //     name  : 'babel',
        //     entry : ['core-js/stable', 'regenerator-runtime/runtime', './src/js/index.js'],
        //     output: {
        //         filename  : 'main.babel.js',
        //         path      : outputPath,
        //         publicPath: publicPath
        //     },
        //     mode     : mode,
        //     devServer: devServer,
        //     devtool  : devtool,
        //     module   : {
        //         rules: [
        //             {
        //                 test   : /\.js$/,
        //                 exclude: /node_modules/,
        //                 use    : {
        //                     loader : 'babel-loader',
        //                     options: {
        //                         presets: ['@babel/preset-env'],
        //                         plugins: ['@babel/plugin-proposal-class-properties']
        //                     }
        //                 }
        //             }
        //         ]
        //     }
        // },
    ];
};
