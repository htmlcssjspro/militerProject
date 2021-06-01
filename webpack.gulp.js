'use strict';

const path = require('path');
const c    = require('ansi-colors');

// eslint-disable-next-line no-unused-vars
module.exports = function(env, argv) {
    // eslint-disable-next-line no-console
    console.log(c.cyanBright('WebpackConfig: '), __filename);

    return {
        name  : env.name,
        entry : `./${env.entry}`,
        output: {
            filename: `${env.name}.js`,
            path    : path.resolve(__dirname, `./${env.output}`),
        },
        mode        : env.dev ? 'development' : 'production',
        devtool     : env.dev ? 'source-map' : 'nosources-source-map',
        target      : ['web', 'es6'],
        optimization: {
            minimize: env.dev ? false : true,
        },
    };
};
