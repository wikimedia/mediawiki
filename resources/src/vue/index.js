( function () {
	const Vue = require( '../../lib/vue/vue.js' );
	const errorLogger = require( './errorLogger.js' );
	const i18n = require( './i18n.js' );
	const teleportTarget = require( 'mediawiki.page.ready' ).teleportTarget;

	/**
	 * Replace the given DOM element with its children.
	 *
	 * This changes the DOM structure resulting from a Vue 3-style mount to mimic the result of a
	 * Vue 2-style mount.
	 *
	 * @param {HTMLElement} el DOM element the component was mounted to
	 */
	function unwrapElement( el ) {
		if ( !el.parentNode ) {
			return;
		}
		while ( el.firstChild ) {
			el.parentNode.insertBefore( el.firstChild, el );
		}
		el.parentNode.removeChild( el );
	}

	// Only needed for Vue 2 compatibility; remove this when migrating away from the Vue 3 compat build
	Vue.use( errorLogger );
	Vue.use( i18n );

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
	 * @param {Object} options
	 * @param {...Mixed} otherArgs
	 * @return {Object} Vue app instance
	 */
	Vue.createMwApp = function ( options, ...otherArgs ) {
		const app = Vue.createApp( options, ...otherArgs );
		app.use( errorLogger );
		app.use( i18n );
		app.provide( 'CdxTeleportTarget', teleportTarget );

		if ( options.store ) {
			// Provide backwards compatibility for callers expecting Vuex 3
			mw.log.warn( 'Use of Vue.createMwApp( { store } ) is deprecated. Use Vue.createMwApp(...).use( store ) instead.' );
			mw.track( 'mw.deprecate', 'Vue.createMwApp.store' );

			app.use( options.store );
		}
		return app;
	};

	// Proxy the Vue object to intercept its constructor and the .$mount() method, to make the
	// mounting API backwards compatible with Vue 2 (the Vue 3 migration build chose not to do this
	// for some reason)
	const proxiedVue = new Proxy( Vue, {
		construct( Target, constructorArgs ) {
			mw.log.warn( 'Use of new Vue(...) is deprecated. Use Vue.createMwApp() instead.' );
			mw.track( 'mw.deprecate', 'Vue.new' );

			// Call the real Vue constructor
			const vueInstance = new Target( ...constructorArgs );

			// If this was a call like new Vue( { el: '#foo' } ), mimic Vue 2's behavior by
			// unwrapping #foo
			const [ options ] = constructorArgs;
			if ( options && options.el ) {
				// vueInstance.$el is the first child of the node identified by options.el
				unwrapElement( vueInstance.$el.parentNode );
			}

			// Proxy calls to vueInstance.$mount() so that they also mimic Vue 2's behavior
			return new Proxy( vueInstance, {
				get( target, key ) {
					if ( key === '$mount' ) {
						return function ( ...mountArgs ) {
							const mountResult = target.$mount( ...mountArgs );
							// target.$el is the first child of the node that was mounted to
							unwrapElement( target.$el.parentNode );
							return mountResult;
						};
					} else {
						return target[ key ];
					}
				}
			} );
		}
	} );

	// HACK: the global build of Vue that we're using assumes that Vue is globally available
	// in eval()ed code, because it expects var Vue = ...; to run in the global scope
	// Satisfy that assumption
	window.Vue = proxiedVue;

	module.exports = proxiedVue;
}() );
