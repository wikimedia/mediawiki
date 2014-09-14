/**
 * @class mw.Api.plugin.options
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {

		/**
		 * Asynchronously save the value of a single user option using the API. See #saveOptions.
		 *
		 * @param {string} name
		 * @param {string} value
		 * @return {jQuery.Promise}
		 */
		saveOption: function ( name, value ) {
			var param = {};
			param[name] = value;
			this.saveOptions( param );
		},

		/**
		 * Asynchronously save the values of user options using the API.
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
			var grouped = [], deferreds = [], name, value;

			for ( name in options ) {
				if ( options.hasOwnProperty( name ) ) {
					value = options[name] + '';
					// Can we bundle this option, or does it need a separate request?
					if (
						value.indexOf( '|' ) === -1 &&
						name.indexOf( '|' ) === -1 && name.indexOf( '=' ) === -1
					) {
						grouped.push( name + '=' + value );
					} else {
						deferreds.push( this.postWithToken( 'options', {
							action: 'options',
							optionname: name,
							optionvalue: value
						} ) );
					}
				}
			}

			deferreds.push( this.postWithToken( 'options', {
				action: 'options',
				change: grouped.join( '|' )
			} ) );

			return $.when.apply( $, deferreds );
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.options
	 */

}( mediaWiki, jQuery ) );
