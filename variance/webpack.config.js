const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    mode: 'development',
    // devtool: 'inline-source-map',
    entry: {
        frontend : ['./src/index.js', './src/sass/style.scss'],
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'public'),
    },
    module: {
        rules: [
            {
                test: /\.s?css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ],
            },
            {
                test: /\.(png|svg|jp?g|gif)$/,
                use: {
                    loader : 'url-loader',
                    options: {
                        limit: 8000,
                        name: "images/[name].[ext]",
                    },
                },
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: {
                    loader : 'file-loader',
                    options: {
                        name: "fonts/[name].[ext]",
                    },
                },
            }
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css'
        }),
    ],
};