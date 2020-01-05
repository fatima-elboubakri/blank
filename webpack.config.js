const Encore = require('@symfony/webpack-encore');

Encore
    .enableSingleRuntimeChunk()
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
