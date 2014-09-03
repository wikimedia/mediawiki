/*jshint camelcase:false */
( function ( mw, $ ) {
	var ajaxUploadDestCheck = mw.config.get( 'wgAjaxUploadDestCheck' ),
		$license = $( '#wpLicense' ), uploadWarning, uploadLicense;

	window.wgUploadWarningObj = uploadWarning = {
		responseCache: { '': '&nbsp;' },
		nameToCheck: '',
		typing: false,
		delay: 500, // ms
		timeoutID: false,

		keypress: function () {
			if ( !ajaxUploadDestCheck ) {
				return;
			}

			// Find file to upload
			if ( !$( '#wpDestFile' ).length || !$( '#wpDestFile-warning' ).length ) {
				return;
			}

			this.nameToCheck = $( '#wpDestFile' ).val();

			// Clear timer
			if ( this.timeoutID ) {
				clearTimeout( this.timeoutID );
			}
			// Check response cache
			if ( this.responseCache.hasOwnProperty( this.nameToCheck ) ) {
				this.setWarning( this.responseCache[this.nameToCheck] );
				return;
			}

			this.timeoutID = setTimeout( function () {
				uploadWarning.timeout();
			}, this.delay );
		},

		checkNow: function ( fname ) {
			if ( !ajaxUploadDestCheck ) {
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
			if ( !ajaxUploadDestCheck || this.nameToCheck === '' ) {
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
				uploadWarning.processResult( resultOut, uploadWarning.nameToCheck );
			} );
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
				$( '#wpDestFileWarningAck' ).val( '' );
			} else {
				$( '#wpDestFileWarningAck' ).val( '1' );
			}

		}
	};

	uploadLicense = {

		responseCache: { '': '' },

		fetchPreview: function ( license ) {
			var $spinnerLicense;
			if ( !mw.config.get( 'wgAjaxLicensePreview' ) ) {
				return;
			}
			if ( this.responseCache.hasOwnProperty( license ) ) {
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
				uploadLicense.processResult( result, license );
			} );
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
		if ( !$( '#wpSourceTypeurl' ).prop( 'checked' ) ) {
			$( '#wpUploadFileURL' ).prop( 'disabled', true );
		}

		// AJAX wpDestFile warnings
		if ( ajaxUploadDestCheck ) {
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

		if ( mw.config.get( 'wgAjaxLicensePreview' ) && $license.length ) {
			// License selector check
			$license.change( function () {
				// We might show a preview
				uploadLicense.fetchPreview( $license.val() );
			} );

			// License selector table row
			$license.closest( 'tr' ).after(
				$( '<tr>' ).append(
					$( '<td>' ),
					$( '<td>' ).attr( 'id', 'mw-license-preview' )
				)
			);
		}

		// fillDestFile setup
		$.each( mw.config.get( 'wgUploadSourceIds' ), function ( index, sourceId ) {
			$( '#' + sourceId ).change( function () {
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
					fname = path.slice( slash + 1 );
				} else {
					fname = path.slice( backslash + 1 );
				}

				// Clear the filename if it does not have a valid extension.
				// URLs are less likely to have a useful extension, so don't include them in the
				// extension check.
				if (
					mw.config.get( 'wgStrictFileExtensions' ) &&
					mw.config.get( 'wgFileExtensions' ) &&
					$( this ).attr( 'id' ) !== 'wpUploadFileURL'
				) {
					if (
						fname.lastIndexOf( '.' ) === -1 ||
						$.inArray(
							fname.slice( fname.lastIndexOf( '.' ) + 1 ).toLowerCase(),
							$.map( mw.config.get( 'wgFileExtensions' ), function ( element ) {
								return element.toLowerCase();
							} )
						) === -1
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
					fname = fname.charAt( 0 ).toUpperCase().concat( fname.slice( 1 ) );
				}

				// Output result
				if ( $( '#wpDestFile' ).length ) {
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
