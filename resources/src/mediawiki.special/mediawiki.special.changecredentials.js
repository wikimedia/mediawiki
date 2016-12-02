/*!
 * JavaScript for change credentials form.
 */
( function ( mw, $, OO ) {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		$root.find( '.mw-changecredentials-validate-password.oo-ui-fieldLayout' ).each( function () {
			var self = OO.ui.FieldLayout.static.infuse( $( this ) ),
				api = new mw.Api();

			self.fieldWidget.setValidation( function ( password ) {
				var d, apiPromise;

				password = $.trim( password );

				if ( password === '' ) {
					self.setErrors( [] );
					return true;
				}

				d = $.Deferred();
				apiPromise = api.post( {
					action: 'validatepassword',
					password: password,
					formatversion: 2,
					errorformat: 'html',
					errorsuselocal: true,
					uselang: mw.config.get( 'wgUserLanguage' )
				} ).done( function ( resp ) {
					var pwinfo = resp.validatepassword,
						good = pwinfo.validity === 'Good',
						errors = [];

					if ( !good ) {
						pwinfo.validitymessages.map( function ( m ) {
							errors.push( new OO.ui.HtmlSnippet( m.html ) );
						} );
					}
					self.setErrors( errors );
					d.resolve( good );
				} ).fail( d.reject );

				return d.promise( { abort: apiPromise.abort } );
			} );
		} );
	} );
}( mediaWiki, jQuery, OO ) );
