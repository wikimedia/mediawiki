( function () {
	const Vue = require( '../../lib/vue/vue.js' );
	const errorLogger = require( './errorLogger.js' );
	const i18n = require( './i18n.js' );
	const teleportTarget = require( 'mediawiki.page.ready' ).teleportTarget;

	/**
	 * Additional functions and plugins added to the Vue object.
	 *
	 * For documentation on Vue's built-in functions, see
	 * {@link https://vuejs.org/api/ Vue's API reference}.
	 *
	 * @module Vue
	 */

	/**
	 * Wrapper around {@link https://vuejs.org/api/application.html#createapp Vue.createApp} that
	 * adds the {@link Vue#$i18n i18n plugin} and the error handler. These were added
	 * globally in Vue 2, but Vue 3 does not support global plugins.
	 *
	 * To ensure all Vue code has the i18n plugin and the error handler installed, use of
	 * `Vue.createMwApp()` is recommended anywhere one would normally use `Vue.createApp()`.
	 *
	 * @method createMwApp
	 * @param {...any} args
	 * @return {Object} Vue app instance
	 * @memberof module:Vue
	 */
	Vue.createMwApp = function ( ...args ) {
		const app = Vue.createApp( ...args );
		app.use( errorLogger );
		app.use( i18n );
		app.provide( 'CdxTeleportTarget', teleportTarget );

		return app;
	};

	// HACK: the global build of Vue that we're using assumes that Vue is globally available
	// in eval()ed code, because it expects var Vue = ...; to run in the global scope
	// Satisfy that assumption
	window.Vue = Vue;

	module.exports = Vue;
}() );
