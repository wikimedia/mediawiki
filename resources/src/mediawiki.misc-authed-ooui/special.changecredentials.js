/*!
 * JavaScript for change credentials form.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
		const api = new mw.Api();

		$root.find( '.mw-changecredentials-validate-password.oo-ui-fieldLayout' ).each( function () {
			const self = OO.ui.FieldLayout.static.infuse( $( this ) );

			let currentApiPromise;
			self.getField().setValidation( ( passwordValue ) => {
				if ( currentApiPromise ) {
					currentApiPromise.abort();
					currentApiPromise = undefined;
				}

				passwordValue = passwordValue.trim();

				if ( passwordValue === '' ) {
					self.setErrors( [] );
					return true;
				}

				const d = $.Deferred();
				currentApiPromise = api.post( {
					action: 'validatepassword',
					password: passwordValue,
					formatversion: 2,
					errorformat: 'html',
					errorsuselocal: true,
					uselang: mw.config.get( 'wgUserLanguage' )
				} ).done( ( resp ) => {
					const pwinfo = resp.validatepassword,
						good = pwinfo.validity === 'Good';

					currentApiPromise = undefined;

					let errors;
					if ( !good ) {
						errors = pwinfo.validitymessages.map( ( m ) => new OO.ui.HtmlSnippet( m.html ) );
					}
					self.setErrors( errors || [] );
					d.resolve( good );
				} ).fail( d.reject );

				return d.promise( { abort: currentApiPromise.abort } );
			} );
		} );
	} );
}() );
