module.exports = {
    mode: 'production',
    entry: './resources/js/app.js',
    plugins: [
        new Webpack.ProvidePlugin({
            $: 'jquery',
        }),
    ],
};
