/**
 * @class mw.Api.plugin.options
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {

		/**
		 * Asynchronously save the value of a single user option using the API. See #saveOptions.
		 *
		 * @param {string} name
		 * @param {string|null} value
		 * @return {jQuery.Promise}
		 */
		saveOption: function ( name, value ) {
			var param = {};
			param[ name ] = value;
			return this.saveOptions( param );
		},

		/**
		 * Asynchronously save the values of user options using the API.
		 *
		 * If a value of `null` is provided, the given option will be reset to the default value.
		 *
		 * Any warnings returned by the API, including warnings about invalid option names or values,
		 * are ignored. However, do not rely on this behavior.
		 *
		 * If necessary, the options will be saved using several parallel API requests. Only one promise
		 * is always returned that will be resolved when all requests complete.
		 *
		 * @param {Object} options Options as a `{ name: value, … }` object
		 * @return {jQuery.Promise}
		 */
		saveOptions: function ( options ) {
			var name, value, bundleable,
				grouped = [],
				deferreds = [];

			for ( name in options ) {
				value = options[ name ] === null ? null : String( options[ name ] );

				// Can we bundle this option, or does it need a separate request?
				bundleable =
					( value === null || value.indexOf( '|' ) === -1 ) &&
					( name.indexOf( '|' ) === -1 && name.indexOf( '=' ) === -1 );

				if ( bundleable ) {
					if ( value !== null ) {
						grouped.push( name + '=' + value );
					} else {
						// Omitting value resets the option
						grouped.push( name );
					}
				} else {
					if ( value !== null ) {
						deferreds.push( this.postWithToken( 'csrf', {
							formatversion: 2,
							action: 'options',
							optionname: name,
							optionvalue: value
						} ) );
					} else {
						// Omitting value resets the option
						deferreds.push( this.postWithToken( 'csrf', {
							formatversion: 2,
							action: 'options',
							optionname: name
						} ) );
					}
				}
			}

			if ( grouped.length ) {
				deferreds.push( this.postWithToken( 'csrf', {
					formatversion: 2,
					action: 'options',
					change: grouped
				} ) );
			}

			return $.when.apply( $, deferreds );
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.options
	 */

}( mediaWiki, jQuery ) );
