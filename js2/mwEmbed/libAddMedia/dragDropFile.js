/* firefox 3.6 drag-drop uploading
*
* Note: this file is still under development
*/
loadGM( {
	"mwe-upload-multi" : "Upload {{PLURAL:$1|file|files}}",
	"mwe-review-upload": "Review file {{PLURAL:$1|upload|uploads}}"
} );

( function( $ ) {
	$.fn.dragDropFile = function () {
		js_log( "drag drop: " + this.selector );
		// setup drag binding and highlight
		var dC = $j( this.selector ).get( 0 );
		dC.addEventListener( "dragenter",
			function( event ) {
				$j( dC ).css( 'border', 'solid red' );
				event.stopPropagation();
				event.preventDefault();
			}, false );
		dC.addEventListener( "dragleave",
			function( event ) {
				// default textbox css (should be an easy way to do this)
				$j( dC ).css( 'border', '' );
				event.stopPropagation();
				event.preventDefault();
			}, false );
		dC.addEventListener( "dragover",
			function( event ) {
				event.stopPropagation();
				event.preventDefault();
			}, false );
		dC.addEventListener( "drop",
			function( event ) {
				doDrop( event );
				// handle the drop loader and call event
			}, false );
		function doDrop( event ) {
			var dt = event.dataTransfer,
				files = dt.files,
				fileCount = files.length;

			event.stopPropagation();
			event.preventDefault();

			$j( '#multiple_file_input' ).remove();

			$j( 'body' ).append( '<div title="' + gM( 'mwe-upload-multi', fileCount ) + '" ' +
				'style="position:absolute;bottom:5em;top:3em;right:0px;left:0px" ' +
				'id="multiple_file_input">' +
				'</div>'
			);


			var buttons = { };
			buttons[ gM( 'mwe-cancel' ) ] = function() {
				$j( this ).dialog( 'close' );
			}
			buttons[ gM( 'mwe-upload-multi', fileCount ) ] = function() {
				alert( 'do multiple file upload' );
			}
			// open up the dialog
			$j( '#multiple_file_input' ).dialog( {
				bgiframe: true,
				autoOpen: true,
				modal: true,
				draggable:false,
				resizable:false,
				buttons : buttons
			} );
			$j( '#multiple_file_input' ).dialogFitWindow();
			$j( window ).resize( function() {
				$j( '#multiple_file_input' ).dialogFitWindow();
			} );
			// add the inital table / title:
			$j( '#multiple_file_input' ).html( '<h3>' + gM( 'mwe-review-upload' ) + '</h3>' +
				'<table width="100%" border="1" class="table_list" style="border:none;"></table>' );
			$j.each( files, function( i, file ) {
				if ( file.fileSize < 64048576 ) {
					$j( '#multiple_file_input .table_list' ).append(
						'<tr>' +
							'<td width="300" style="padding:5px"><img width="250" src="' + file.getAsDataURL() + '">' + '</td>' +
							'<td valign="top">' +
								'File Name: <input name="file[' + i + '][title]" value="' + file.name + '"><br>' +
								'File Desc: <textarea style="width:300px;" name="file[' + i + '][desc]"></textarea><br>' +
							'</td>' +
						'</tr>'
					);
					/*$j.addDialog( "upload this image", '<img width="300" src="' + files[i].getAsDataURL() + '">' +
						'<br>name: ' + files[i].name + '</br>' +
						'<br>size: ' + files[i].fileSize + '</br>' +
						'<br>mime: ' + files[i].mediaType + '</br>');
					*/
					// do the add-media-wizard with the upload tab
				} else {
					alert( "file is too big, needs to be below 64mb" );
				}
			} );
		}
	}
} )( jQuery );
