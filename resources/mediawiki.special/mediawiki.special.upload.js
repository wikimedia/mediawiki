/*
 * JavaScript for Special:Upload
 * Note that additional code still lives in skins/common/upload.js
 */

/**
 * Add a preview to the upload form
 */
jQuery( function( $ ) {
	/**
	 * Is the FileAPI available with sufficient functionality?
	 */
	function hasFileAPI(){
		return typeof window.FileReader !== 'undefined';
	}

	/**
	 * Check if this is a recognizable image type...
	 * Also excludes files over 10M to avoid going insane on memory usage.
	 *
	 * @todo is there a way we can ask the browser what's supported in <img>s?
	 *
	 * @param {File} file
	 * @return boolean
	 */
	function fileIsPreviewable( file ) {
		var	known = ['image/png', 'image/gif', 'image/jpeg', 'image/svg+xml'],
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
	 * @todo check file size limits and warn of likely failures
	 *
	 * @param {File} file
	 */
	function showPreview( file ) {
		var	previewSize = 180,
			thumb = $( '<div id="mw-upload-thumbnail" class="thumb tright">' +
						'<div class="thumbinner">' +
							'<canvas width="' + previewSize + '" height="' + previewSize + '" ></canvas>' +
							'<div class="thumbcaption"><div class="filename"></div><div class="fileinfo"></div></div>' +
						'</div>' +
					'</div>' );
		thumb.find( '.filename' ).text( file.name ).end()
			.find( '.fileinfo' ).text( prettySize( file.size ) ).end();
		
		var	ctx = thumb.find( 'canvas' )[0].getContext( '2d' ),
			spinner = new Image();
		spinner.onload = function() { 
			ctx.drawImage( spinner, (previewSize - spinner.width) / 2, 
					(previewSize - spinner.height) / 2 ); 
		};
		spinner.src = mw.config.get( 'wgScriptPath' ) + '/skins/common/images/spinner.gif';
		$( '#mw-htmlform-source' ).parent().prepend( thumb );

		var meta;
		fetchPreview( file, function( dataURL ) {
			var	img = new Image(),
				rotation = 0;
			
			if ( meta && meta.tiff && meta.tiff.Orientation ) {
				rotation = (360 - function () {
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
				}() ) % 360;
			}
			
			img.onload = function() {
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
						break;
					case 90:
						
						x = dx;
						y = dy - previewSize;
						break;
					case 180:
						x = dx - previewSize;
						y = dy - previewSize;
						break;
					case 270:
						x = dx - previewSize;
						y = dy;
						break;
				}
				
				ctx.clearRect( 0, 0, 180, 180 );
				ctx.rotate( rotation / 180 * Math.PI );
				ctx.drawImage( img, x, y, width, height );
				
				// Image size
				var info = mw.msg( 'widthheight', img.width, img.height ) +
					', ' + prettySize( file.size );
				$( '#mw-upload-thumbnail .fileinfo' ).text( info );
			};
			img.src = dataURL;
		}, mw.config.get( 'wgFileCanRotate' ) ? function ( data ) {
			try {
				meta = mw.util.jpegmeta( data, file.fileName );
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
		reader.onload = function() {
			if ( callbackBinary ) {
				callbackBinary( reader.result );
				reader.onload = function() {
					callback( reader.result );
				};
				reader.readAsDataURL( file );
			} else {
				callback( reader.result );
			}
		};
		if ( callbackBinary ) {
			reader.readAsBinaryString( file );
		} else {
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
		var sizes = ['size-bytes', 'size-kilobytes', 'size-megabytes', 'size-gigabytes'];
		while ( s >= 1024 && sizes.length > 1 ) {
			s /= 1024;
			sizes = sizes.slice( 1 );
		}
		return mw.msg( sizes[0], Math.round( s ) );
	}

	/**
	 * Clear the file upload preview area.
	 */
	function clearPreview() {
		$( '#mw-upload-thumbnail' ).remove();
	}
	

	if ( hasFileAPI() ) {
		// Update thumbnail when the file selection control is updated.
		$( '#wpUploadFile' ).change( function() {
			clearPreview();
			if ( this.files && this.files.length ) {
				// Note: would need to be updated to handle multiple files.
				var file = this.files[0];
				if ( fileIsPreviewable( file ) ) {
					showPreview( file );
				}
			}
		} );
	}
} );

/**
 * Disable all upload source fields except the selected one
 */
jQuery( function ( $ ) {
	var rows = $( '.mw-htmlform-field-UploadSourceField' );
	for ( var i = rows.length; i; i-- ) {
		var row = rows[i - 1];
		$( 'input[name="wpSourceType"]', row ).change( function () {
			var currentRow = row; // Store current row in our own scope
			return function () {
				if ( this.checked ) {
					// Disable all inputs
					$( 'input[name!="wpSourceType"]', rows ).attr( 'disabled', true );
					// Re-enable the current one
					$( 'input', currentRow ).attr( 'disabled', false );
				}
			};
		}() );
	}
} );
