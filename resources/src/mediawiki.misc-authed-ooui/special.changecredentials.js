/*!
 * JavaScript for change credentials form.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var api = new mw.Api();

		$root.find( '.mw-changecredentials-validate-password.oo-ui-fieldLayout' ).each( function () {
			var currentApiPromise,
				self = OO.ui.FieldLayout.static.infuse( $( this ) );

			self.getField().setValidation( function ( password ) {
				var d;

				if ( currentApiPromise ) {
					currentApiPromise.abort();
					currentApiPromise = undefined;
				}

				password = password.trim();

				if ( password === '' ) {
					self.setErrors( [] );
					return true;
				}

				d = $.Deferred();
				currentApiPromise = api.post( {
					action: 'validatepassword',
					password: password,
					formatversion: 2,
					errorformat: 'html',
					errorsuselocal: true,
					uselang: mw.config.get( 'wgUserLanguage' )
				} ).done( function ( resp ) {
					var errors,
						pwinfo = resp.validatepassword,
						good = pwinfo.validity === 'Good';

					currentApiPromise = undefined;

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
