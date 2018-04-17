/**
 * JavaScript for Special:Upload
 *
 * @private
 * @class mw.special.upload
 * @singleton
 */

/* global Uint8Array */

( function ( mw, $ ) {
	var uploadWarning, uploadTemplatePreview,
		ajaxUploadDestCheck = mw.config.get( 'wgAjaxUploadDestCheck' ),
		$license = $( '#wpLicense' );

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
				this.setWarning( this.responseCache[ this.nameToCheck ] );
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
			var $spinnerDestCheck, title;
			if ( !ajaxUploadDestCheck || this.nameToCheck.trim() === '' ) {
				return;
			}
			$spinnerDestCheck = $.createSpinner().insertAfter( '#wpDestFile' );
			title = mw.Title.newFromText( this.nameToCheck, mw.config.get( 'wgNamespaceIds' ).file );

			( new mw.Api() ).get( {
				formatversion: 2,
				action: 'query',
				// If title is empty, user input is invalid, the API call will produce details about why
				titles: [ title ? title.getPrefixedText() : this.nameToCheck ],
				prop: 'imageinfo',
				iiprop: 'uploadwarning',
				errorformat: 'html',
				errorlang: mw.config.get( 'wgUserLanguage' )
			} ).done( function ( result ) {
				var
					resultOut = '',
					page = result.query.pages[ 0 ];
				if ( page.imageinfo ) {
					resultOut = page.imageinfo[ 0 ].html;
				} else if ( page.invalidreason ) {
					resultOut = page.invalidreason.html;
				}
				uploadWarning.processResult( resultOut, uploadWarning.nameToCheck );
			} ).always( function () {
				$spinnerDestCheck.remove();
			} );
		},

		processResult: function ( result, fileName ) {
			this.setWarning( result );
			this.responseCache[ fileName ] = result;
		},

		setWarning: function ( warning ) {
			var $warningBox = $( '#wpDestFile-warning' ),
				$warning = $( $.parseHTML( warning ) );
			mw.hook( 'wikipage.content' ).fire( $warning );
			$warningBox.empty().append( $warning );

			// Set a value in the form indicating that the warning is acknowledged and
			// doesn't need to be redisplayed post-upload
			if ( !warning ) {
				$( '#wpDestFileWarningAck' ).val( '' );
				$warningBox.removeAttr( 'class' );
			} else {
				$( '#wpDestFileWarningAck' ).val( '1' );
				$warningBox.attr( 'class', 'mw-destfile-warning' );
			}

		}
	};

	window.wgUploadTemplatePreviewObj = uploadTemplatePreview = {

		responseCache: { '': '' },

		/**
		 * @param {jQuery} $element The element whose .val() will be previewed
		 * @param {jQuery} $previewContainer The container to display the preview in
		 */
		getPreview: function ( $element, $previewContainer ) {
			var template = $element.val(),
				$spinner;

			if ( this.responseCache.hasOwnProperty( template ) ) {
				this.showPreview( this.responseCache[ template ], $previewContainer );
				return;
			}

			$spinner = $.createSpinner().insertAfter( $element );

			( new mw.Api() ).parse( '{{' + template + '}}', {
				title: $( '#wpDestFile' ).val() || 'File:Sample.jpg',
				prop: 'text',
				pst: true,
				uselang: mw.config.get( 'wgUserLanguage' )
			} ).done( function ( result ) {
				uploadTemplatePreview.processResult( result, template, $previewContainer );
			} ).always( function () {
				$spinner.remove();
			} );
		},

		processResult: function ( result, template, $previewContainer ) {
			this.responseCache[ template ] = result;
			this.showPreview( this.responseCache[ template ], $previewContainer );
		},

		showPreview: function ( preview, $previewContainer ) {
			$previewContainer.html( preview );
		}

	};

	$( function () {
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
				uploadTemplatePreview.getPreview( $license, $( '#mw-license-preview' ) );
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
		mw.config.get( 'wgUploadSourceIds' ).forEach( function ( sourceId ) {
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
					mw.config.get( 'wgCheckFileExtensions' ) &&
					mw.config.get( 'wgStrictFileExtensions' ) &&
					Array.isArray( mw.config.get( 'wgFileExtensions' ) ) &&
					$( this ).attr( 'id' ) !== 'wpUploadFileURL'
				) {
					if (
						fname.lastIndexOf( '.' ) === -1 ||
						mw.config.get( 'wgFileExtensions' ).map( function ( element ) {
							return element.toLowerCase();
						} ).indexOf( fname.slice( fname.lastIndexOf( '.' ) + 1 ).toLowerCase() ) === -1
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
					fname = fname[ 0 ].toUpperCase() + fname.slice( 1 );
				}

				// Output result
				if ( $( '#wpDestFile' ).length ) {
					// Call decodeURIComponent function to remove possible URL-encoded characters
					// from the file name (T32390). Especially likely with upload-form-url.
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

	// Add a preview to the upload form
	$( function () {
		/**
		 * Is the FileAPI available with sufficient functionality?
		 *
		 * @return {boolean}
		 */
		function hasFileAPI() {
			return window.FileReader !== undefined;
		}

		/**
		 * Check if this is a recognizable image type...
		 * Also excludes files over 10M to avoid going insane on memory usage.
		 *
		 * TODO: Is there a way we can ask the browser what's supported in `<img>`s?
		 *
		 * TODO: Put SVG back after working around Firefox 7 bug <https://phabricator.wikimedia.org/T33643>
		 *
		 * @param {File} file
		 * @return {boolean}
		 */
		function fileIsPreviewable( file ) {
			var known = [ 'image/png', 'image/gif', 'image/jpeg', 'image/svg+xml' ],
				tooHuge = 10 * 1024 * 1024;
			return ( known.indexOf( file.type ) !== -1 ) && file.size > 0 && file.size < tooHuge;
		}

		/**
		 * Format a file size attractively.
		 *
		 * TODO: Match numeric formatting
		 *
		 * @param {number} s
		 * @return {string}
		 */
		function prettySize( s ) {
			var sizeMsgs = [ 'size-bytes', 'size-kilobytes', 'size-megabytes', 'size-gigabytes' ];
			while ( s >= 1024 && sizeMsgs.length > 1 ) {
				s /= 1024;
				sizeMsgs = sizeMsgs.slice( 1 );
			}
			return mw.msg( sizeMsgs[ 0 ], Math.round( s ) );
		}

		/**
		 * Start loading a file into memory; when complete, pass it as a
		 * data URL to the callback function. If the callbackBinary is set it will
		 * first be read as binary and afterwards as data URL. Useful if you want
		 * to do preprocessing on the binary data first.
		 *
		 * @param {File} file
		 * @param {Function} callback
		 * @param {Function} callbackBinary
		 */
		function fetchPreview( file, callback, callbackBinary ) {
			var reader = new FileReader();
			if ( callbackBinary && 'readAsBinaryString' in reader ) {
				// To fetch JPEG metadata we need a binary string; start there.
				// TODO
				reader.onload = function () {
					callbackBinary( reader.result );

					// Now run back through the regular code path.
					fetchPreview( file, callback );
				};
				reader.readAsBinaryString( file );
			} else if ( callbackBinary && 'readAsArrayBuffer' in reader ) {
				// readAsArrayBuffer replaces readAsBinaryString
				// However, our JPEG metadata library wants a string.
				// So, this is going to be an ugly conversion.
				reader.onload = function () {
					var i,
						buffer = new Uint8Array( reader.result ),
						string = '';
					for ( i = 0; i < buffer.byteLength; i++ ) {
						string += String.fromCharCode( buffer[ i ] );
					}
					callbackBinary( string );

					// Now run back through the regular code path.
					fetchPreview( file, callback );
				};
				reader.readAsArrayBuffer( file );
			} else if ( 'URL' in window && 'createObjectURL' in window.URL ) {
				// Supported in Firefox 4.0 and above <https://developer.mozilla.org/en-US/docs/Web/API/URL/createObjectURL>
				// WebKit has it in a namespace for now but that's ok. ;)
				//
				// Lifetime of this URL is until document close, which is fine
				// for Special:Upload -- if this code gets used on longer-running
				// pages, add a revokeObjectURL() when it's no longer needed.
				//
				// Prefer this over readAsDataURL for Firefox 7 due to bug reading
				// some SVG files from data URIs <https://bugzilla.mozilla.org/show_bug.cgi?id=694165>
				callback( window.URL.createObjectURL( file ) );
			} else {
				// This ends up decoding the file to base-64 and back again, which
				// feels horribly inefficient.
				reader.onload = function () {
					callback( reader.result );
				};
				reader.readAsDataURL( file );
			}
		}

		/**
		 * Clear the file upload preview area.
		 */
		function clearPreview() {
			$( '#mw-upload-thumbnail' ).remove();
		}

		/**
		 * Show a thumbnail preview of PNG, JPEG, GIF, and SVG files prior to upload
		 * in browsers supporting HTML5 FileAPI.
		 *
		 * As of this writing, known good:
		 *
		 * - Firefox 3.6+
		 * - Chrome 7.something
		 *
		 * TODO: Check file size limits and warn of likely failures
		 *
		 * @param {File} file
		 */
		function showPreview( file ) {
			var $canvas,
				ctx,
				meta,
				previewSize = 180,
				$spinner = $.createSpinner( { size: 'small', type: 'block' } )
					.css( { width: previewSize, height: previewSize } ),
				thumb = mw.template.get( 'mediawiki.special.upload', 'thumbnail.html' ).render();

			thumb
				.find( '.filename' ).text( file.name ).end()
				.find( '.fileinfo' ).text( prettySize( file.size ) ).end()
				.find( '.thumbinner' ).prepend( $spinner ).end();

			$canvas = $( '<canvas>' ).attr( { width: previewSize, height: previewSize } );
			ctx = $canvas[ 0 ].getContext( '2d' );
			$( '#mw-htmlform-source' ).parent().prepend( thumb );

			fetchPreview( file, function ( dataURL ) {
				var img = new Image(),
					rotation = 0;

				if ( meta && meta.tiff && meta.tiff.Orientation ) {
					rotation = ( 360 - ( function () {
						// See includes/media/Bitmap.php
						switch ( meta.tiff.Orientation.value ) {
							case 8:
								return 90;
							case 3:
								return 180;
							case 6:
								return 270;
							default:
								return 0;
						}
					}() ) ) % 360;
				}

				img.onload = function () {
					var info, width, height, x, y, dx, dy, logicalWidth, logicalHeight;

					// Fit the image within the previewSizexpreviewSize box
					if ( img.width > img.height ) {
						width = previewSize;
						height = img.height / img.width * previewSize;
					} else {
						height = previewSize;
						width = img.width / img.height * previewSize;
					}
					// Determine the offset required to center the image
					dx = ( 180 - width ) / 2;
					dy = ( 180 - height ) / 2;
					switch ( rotation ) {
						// If a rotation is applied, the direction of the axis
						// changes as well. You can derive the values below by
						// drawing on paper an axis system, rotate it and see
						// where the positive axis direction is
						case 0:
							x = dx;
							y = dy;
							logicalWidth = img.width;
							logicalHeight = img.height;
							break;
						case 90:

							x = dx;
							y = dy - previewSize;
							logicalWidth = img.height;
							logicalHeight = img.width;
							break;
						case 180:
							x = dx - previewSize;
							y = dy - previewSize;
							logicalWidth = img.width;
							logicalHeight = img.height;
							break;
						case 270:
							x = dx - previewSize;
							y = dy;
							logicalWidth = img.height;
							logicalHeight = img.width;
							break;
					}

					ctx.clearRect( 0, 0, 180, 180 );
					ctx.rotate( rotation / 180 * Math.PI );
					ctx.drawImage( img, x, y, width, height );
					$spinner.replaceWith( $canvas );

					// Image size
					info = mw.msg( 'widthheight', logicalWidth, logicalHeight ) +
						', ' + prettySize( file.size );

					$( '#mw-upload-thumbnail .fileinfo' ).text( info );
				};
				img.onerror = function () {
					// Can happen for example for invalid SVG files
					clearPreview();
				};
				img.src = dataURL;
			}, mw.config.get( 'wgFileCanRotate' ) ? function ( data ) {
				var jpegmeta = mw.loader.require( 'mediawiki.libs.jpegmeta' );
				try {
					meta = jpegmeta( data, file.fileName );
					// eslint-disable-next-line no-underscore-dangle, camelcase
					meta._binary_data = null;
				} catch ( e ) {
					meta = null;
				}
			} : null );
		}

		/**
		 * Check if the file does not exceed the maximum size
		 *
		 * @param {File} file
		 * @return {boolean}
		 */
		function checkMaxUploadSize( file ) {
			var maxSize, $error;

			function getMaxUploadSize( type ) {
				var sizes = mw.config.get( 'wgMaxUploadSize' );

				if ( sizes[ type ] !== undefined ) {
					return sizes[ type ];
				}
				return sizes[ '*' ];
			}

			$( '.mw-upload-source-error' ).remove();

			maxSize = getMaxUploadSize( 'file' );
			if ( file.size > maxSize ) {
				$error = $( '<p class="error mw-upload-source-error" id="wpSourceTypeFile-error">' +
					mw.message( 'largefileserver', file.size, maxSize ).escaped() + '</p>' );

				$( '#wpUploadFile' ).after( $error );

				return false;
			}

			return true;
		}

		/* Initialization */
		if ( hasFileAPI() ) {
			// Update thumbnail when the file selection control is updated.
			$( '#wpUploadFile' ).change( function () {
				var file;
				clearPreview();
				if ( this.files && this.files.length ) {
					// Note: would need to be updated to handle multiple files.
					file = this.files[ 0 ];

					if ( !checkMaxUploadSize( file ) ) {
						return;
					}

					if ( fileIsPreviewable( file ) ) {
						showPreview( file );
					}
				}
			} );
		}
	} );

	// Disable all upload source fields except the selected one
	$( function () {
		var $rows = $( '.mw-htmlform-field-UploadSourceField' );

		$rows.on( 'change', 'input[type="radio"]', function ( e ) {
			var currentRow = e.delegateTarget;

			if ( !this.checked ) {
				return;
			}

			$( '.mw-upload-source-error' ).remove();

			// Enable selected upload method
			$( currentRow ).find( 'input' ).prop( 'disabled', false );

			// Disable inputs of other upload methods
			// (except for the radio button to re-enable it)
			$rows
				.not( currentRow )
				.find( 'input[type!="radio"]' )
				.prop( 'disabled', true );
		} );

		// Set initial state
		if ( !$( '#wpSourceTypeurl' ).prop( 'checked' ) ) {
			$( '#wpUploadFileURL' ).prop( 'disabled', true );
		}
	} );

	$( function () {
		// Prevent losing work
		var allowCloseWindow,
			$uploadForm = $( '#mw-upload-form' );

		if ( !mw.user.options.get( 'useeditwarning' ) ) {
			// If the user doesn't want edit warnings, don't set things up.
			return;
		}

		$uploadForm.data( 'origtext', $uploadForm.serialize() );

		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				return $( '#wpUploadFile' ).get( 0 ).files.length !== 0 ||
					$uploadForm.data( 'origtext' ) !== $uploadForm.serialize();
			},

			message: mw.msg( 'editwarning-warning' ),
			namespace: 'uploadwarning'
		} );

		$uploadForm.submit( function () {
			allowCloseWindow.release();
		} );
	} );

	// Add tabindex to mw-editTools
	$( function () {
		// Function to change tabindex for all links within mw-editTools
		function setEditTabindex( $val ) {
			$( '.mw-editTools' ).find( 'a' ).each( function () {
				$( this ).attr( 'tabindex', $val );
			} );
		}

		// Change tabindex to 0 if user pressed spaced or enter while focused
		$( '.mw-editTools' ).on( 'keypress', function ( e ) {
			// Don't continue if pressed key was not enter or spacebar
			if ( e.which !== 13 && e.which !== 32 ) {
				return;
			}

			// Change tabindex only when main div has focus
			if ( $( this ).is( ':focus' ) ) {
				$( this ).find( 'a' ).first().focus();
				setEditTabindex( '0' );
			}
		} );

		// Reset tabindex for elements when user focused out mw-editTools
		$( '.mw-editTools' ).on( 'focusout', function ( e ) {
			// Don't continue if relatedTarget is within mw-editTools
			if ( e.relatedTarget !== null && $( e.relatedTarget ).closest( '.mw-editTools' ).length > 0 ) {
				return;
			}

			// Reset tabindex back to -1
			setEditTabindex( '-1' );
		} );

		// Set initial tabindex for mw-editTools to 0 and to -1 for all links
		$( '.mw-editTools' ).attr( 'tabindex', '0' );
		setEditTabindex( '-1' );
	} );
}( mediaWiki, jQuery ) );
