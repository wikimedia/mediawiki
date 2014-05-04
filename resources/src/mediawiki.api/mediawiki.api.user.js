/**
 * Get information about the currently logged-in user,
 * and set user options as well, with ease.
 * @author Ricordisamoa
 * @class mw.Api.plugin.user
 */
( function ( mw, $ ) {
	'use strict';

	$.extend( mw.Api.prototype, {
		/**
		 * Return information about the currently logged-in user.
		 * @param {string|string[]} prop which properties to get
		 * @return {jQuery.Promise} See mw.Api#post
		 */
		getUserInfo: function ( prop ) {
			var deferred = new $.Deferred();

			this.post( {
				meta: 'userinfo',
				uiprop: $.isArray( prop ) ? prop.join( '|' ) : prop
			} )
			.done( function ( data ) {
				if ( data.query && data.query.userinfo ) {
					deferred.resolve( data.query.userinfo );
				} else {
					var code = data.error && data.error.code || 'unknown';
					deferred.reject( code, data );
				}
			} );

			return deferred.promise();
		},

		/**
		 * Change options for the currently logged-in user.
		 * @param {Object} change which options to set, in form of optionname=optionvalue pairs;
		 *  use null as optionvalue to reset it to the site default.
		 * @param {boolean} [reset] resets all preferences to the site defaults
		 * @return {jQuery.Promise} See mw.Api#post
		 */
		setOptions: function ( change, reset ) {
			var params = {
				action: 'options'
			};

			if ( reset ) {
				params.reset = 1;
			}

			if ( change ) {
				params.change = $.map( change, function ( value, name ) {
					if ( value !== null && String(value).indexOf( '|' ) !== -1 ) {
						params.optionname = name;
						params.optionvalue = value;
					} else {
						return name + ( value === null ? '' : '=' + value );
					}
				} )
				.join( '|' );
			}

			return this.postWithToken( 'options', params );
		},

		/**
		 * Update mw.user.options with live data.
		 * @return {jQuery.Promise} See mw.Api#post
		 */
		updateOptions: function () {
			return this.getUserInfo( 'options' )
				.done( function ( data ) {
					$.extend( mw.user.options.values, data.options || {} );
				} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.user
	 */

}( mediaWiki, jQuery ) );
