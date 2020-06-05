// https://blog.madewithenvy.com/getting-started-with-webpack-2-ed2b86c68783#.hyxiqhrhk
const webpack = require('webpack');
const glob = require('glob-all');
const path = require('path');

const inProduction = (process.env.NODE_ENV === 'production');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const { WebpackBundleSizeAnalyzerPlugin } = require('webpack-bundle-size-analyzer');
const ManifestPlugin = require('webpack-manifest-plugin');
const PurgecssPlugin = require('purgecss-webpack-plugin');

// entry points build backwards, so last in array comes first

const nodeModules = path.resolve(__dirname, './node_modules');

const entryPoints = {
    vendor: ['./assets/vendor/index.js'],
    application: ['./assets/application.js', './assets/core/index.js'],
    admin: ['./assets/admin/application.js', './assets/admin/core/index.js'],
};

// optional public componentClasses
const optionalComponents = [];

optionalComponents.forEach((file) => {
    entryPoints[file] = `./assets/public/optional/components/js/${file}.js`;
});

const adminOptionalComponents = [];

adminOptionalComponents.forEach((file) => {
    entryPoints[file] = `./src/admin/optional/components/js/${file}.js`;
});

const optionalFiles = [];

optionalFiles.forEach((file) => {
    entryPoints[file] = `./src/public/optional/components/${file}.js`;
});

class TailwindExtractor {
    static extract(content) {
        // return content.match(/[A-z0-9-:\/]+/g);
        return content.match(/[\w-/:]+(?<!:)/g) || [];
        // return content.match(/[A-z0-9-:\/]+/g) || [];
    }
}

module.exports = {
    devtool: inProduction ? false : 'source-map',
    node: { fs: 'empty' },
    watchOptions: {
        poll: 1000,
        aggregateTimeout: 1000,
        ignored: nodeModules,
    },
    context: path.resolve(__dirname, './resources'),
    resolve: {
        modules: [
            path.resolve(__dirname, './resources'),
            nodeModules,
        ],
        extensions: ['.ts', '.tsx', '.js', '.json'],
    },
    entry: entryPoints,
    output: {
        path: path.resolve(__dirname, './public/assets'),
        filename: '[name].[chunkhash].js',
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    name: 'vendor',
                    test: 'vendor',
                    enforce: true,
                },
            },
        },
    },
    module: {
        rules: [
            // {
            //     test: /\.css$/,
            //     use: ['source-map-loader'],
            //     enforce: 'pre',
            // },
            {
                test: /\.p?css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        // options: {
                        //     ident: 'postcss',
                        //     plugins: [
                        //         require('postcss-import'),
                        //         require('tailwindcss'),
                        //         require('postcss-nested'),
                        //         require('autoprefixer'),
                        //     ],
                        // },
                    },
                ],
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'images/[name].[chunkhash].[ext]',
                        },
                    },
                    'image-webpack-loader',
                ],
            },
            {
                test: /\.(eot|ttf|woff|woff2)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: 'fonts/[name].[chunkhash].[ext]',
                    },
                },
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: 'babel-loader',
            },
            {
                test: /\.tsx?$/,
                exclude: /node_modules/,
                use: 'ts-loader',
            },
        ],
    },

    plugins: [
        // new WebpackBundleSizeAnalyzerPlugin('./plain-report.txt'),
        // new CopyWebpackPlugin([
        //     {
        //         from: `${nodeModules}/intercooler/src/intercooler.js`,
        //         to: '[name].[ext]'
        //     }
        // ], {
        //     copyUnmodified: true
        // }),
        new webpack.optimize.OccurrenceOrderPlugin(),
        new CleanWebpackPlugin(['assets'], {
            root: path.resolve(__dirname, './public'),
            verbose: true,
            dry: false,
        }),
        new MiniCssExtractPlugin({
            filename: '[name].[contenthash].css',
        }),
        new WebpackBundleSizeAnalyzerPlugin(),
        new ManifestPlugin(),
    ],
};

if (inProduction) {
    module.exports.plugins.push(
        // see https://github.com/erikdesjardins/zip-webpack-plugin
        // new ZipPlugin(),
        // new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
        new webpack.NoEmitOnErrorsPlugin(),

        // make css smaller
        new OptimizeCssAssetsPlugin({
            cssProcessor: require('cssnano'),
            cssProcessorOptions: { discardComments: { removeAll: true } },
            canPrint: true,
        }),

        // remove unwanted CSS
        // see: https://gist.github.com/andrewdelprete/d2f44d0c7f120aae1b8bd87cbf0e3bc8

        // new PurgecssPlugin({
        //     paths: glob.sync([
        //         path.join(__dirname, 'resources/views/**/*.blade.php'),
        //         path.join(__dirname, 'src/**/*.php'),
        //     ]),
        //     // need to whitelist any classes injected via @svg
        //     whitelist: whiteListString.split(' '),
        //     extractors: [
        //         {
        //             extractor: TailwindExtractor,
        //
        //             // Specify the file extensions to include when scanning for
        //             // class names.
        //             extensions: ['html', 'js', 'php', 'vue'],
        //         },
        //     ],
        // }),
    );
}
