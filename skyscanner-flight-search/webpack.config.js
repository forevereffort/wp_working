const path = require('path');

module.exports = {
    mode: 'development',
    entry : {
        frontend : ['./assets/src/frontend/index.js']
    },
    output : {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'assets/public'),
    },
    module : {
        rules : [
            {
                exclude: /node_modules/,
                loader: 'babel-loader',
                options: {
                    presets: [
                        '@babel/preset-env',
                        '@babel/react',
                        {
                            'plugins': ['@babel/plugin-proposal-class-properties']
                        }
                    ]
                }
            },
            {
                test: /\.s?css$/,
                use: [
                    'style-loader',
                    'css-loader',
                    'sass-loader'
                ],
            },
            {
                test: /\.(png|svg|jp?g|gif)$/,
                use: [
                    {
                        loader : 'url-loader',
                        options: {
                            limit: 80000,
                            name: "images/[name].[ext]",
                        },
                    }
                ],
            }
        ]
    }
}