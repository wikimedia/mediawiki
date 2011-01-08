/*
 * JavaScript for Special:Upload
 * Note that additional code still lives in skins/common/upload.js
 */

$( function() {
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
		var known = ['image/png', 'image/gif', 'image/jpeg', 'image/svg+xml'];
		var tooHuge = 10 * 1024 * 1024;
		return ($.inArray( file.type, known ) !== -1) && file.size > 0 && file.size < tooHuge;
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
		var previewSize = 180;
		
		var thumb = $( '<div id="mw-upload-thumbnail" class="thumb tright">' +
						'<div class="thumbinner">' +
							'<canvas width="' + previewSize + '" height="' + previewSize + '" ></canvas>' +
							'<div class="thumbcaption"><div class="filename"></div><div class="fileinfo"></div></div>' +
						'</div>' +
					'</div>' );
		thumb.find( '.filename' ).text( file.name ).end()
			.find( '.fileinfo' ).text( prettySize( file.size ) ).end();
		
		var ctx = thumb.find( 'canvas' )[0].getContext( '2d' );
		var spinner = new Image();
		spinner.onload = function() { 
			ctx.drawImage( spinner, (previewSize - spinner.width) / 2, 
					(previewSize - spinner.height) / 2 ); 
		};
		spinner.src = wgScriptPath + '/skins/common/images/spinner.gif';
		$( '#mw-htmlform-source' ).parent().prepend( thumb );

		fetchPreview( file, function( dataURL ) {	
			var img = new Image();
			var rotation = 0;
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
		});
	}

	/**
	 * Start loading a file into memory; when complete, pass it as a
	 * data URL to the callback function.
	 *
	 * @param {File} file
	 * @param {function} callback
	 */
	function fetchPreview( file, callback ) {
		var reader = new FileReader();
		reader.onload = function() {
			callback( reader.result );
		};
		reader.readAsDataURL( file );
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
		});
	}
});
