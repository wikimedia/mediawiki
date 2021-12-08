/*!
 * Plugin that captures errors from Vue code and logs them to mw.errorLogger
 */
module.exports = {
	install: function ( app ) {
		/**
		 * Track component errors that bubble up to the Vue.js runtime on the `error.vue` analytics
		 * event topic for one or more subscribers to send to an error logging service. Also log those
		 * errors to the console, which is the default behaviour of the Vue.js runtime.
		 *
		 * See also <https://phabricator.wikimedia.org/T249826>.
		 *
		 * @ignore
		 * @param {Error} error
		 */
		app.config.errorHandler = function ( error ) {
			mw.errorLogger.logError( error, 'error.vue' );

			mw.log.error( error );
		};
	}
};
