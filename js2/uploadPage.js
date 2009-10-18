/*
 * This script is run on [[Special:Upload]].
 * It controls the invocation of the mvUploader class based on local config.
 */

js2AddOnloadHook( function() {
	js_log("never ran js2hook");
	mwUploadHelper.init();
});

var mwUploadFormTarget = '#mw-upload-form';
// Set up the upload form bindings once all DOM manipulation is done
var mwUploadHelper = {
	init: function() {
		var _this = this;
		// If wgEnableFirefogg is not boolean false, set to true
		if( typeof wgEnableFirefogg == 'undefined' )
		wgEnableFirefogg = true;

		if( wgEnableFirefogg ) {
			// Set up the upload handler to Firefogg. Should work with the HTTP uploads too.
			$j( '#wpUploadFile' ).firefogg( {
				// An API URL (we won't submit directly to action of the form)
				'api_url': wgServer + wgScriptPath + '/api.php',
				'form_rewrite': true,
				'target_edit_from': mwUploadFormTarget,
				'new_source_cb': function( orgFilename, oggName ) {
					if( $j( '#wpDestFile' ).val() == "" )
					$j( '#wpDestFile' ).val( oggName );
					$j( '#wpDestFile' ).doDestCheck({
						'warn_target': '#wpDestFile-warning'
					} );
				}
			} );
		} else {
			// Add basic upload profile support ( http status monitoring, progress box for
			// browsers that support it, etc.)
			if( $j( '#wpUploadFileURL' ).length != 0 ) {
				$j( '#wpUploadFileURL' ).baseUploadInterface( {
					'api_url': wgServer + wgScriptPath + '/api.php',
					'target_edit_from': mwUploadFormTarget
				} );
			}
		}

		if( wgAjaxUploadDestCheck ) {
			// Do destination check
			$j( '#wpDestFile' ).change( function() {
				$j( '#wpDestFile' ).doDestCheck({
					'warn_target':'#wpDestFile-warning'
				} );
			} );
		}

		// Check if we have HTTP enabled & setup enable/disable toggle:
		if( $j( '#wpUploadFileURL' ).length != 0 ) {
			// Set the initial toggleUpType
			_this.toggleUpType( true );

			$j( "input[name='wpSourceType']" ).click( function() {
				_this.toggleUpType( this.id == 'wpSourceTypeFile' );
			} );
		}
		$j( '#wpUploadFile,#wpUploadFileURL' )
		.focus( function() {
			_this.toggleUpType( this.id == 'wpUploadFile' );
		})
		// Also setup the onChange event binding:
		.change( function() {
			if ( wgUploadAutoFill ) {
				mwUploadHelper.doDestinationFill( this );
			}
		} );
	},
	/**
	* Set the upload radio buttons
	*
	* boolean set
	*/
	toggleUpType: function( set ) {
		$j( '#wpSourceTypeFile' ).attr( 'checked', set );
		$j( '#wpUploadFile' ).attr( 'disabled', !set );

		$j( '#wpSourceTypeURL' ).attr( 'checked', !set );
		$j( '#wpUploadFileURL' ).attr( 'disabled', set );

		// If Firefogg is enabled, toggle action according to wpSourceTypeFile selection
		if( wgEnableFirefogg ) {			
			$j( '#wpUploadFile' ).firefogg({
				'firefogg_form_action': $j( '#wpSourceTypeFile' ).attr( 'checked' )
			} );
		}
	},
	/**
	* Fill in a destination file-name based on a source asset name.
	*/
	doDestinationFill: function( targetElm ) {
		js_log( "doDestinationFill" )
		// Remove any previously flagged errors
		$j( '#mw-upload-permitted,#mw-upload-prohibited' ).hide();

		var path = $j( targetElm ).val();
		// Find trailing part
		var slash = path.lastIndexOf( '/' );
		var backslash = path.lastIndexOf( '\\' );
		var fname;
		if ( slash == -1 && backslash == -1 ) {
			fname = path;
		} else if ( slash > backslash ) {
			fname = path.substring( slash+1, 10000 );
		} else {
			fname = path.substring( backslash+1, 10000 );
		}
		// URLs are less likely to have a useful extension. Don't include them in the extension check.
		if( wgFileExtensions && $j( targetElm ).attr( 'id' ) != 'wpUploadFileURL' ) {
			var found = false;
			if( fname.lastIndexOf( '.' ) != -1 ) {
				var ext = fname.substr( fname.lastIndexOf( '.' ) + 1 );
				for( var i = 0; i < wgFileExtensions.length; i++ ) {
					if( wgFileExtensions[i].toLowerCase() == ext.toLowerCase() )
					found = true;
				}
			}
			if( !found ) {
				// Clear the upload. Set mw-upload-permitted to error.
				$j( targetElm ).val( '' );
				$j( '#mw-upload-permitted,#mw-upload-prohibited' ).show().addClass( 'error' );
				$j( '#wpDestFile' ).val( '' );
				return false;
			}
		}
		// Capitalise first letter and replace spaces by underscores
		fname = fname.charAt( 0 ).toUpperCase().concat( fname.substring( 1, 10000 ) ).replace( / /g, '_' );
		// Output result
		$j( '#wpDestFile' ).val( fname );

		// Do a destination check
		$j( '#wpDestFile' ).doDestCheck({
			'warn_target': '#wpDestFile-warning'
		} );
	}
}