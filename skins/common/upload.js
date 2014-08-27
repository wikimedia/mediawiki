/*jshint camelcase:false */
( function ( mw, $ ) {
	var uploadWarning, uploadLicense;

	uploadWarning = {
		responseCache: { '': '&nbsp;' },
		nameToCheck: '',
		typing: false,
		delay: 500, // ms
		timeoutID: false,

		keypress: function () {
			if ( !mw.config.get( 'wgAjaxUploadDestCheck' ) ) {
				return;
			}

			// Find file to upload
			if ( !$( '#wpDestFile' ) || !$( '#wpDestFile-warning' ) ) {
				return;
			}

			this.nameToCheck = $( '#wpDestFile' ).val();

			// Clear timer
			if ( this.timeoutID ) {
				clearTimeout( this.timeoutID );
			}
			// Check response cache
			if ( this.responseCache[this.nameToCheck] !== undefined ) {
				this.setWarning(this.responseCache[this.nameToCheck]);
				return;
			}

			this.timeoutID = setTimeout( function () {
				this.timeout();
			}.bind( this ), this.delay );
		},

		checkNow: function ( fname ) {
			if ( !mw.config.get( 'wgAjaxUploadDestCheck' ) ) {
				return;
			}
			if ( this.timeoutID ) {
				clearTimeout( this.timeoutID );
			}
			this.nameToCheck = fname;
			this.timeout();
		},

		timeout: function () {
			var $spinnerDestCheck;
			if ( !mw.config.get( 'wgAjaxUploadDestCheck' ) || this.nameToCheck === '' ) {
				return;
			}
			$spinnerDestCheck = $.createSpinner().insertAfter( '#wpDestFile' );

			( new mw.Api() ).get( {
				action: 'query',
				titles: ( new mw.Title( this.nameToCheck, mw.config.get( 'wgNamespaceIds' ).file ) ).getPrefixedText(),
				prop: 'imageinfo',
				iiprop: 'uploadwarning',
				indexpageids: ''
			} ).done( function ( result ) {
				var resultOut = '';
				if ( result.query ) {
					resultOut = result.query.pages[result.query.pageids[0]].imageinfo[0];
				}
				$spinnerDestCheck.remove();
				this.processResult( resultOut, this.nameToCheck );
			}.bind( this ) );
		},

		processResult: function ( result, fileName ) {
			this.setWarning( result.html );
			this.responseCache[fileName] = result.html;
		},

		setWarning: function ( warning ) {
			$( '#wpDestFile-warning' ).html( warning );

			// Set a value in the form indicating that the warning is acknowledged and
			// doesn't need to be redisplayed post-upload
			if ( !warning ) {
				$( '[name=wpDestFileWarningAck]' ).val( '' );
			} else {
				$( '[name=wpDestFileWarningAck]' ).val( '1' );
			}

		}
	};
	mw.config.set( 'wgUploadWarningObj', uploadWarning );

	uploadLicense = {

		responseCache: { '': '' },

		fetchPreview: function ( license ) {
			var $spinnerLicense;
			if ( !mw.config.get( 'wgAjaxLicensePreview' ) ) {
				return;
			}
			if ( this.responseCache[license] !== undefined ) {
				this.showPreview( this.responseCache[license] );
				return;
			}

			$spinnerLicense = $.createSpinner().insertAfter( '#wpLicense' );

			( new mw.Api() ).get( {
				action: 'parse',
				text: '{{' + license + '}}',
				title: $( '#wpDestFile' ).val() || 'File:Sample.jpg',
				prop: 'text',
				pst: ''
			} ).done( function ( result ) {
				$spinnerLicense.remove();
				this.processResult( result, license );
			}.bind( this ) );
		},

		processResult: function ( result, license ) {
			this.responseCache[license] = result.parse.text['*'];
			this.showPreview( this.responseCache[license] );
		},

		showPreview: function ( preview ) {
			$( '#mw-license-preview' ).html( preview );
		}

	};

	$( function () {
		// Disable URL box if the URL copy upload source type is not selected
		if ( $( '#wpSourceTypeurl' ) && !$( '#wpSourceTypeurl' ).prop( 'checked' ) ) {
			if ( $( '#wpUploadFileURL' ) ) {
				$( '#wpUploadFileURL' ).prop( 'disabled', true );
			}
		}

		// For MSIE/Mac: non-breaking spaces cause the <option> not to render.
		// But for some reason, setting the text to itself works
		if (
			$( '#wpLicense' ) &&
			navigator.userAgent.indexOf( 'MSIE' ) !== -1 &&
			navigator.userAgent.indexOf( 'Mac' ) !== -1
		) {
			$( '#wpLicense option' ).each( function () {
				$( this ).text( $( this ).text() );
			} );
		}

		// AJAX wpDestFile warnings
		if ( mw.config.get( 'wgAjaxUploadDestCheck' ) ) {
			// Insert an event handler that fetches upload warnings when wpDestFile
			// has been changed
			$( '#wpDestFile' ).change( function () {
				uploadWarning.checkNow( $( this ).val() );
			} );
			// Insert a row where the warnings will be displayed just below the
			// wpDestFile row
			$( '#mw-htmlform-description tbody' ).append(
				$( '<tr>' ).append(
					$( '<td>' )
						.attr( 'id', 'wpDestFile-warning' )
						.attr( 'colspan', 2 )
				)
			);
		}

		if ( mw.config.get( 'wgAjaxLicensePreview' ) && $( '#wpLicense' ) ) {
			// License selector check
			$( '#wpLicense' ).change( function () {
				// We might show a preview
				uploadLicense.fetchPreview( $( '#wpLicense' ).val() );
			} );

			// License selector table row
			$( '#wpLicense' ).parent().parent().after(
				$( '<tr>' ).append(
					$( '<td>' ),
					$( '<td>' ).attr( 'id', 'mw-license-preview' )
				)
			);
		}

		// fillDestFile setup
		$.each( mw.config.get( 'wgUploadSourceIds' ), function () {
			$( this ).change( function () {
				var path, slash, backslash, fname;
				if ( !mw.config.get( 'wgUploadAutoFill' ) ) {
					return;
				}
				// Remove any previously flagged errors
				$( '#mw-upload-permitted' ).attr( 'class', '' );
				$( '#mw-upload-prohibited' ).attr( 'class', '' );

				path = $( this ).val();
				// Find trailing part
				slash = path.lastIndexOf( '/' );
				backslash = path.lastIndexOf( '\\' );
				if ( slash === -1 && backslash === -1 ) {
					fname = path;
				} else if ( slash > backslash ) {
					fname = path.substring( slash + 1 );
				} else {
					fname = path.substring( backslash + 1 );
				}

				// Clear the filename if it does not have a valid extension.
				// URLs are less likely to have a useful extension, so don't include them in the
				// extension check.
				if ( mw.config.get( 'wgStrictFileExtensions' ) && $( this ).attr( 'id' ) !== 'wpUploadFileURL' ) {
					if (
						fname.lastIndexOf( '.' ) === -1 ||
						$.map( mw.config.get( 'wgFileExtensions' ), function ( element ) {
							return element.toLowerCase();
						} ).indexOf( fname.substr( fname.lastIndexOf( '.' ) + 1 ).toLowerCase() ) === -1
					) {
						// Not a valid extension
						// Clear the upload and set mw-upload-permitted to error
						$( this ).val( '' );
						$( '#mw-upload-permitted' ).attr( 'class', 'error' );
						$( '#mw-upload-prohibited' ).attr( 'class', 'error' );
						// Clear wpDestFile as well
						$( '#wpDestFile' ).val( '' );

						return false;
					}
				}

				// Replace spaces by underscores
				fname = fname.replace( / /g, '_' );
				// Capitalise first letter if needed
				if ( mw.config.get( 'wgCapitalizeUploads' ) ) {
					fname = fname.charAt( 0 ).toUpperCase().concat( fname.substring( 1 ) );
				}

				// Output result
				if ( $( '#wpDestFile' ) ) {
					// Call decodeURIComponent function to remove possible URL-encoded characters
					// from the file name (bug 30390). Especially likely with upload-form-url.
					// decodeURIComponent can throw an exception if input is invalid utf-8
					try {
						$( '#wpDestFile' ).val( decodeURIComponent( fname ) );
					} catch ( err ) {
						$( '#wpDestFile' ).val( fname );
					}
					uploadWarning.checkNow( fname );
				}
			} );
		} );
	} );
}( mediaWiki, jQuery ) );
