( function () {
	var Vue = require( '../../lib/vue/vue.js' );

	Vue.use( require( './i18n.js' ) );

	/**
	 * Log errors thrown by Vue.js components to the `global.error` analytics event topic and to the
	 * console.
	 *
	 * @see https://phabricator.wikimedia.org/T249826
	 *
	 * @ignore
	 *
	 * @param {Mixed} error
	 */
	Vue.config.errorHandler = function ( error ) {
		mw.errorLogger.logError( error );

		// eslint-disable-next-line no-console
		console.error( error );
	};

	module.exports = Vue;
}() );
