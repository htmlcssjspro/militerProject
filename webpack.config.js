'use strict';

const path = require('path');
const c    = require('ansi-colors');

// eslint-disable-next-line no-unused-vars
module.exports = function(env, argv) {
    // eslint-disable-next-line no-console
    console.log(c.cyanBright('WebpackConfig: '), __filename);
    // const mode = (argv.mode && argv.mode === 'development') ? 'development' : 'production';
    const mode =
        env.dev || (argv.mode && argv.mode === 'development')
            ? 'development' : 'production';
    // const dev = mode === 'development';
    const output = {
        filename  : '[name].js',
        path      : path.resolve(__dirname, './public/js'),
        publicPath: 'js/'
    };
    const devServer  = {
        contentBase: path.resolve(__dirname, './public'),
        overlay    : true
    };
    const devtool = env.dev ? 'source-map' : 'nosources-source-map';
    const target = ['web', 'es6'];
    const optimization = {
        minimize: env.dev ? false : true
    };

    return [
        {
            name : 'main',
            entry: {
                main: './src/js/main.js'
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
