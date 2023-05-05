/*!
 * JavaScript for change credentials form.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var api = new mw.Api();

		$root.find( '.mw-changecredentials-validate-password.oo-ui-fieldLayout' ).each( function () {
			var currentApiPromise,
				self = OO.ui.FieldLayout.static.infuse( $( this ) );

			self.getField().setValidation( function ( passwordValue ) {
				if ( currentApiPromise ) {
					currentApiPromise.abort();
					currentApiPromise = undefined;
				}

				passwordValue = passwordValue.trim();

				if ( passwordValue === '' ) {
					self.setErrors( [] );
					return true;
				}

				var d = $.Deferred();
				currentApiPromise = api.post( {
					action: 'validatepassword',
					password: passwordValue,
					formatversion: 2,
					errorformat: 'html',
					errorsuselocal: true,
					uselang: mw.config.get( 'wgUserLanguage' )
				} ).done( function ( resp ) {
					var pwinfo = resp.validatepassword,
						good = pwinfo.validity === 'Good';

					currentApiPromise = undefined;

					var errors;
					if ( !good ) {
						errors = pwinfo.validitymessages.map( function ( m ) {
							return new OO.ui.HtmlSnippet( m.html );
						} );
					}
					self.setErrors( errors || [] );
					d.resolve( good );
				} ).fail( d.reject );

				return d.promise( { abort: currentApiPromise.abort } );
			} );
		} );
	} );
}() );
