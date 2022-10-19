const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
	map: true,
	plugins: [...(isProduction ? [require('autoprefixer'), require('cssnano')] : [])],
};
