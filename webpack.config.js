const path = require('path');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const isDevelopment = process.env.NODE_ENV === 'development';

const pluginOptions = isDevelopment
	? [
			new MiniCssExtractPlugin({
				filename: '../css/[name].css',
			}),
			new BrowserSyncPlugin({
				host: 'localhost',
				port: 3000,
				proxy:
					'http://localhost/ceglos' /** IF ANYONE ELSE ENDS UP USING THIS, JUST CHANGE THE PROXY TO YOUR LOCALHOST FOR CEGLOS TO GET BROWSER SYNC TO WORK */,
				files: ['**/*.php', './dist/css/*.css', './dist/js/*.js'],
			}),
	  ]
	: [
			new MiniCssExtractPlugin({
				filename: '../css/[name].css',
			}),
	  ];

module.exports = {
	entry: path.resolve(__dirname, 'src', 'js', 'index.js'),
	output: {
		filename: 'bundle.js',
		path: path.resolve(__dirname, 'dist', 'js'),
		publicPath: './',
	},
	plugins: pluginOptions,
	watch: isDevelopment,
	devtool: 'source-map',
	mode: isDevelopment ? 'development' : 'production',
	module: {
		rules: [
			{
				test: /\.m?js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
					},
				},
			},
			{
				test: /\.s[ac]ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'postcss-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'resolve-url-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: true,
							sassOptions: {
								outputStyle: 'expanded',
							},
						},
					},
				],
			},
			{
				test: /\.(png|jpg|gif)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: '../images',
						},
					},
				],
			},
		],
	},
};
