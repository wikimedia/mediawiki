( function () {
	const Vue = require( '../../lib/vue/vue.js' );
	const errorLogger = require( './errorLogger.js' );
	const i18n = require( './i18n.js' );
	const teleportTarget = require( 'mediawiki.page.ready' ).teleportTarget;

	/**
	 * @class Vue
	 */
	/**
	 * Wrapper around Vue.createApp() that adds the i18n plugin and the error handler.
	 *
	 * These were added globally in Vue 2, but Vue 3 does not support global plugins.
	 * To ensure all Vue code has the i18n plugin and the error handler installed, use of
	 * Vue.createMwApp() is recommended anywhere one would normally use Vue.createApp().
	 *
	 * @param {...Mixed} args
	 * @return {Object} Vue app instance
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
