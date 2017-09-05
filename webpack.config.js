var Encore = require('@symfony/webpack-encore');

Encore
// directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/build/app.js
    .addEntry('app', './web/assets/js/main.js')

    // will output as web/build/global.css
    //.addStyleEntry('global', './web/assets/css/global.scss')

    // allow sass/scss files to be processed
    //.enableSassLoader()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())


// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;

// export the final configuration
let webpackConfig = Encore.getWebpackConfig()

webpackConfig.module.rules.push({
    test: /\.vue$/,
    loader: 'vue-loader'
})

module.exports = webpackConfig