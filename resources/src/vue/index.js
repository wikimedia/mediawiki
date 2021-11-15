( function () {
	var Vue = require( '../../lib/vue/vue.js' );

	Vue.use( require( './errorLogger.js' ) );
	Vue.use( require( './i18n.js' ) );

	/**
	 * @class Vue
	 */
	/**
	 * Wrapper that imitates Vue.createApp(), to make the Vue 2 -> Vue 3 migration smoother.
	 *
	 * Vue.createApp() is introduced in Vue 3. This returns an object that provides part of the
	 * same API, so that we can swap out Vue 3 for Vue 2 without needing to change callers.
	 * After the Vue 3 migration, this function will stay as a wrapper around Vue.createApp()
	 * that adds the i18n plugin and the error handler, because those can't be added globally
	 * in Vue 3.
	 *
	 * To create and mount an app that doesn't use Vuex:
	 *     var RootComponent = require( './RootComponent.vue' ),
	 *     Vue.createMwApp( RootComponent )
	 *         .mount( '#foo' ); // CSS selector of mount point, or DOM element
	 *
	 * To create and mount an app with Vuex:
	 *     var RootComponent = require( './RootComponent.vue' ),
	 *         store = require( './store.js' );
	 *     Vue.createMwApp( RootComponent )
	 *         .use( store )
	 *         .mount( '#foo' );
	 *
	 * To pass props to the component, pass them as the second parameter to createMwApp():
	 *    Vue.createMwApp( RootComponent, { pageName: 'foo', disabled: false } );
	 *
	 * @param {Object} componentOptions Vue component options object
	 * @param {Object} [propsData] Props to pass to the component
	 * @return {Object} Object that pretends to be a Vue 3 app object, supports .use() and .mount()
	 */
	Vue.createMwApp = function ( componentOptions, propsData ) {
		var App = Vue.extend( componentOptions ),
			finalOptions = {};

		if ( propsData ) {
			finalOptions.propsData = propsData;
		}

		// Wrap .use(), so we can redirect app.use( VuexStore )
		App.use = function ( plugin ) {
			if (
				typeof plugin !== 'function' && typeof plugin.install !== 'function' &&
				// eslint-disable-next-line no-underscore-dangle
				plugin._actions && plugin._mutations
			) {
				// This looks like a Vuex store, store it for later use.
				finalOptions.store = plugin;
				return this;
			}
			Vue.use.apply( Vue, arguments );
			return App;
		};

		App.mount = function ( elementOrSelector, hydrating ) {
			// Mimic the Vue 3 behavior of appending to the element rather than replacing it
			// Add a div to the element, and pass that to .$mount() so that it gets replaced.
			var wrapperElement = document.createElement( 'div' ),
				parentElement = typeof elementOrSelector === 'string' ?
					document.querySelector( elementOrSelector ) : elementOrSelector;
			if ( !parentElement || !parentElement.appendChild ) {
				throw new Error( 'Cannot find element: ' + elementOrSelector );
			}
			// Remove any existing children from parentElement.
			while ( parentElement.firstChild ) {
				parentElement.removeChild( parentElement.firstChild );
			}
			parentElement.appendChild( wrapperElement );
			var app = new App( finalOptions );
			app.$mount( wrapperElement, hydrating );
			return app;
		};
		return App;
	};

	module.exports = Vue;
}() );
