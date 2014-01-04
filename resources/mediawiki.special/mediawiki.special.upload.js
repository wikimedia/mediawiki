/**
 * JavaScript for Special:Upload
 * Note that additional code still lives in skins/common/upload.js
 */
( function ( mw, $ ) {
	/**
	 * Add a preview to the upload form
	 */
	$( function () {
		/**
		 * Is the FileAPI available with sufficient functionality?
		 */
		function hasFileAPI() {
			return window.FileReader !== undefined;
		}

		/**
		 * Check if this is a recognizable image type...
		 * Also excludes files over 10M to avoid going insane on memory usage.
		 *
		 * @todo is there a way we can ask the browser what's supported in <img>s?
		 * @todo put SVG back after working around Firefox 7 bug <https://bugzilla.wikimedia.org/show_bug.cgi?id=31643>
		 *
		 * @param {File} file
		 * @return boolean
		 */
		function fileIsPreviewable( file ) {
			var known = ['image/png', 'image/gif', 'image/jpeg', 'image/svg+xml'],
				tooHuge = 10 * 1024 * 1024;
			return ( $.inArray( file.type, known ) !== -1 ) && file.size > 0 && file.size < tooHuge;
		}

		/**
		 * Show a thumbnail preview of PNG, JPEG, GIF, and SVG files prior to upload
		 * in browsers supporting HTML5 FileAPI.
		 *
		 * As of this writing, known good:
		 * - Firefox 3.6+
		 * - Chrome 7.something
		 *
		 * tableId and thumbnailId parameters must be properly escaped
		 * HTML id attribute values.
		 *
		 * @todo check file size limits and warn of likely failures
		 *
		 * @param {File} file
		 * @param {string} tableId ID of table element above which to insert the thumbnail
		 * @param {string} thumbnailId ID to be given to thumbnail element
		 */
		function showPreview( file, tableId, thumbnailId ) {
			var $canvas,
				ctx,
				meta,
				previewSize = 180,
				$thumb = $( '<div id="' + thumbnailId + '" class="thumb tright">' +
							'<div class="thumbinner">' +
								'<div class="mw-small-spinner" style="width: 180px; height: 180px"></div>' +
								'<div class="thumbcaption"><div class="filename"></div><div class="fileinfo"></div></div>' +
							'</div>' +
						'</div>' );

			$thumb.find( '.filename' ).text( file.name ).end()
				.find( '.fileinfo' ).text( prettySize( file.size ) ).end();

			$canvas = $('<canvas width="' + previewSize + '" height="' + previewSize + '" ></canvas>');
			ctx = $canvas[0].getContext( '2d' );
			$( '#' + tableId ).parent().prepend( $thumb );

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
					dx = (180 - width) / 2;
					dy = (180 - height) / 2;
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
					$thumb.find('.mw-small-spinner').replaceWith($canvas);

					// Image size
					info = mw.msg( 'widthheight', logicalWidth, logicalHeight ) +
						', ' + prettySize( file.size );

					$( '#' + thumbnailId + ' .fileinfo' ).text( info );
				};
				img.src = dataURL;
			}, mw.config.get( 'wgFileCanRotate' ) ? function ( data ) {
				/*jshint camelcase: false, nomen: false */
				try {
					meta = mw.libs.jpegmeta( data, file.fileName );
					meta._binary_data = null;
				} catch ( e ) {
					meta = null;
				}
			} : null );
		}

		/**
		 * Start loading a file into memory; when complete, pass it as a
		 * data URL to the callback function. If the callbackBinary is set it will
		 * first be read as binary and afterwards as data URL. Useful if you want
		 * to do preprocessing on the binary data first.
		 *
		 * @param {File} file
		 * @param {function} callback
		 * @param {function} callbackBinary
		 */
		function fetchPreview( file, callback, callbackBinary ) {
			var reader = new FileReader();
			if ( callbackBinary && 'readAsBinaryString' in reader ) {
				// To fetch JPEG metadata we need a binary string; start there.
				// todo:
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
						string += String.fromCharCode( buffer[i] );
					}
					callbackBinary( string );

					// Now run back through the regular code path.
					fetchPreview( file, callback );
				};
				reader.readAsArrayBuffer( file );
			} else if ( 'URL' in window && 'createObjectURL' in window.URL ) {
				// Supported in Firefox 4.0 and above <https://developer.mozilla.org/en/DOM/window.URL.createObjectURL>
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
		 * Format a file size attractively.
		 * @todo match numeric formatting
		 *
		 * @param {number} s
		 * @return string
		 */
		function prettySize( s ) {
			var sizeMsgs = ['size-bytes', 'size-kilobytes', 'size-megabytes', 'size-gigabytes'];
			while ( s >= 1024 && sizeMsgs.length > 1 ) {
				s /= 1024;
				sizeMsgs = sizeMsgs.slice( 1 );
			}
			return mw.msg( sizeMsgs[0], Math.round( s ) );
		}

		/**
		 * Clear the file upload preview area.
		 *
		 * thumbnailId parameter must be a properly escaped
		 * HTML id attribute value.
		 *
		 * @param {string} thumbnailId ID of thumbnail element to be removed
		 */
		function clearPreview( thumbnailId ) {
			$( '#' + thumbnailId ).remove();
		}

		/**
		 * Check if the file does not exceed the maximum size
		 *
		 * fileId and errorId parameters must be properly escaped
		 * HTML id attribute values.
		 *
		 * @param {File} file
		 * @param {string} fileId ID of upload form field
		 * @param {string} errorId ID of element to contain error output
		 */
		function checkMaxUploadSize( file, fileId, errorId ) {
			var maxSize, $error;

			function getMaxUploadSize( type ) {
				var sizes = mw.config.get( 'wgMaxUploadSize' );

				if ( sizes[type] !== undefined ) {
					return sizes[type];
				}
				return sizes['*'];
			}

			$( '.' + errorId ).remove();

			maxSize = getMaxUploadSize( 'file' );
			if ( file.size > maxSize ) {
				$error = $( '<p class="error mw-upload-source-error" id="' + errorId + '">' +
					mw.message( 'largefileserver', file.size, maxSize ).escaped() + '</p>' );

				$( '#' + fileId ).after( $error );

				return false;
			}

			return true;
		}

		/**
		 * Initialization
		 *
		 * All parameters must be properly escaped
		 * HTML id attribute values.
		 *
		 * @param {string} fileId ID of upload field
		 * @param {string} thumbnailId ID of thumbnail element
		 * @param {string} errorId ID of element where error output will appear
		 * @param {string} tableId ID of table element above which thumbnail will be added
		 */
		window.setupThumbnail = function ( fileId, thumbnailId, errorId, tableId ) {
			if ( hasFileAPI() ) {
				// Update thumbnail when the file selection control is updated.
				$( '#' + fileId )
					.change( function () {
						clearPreview( thumbnailId );
						if ( this.files && this.files.length ) {
							// Note: would need to be updated to handle multiple files.
							var file = this.files[0];

							if ( ! checkMaxUploadSize(
								file,
								fileId,
								errorId
							) ) {
								return;
							}

							if ( fileIsPreviewable( file ) ) {
								showPreview(
									file,
									tableId,
									thumbnailId
								);
							}
						}
					} )
					.change();
			}
		};
		window.setupThumbnail(
			'wpUploadFile',
			'mw-upload-thumbnail',
			'wpSourceTypeFile-error',
			'mw-htmlform-source'
		);
	} );

	/**
	 * When there are multiple source fields, allow only one enabled at once
	 *
	 * Parameters errorId and radioName must be appropriately escaped for
	 * insertion directly into jQuery selector strings.
	 *
	 * @param rows selected table rows within which to operate
	 * @param {string} errorId ID of element where source errors go
	 * @param {string} radioName name of radio button selecting source fields
	 */
	window.setupSourceFields = function ( $rows, errorId, radioName ) {
		var i, $row;

		function createHandler( $currentRow ) {
			/**
			 * @param {jQuery.Event}
			 */
			return function () {
				$( '#' + errorId ).remove();
				if ( this.checked ) {
					// Disable all inputs
					$rows.find( 'input[name!="' + radioName + '"]' ).prop( 'disabled', true );
					// Re-enable the current one
					$currentRow.find( 'input' ).prop( 'disabled', false );
				}
			};
		}

		for ( i = $rows.length; i; i-- ) {
			$row = $rows.eq(i - 1);
			$row
				.find( 'input[name="' + radioName + '"]' )
				.change( createHandler( $row ) );
		}
	};

	/**
	 * Disable all upload source fields except the selected one
	 */
	$( document ).ready( function() {
		window.setupSourceFields(
			$( '.mw-htmlform-field-UploadSourceField' ),
			'wpSourceTypeFile-error',
			'wpSourceType'
		);
	} );

}( mediaWiki, jQuery ) );
