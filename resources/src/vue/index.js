( function () {
	var Vue = require( '../../lib/vue/vue.js' );

	Vue.use( require( './i18n.js' ) );

	/**
	 * Track component errors that bubble up to the Vue.js runtime on the `error.vue` analytics
	 * event topic for one or more subscribers to send to an error logging service. Also log those
	 * errors to the console, which is the default behaviour of the Vue.js runtime.
	 *
	 * @see https://phabricator.wikimedia.org/T249826
	 *
	 * @param {Error} error
	 * @ignore
	 */
	Vue.config.errorHandler = function ( error ) {
		mw.errorLogger.logError( error, 'error.vue' );

		mw.log.error( error );
	};

	module.exports = Vue;
}() );
