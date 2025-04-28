const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('homepage', [
        './assets/styles/reset.css',
        './assets/styles/homepage.css',
        './assets/styles/loader.css',
        './assets/bootstrap.js',

    ])

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .autoProvidejQuery()

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    .copyFiles([
        {
            from: './public/img',
            to: 'images/[path][name].[ext]',
        }
    ])

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()
;

module.exports = Encore.getWebpackConfig();