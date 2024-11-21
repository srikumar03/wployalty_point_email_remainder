const webpack = require("webpack");
const react = new webpack.ProvidePlugin({
    React: "react",
});

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");

module.exports = {
    entry: "./src/index.js",
    mode: "development", // or "production" for production builds
    output: {
        path: __dirname,
        filename: "../assets/Admin/Js/dist/[name].bundle.js",
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/, // Match .js and .jsx files
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ["@babel/preset-env", "@babel/preset-react"], // Add presets here if not using a .babelrc file
                    },
                },
            },
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, "css-loader", "postcss-loader"],
            },
            {
                test: /\.(?:ico|gif|png|jpg|jpeg)$/i,
                type: 'asset/resource',
            },
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js', '.jsx'],
        alias: {
            '@': path.resolve(__dirname, ''),
        }
    },
    plugins: [
        react,
        new MiniCssExtractPlugin({
            filename: "../assets/Admin/Css/dist/style.css", // Adjust output path for CSS
        })
    ],
};

