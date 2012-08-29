( function ( mw, $ ) {
var	ajaxUploadDestCheck = mw.config.get( 'wgAjaxUploadDestCheck' ),
	fileExtensions = mw.config.get( 'wgFileExtensions' );

window.licenseSelectorCheck = function() {
	var selector = document.getElementById( "wpLicense" );
	var selection = selector.options[selector.selectedIndex].value;
	if( selector.selectedIndex > 0 ) {
		if( selection == "" ) {
			// Option disabled, but browser is broken and doesn't respect this
			selector.selectedIndex = 0;
		}
	}
	// We might show a preview
	wgUploadLicenseObj.fetchPreview( selection );
};

function uploadSetup() {
	// Disable URL box if the URL copy upload source type is not selected
	var e = document.getElementById( 'wpSourceTypeurl' );
	if( e ) {
		if( !e.checked ) {
			var ein = document.getElementById( 'wpUploadFileURL' );
			if(ein)
				ein.setAttribute( 'disabled', 'disabled' );
		}
	}

	// For MSIE/Mac: non-breaking spaces cause the <option> not to render.
	// But for some reason, setting the text to itself works
	var selector = document.getElementById("wpLicense");
	if (selector) {
		var ua = navigator.userAgent;
		var isMacIe = (ua.indexOf("MSIE") != -1) && (ua.indexOf("Mac") != -1);
		if (isMacIe) {
			for (var i = 0; i < selector.options.length; i++) {
				selector.options[i].text = selector.options[i].text;
			}
		}
	}

	// AJAX wpDestFile warnings
	if ( ajaxUploadDestCheck ) {
		// Insert an event handler that fetches upload warnings when wpDestFile
		// has been changed
		document.getElementById( 'wpDestFile' ).onchange = function ( e ) {
			wgUploadWarningObj.checkNow(this.value);
		};
		// Insert a row where the warnings will be displayed just below the
		// wpDestFile row
		var optionsTable = document.getElementById( 'mw-htmlform-description' ).tBodies[0];
		var row = optionsTable.insertRow( 1 );
		var td = document.createElement( 'td' );
		td.id = 'wpDestFile-warning';
		td.colSpan = 2;

		row.appendChild( td );
	}

	var wpLicense = document.getElementById( 'wpLicense' );
	if ( mw.config.get( 'wgAjaxLicensePreview' ) && wpLicense ) {
		// License selector check
		wpLicense.onchange = licenseSelectorCheck;

		// License selector table row
		var wpLicenseRow = wpLicense.parentNode.parentNode;
		var wpLicenseTbody = wpLicenseRow.parentNode;

		var row = document.createElement( 'tr' );
		var td = document.createElement( 'td' );
		row.appendChild( td );
		td = document.createElement( 'td' );
		td.id = 'mw-license-preview';
		row.appendChild( td );

		wpLicenseTbody.insertBefore( row, wpLicenseRow.nextSibling );
	}


	// fillDestFile setup
	var	i,
		uploadSourceIds = mw.config.get( 'wgUploadSourceIds' ),
		len = uploadSourceIds.length;
	for ( i = 0; i < len; i += 1 )
		document.getElementById( uploadSourceIds[i] ).onchange = function (e) {
			fillDestFilename( this.id );
		};
};


window.wgUploadWarningObj = {
	'responseCache' : { '' : '&nbsp;' },
	'nameToCheck' : '',
	'typing': false,
	'delay': 500, // ms
	'timeoutID': false,

	'keypress': function () {
		if ( !ajaxUploadDestCheck || !sajax_init_object() ) return;

		// Find file to upload
		var destFile = document.getElementById('wpDestFile');
		var warningElt = document.getElementById( 'wpDestFile-warning' );
		if ( !destFile || !warningElt ) return ;

		this.nameToCheck = destFile.value ;

		// Clear timer
		if ( this.timeoutID ) {
			window.clearTimeout( this.timeoutID );
		}
		// Check response cache
		for (cached in this.responseCache) {
			if (this.nameToCheck == cached) {
				this.setWarning(this.responseCache[this.nameToCheck]);
				return;
			}
		}

		this.timeoutID = window.setTimeout( 'wgUploadWarningObj.timeout()', this.delay );
	},

	'checkNow': function (fname) {
		if ( !ajaxUploadDestCheck || !sajax_init_object() ) return;
		if ( this.timeoutID ) {
			window.clearTimeout( this.timeoutID );
		}
		this.nameToCheck = fname;
		this.timeout();
	},

	'timeout' : function() {
		if ( !ajaxUploadDestCheck || !sajax_init_object() ) return;
		injectSpinner( document.getElementById( 'wpDestFile' ), 'destcheck' );

		// Get variables into local scope so that they will be preserved for the
		// anonymous callback. fileName is copied so that multiple overlapping
		// ajax requests can be supported.
		var obj = this;
		var fileName = this.nameToCheck;
		sajax_do_call( 'SpecialUpload::ajaxGetExistsWarning', [this.nameToCheck],
			function (result) {
				obj.processResult(result, fileName)
			}
		);
	},

	'processResult' : function (result, fileName) {
		removeSpinner( 'destcheck' );
		this.setWarning(result.responseText);
		this.responseCache[fileName] = result.responseText;
	},

	'setWarning' : function (warning) {
		var warningElt = document.getElementById( 'wpDestFile-warning' );
		var ackElt = document.getElementsByName( 'wpDestFileWarningAck' );

		this.setInnerHTML(warningElt, warning);

		// Set a value in the form indicating that the warning is acknowledged and
		// doesn't need to be redisplayed post-upload
		if ( warning == '' || warning == '&nbsp;' ) {
			ackElt[0].value = '';
		} else {
			ackElt[0].value = '1';
		}

	},
	'setInnerHTML' : function (element, text) {
		// Check for no change to avoid flicker in IE 7
		if (element.innerHTML != text) {
			element.innerHTML = text;
		}
	}
};

window.fillDestFilename = function(id) {
	if ( !mw.config.get( 'wgUploadAutoFill' ) ) {
		return;
	}
	if (!document.getElementById) {
		return;
	}
	// Remove any previously flagged errors
	var e = document.getElementById( 'mw-upload-permitted' );
	if( e ) e.className = '';

	var e = document.getElementById( 'mw-upload-prohibited' );
	if( e ) e.className = '';

	var path = document.getElementById(id).value;
	// Find trailing part
	var slash = path.lastIndexOf('/');
	var backslash = path.lastIndexOf('\\');
	var fname;
	if (slash == -1 && backslash == -1) {
		fname = path;
	} else if (slash > backslash) {
		fname = path.substring(slash+1, 10000);
	} else {
		fname = path.substring(backslash+1, 10000);
	}

	// Clear the filename if it does not have a valid extension.
	// URLs are less likely to have a useful extension, so don't include them in the
	// extension check.
	if ( mw.config.get( 'wgStrictFileExtensions' ) && fileExtensions && id !== 'wpUploadFileURL' ) {
		var found = false;
		if ( fname.lastIndexOf( '.' ) !== -1 ) {
			var ext = fname.substr( fname.lastIndexOf( '.' ) + 1 );
			for ( var i = 0; i < fileExtensions.length; i += 1 ) {
				if ( fileExtensions[i].toLowerCase() === ext.toLowerCase() ) {
					found = true;
					break;
				}
			}
		}
		if( !found ) {
			// Not a valid extension
			// Clear the upload and set mw-upload-permitted to error
			document.getElementById(id).value = '';
			var e = document.getElementById( 'mw-upload-permitted' );
			if( e ) e.className = 'error';

			var e = document.getElementById( 'mw-upload-prohibited' );
			if( e ) e.className = 'error';

			// Clear wpDestFile as well
			var e = document.getElementById( 'wpDestFile' );
			if( e ) e.value = '';

			return false;
		}
	}

	// Replace spaces by underscores
	fname = fname.replace( / /g, '_' );
	// Capitalise first letter if needed
	if ( mw.config.get( 'wgCapitalizeUploads' ) ) {
		fname = fname.charAt( 0 ).toUpperCase().concat( fname.substring( 1, 10000 ) );
	}

	// Output result
	var destFile = document.getElementById( 'wpDestFile' );
	if ( destFile ) {
		// Call decodeURIComponent function to remove possible URL-encoded characters
		// from the file name (bug 30390). Especially likely with upload-form-url.
		// decodeURIComponent can throw an exception in input is invalid utf-8
		try {
			destFile.value = decodeURIComponent( fname );
		} catch ( e ) {
			destFile.value = fname;
		}
		wgUploadWarningObj.checkNow( fname );
	}
};

window.toggleFilenameFiller = function() {
	if(!document.getElementById) return;
	var upfield = document.getElementById('wpUploadFile');
	var destName = document.getElementById('wpDestFile').value;
	wgUploadAutoFill = ( destName == '' || destName == ' ' );
};

window.wgUploadLicenseObj = {

	'responseCache' : { '' : '' },

	'fetchPreview': function( license ) {
		if ( !mw.config.get( 'wgAjaxLicensePreview' ) ) return;
		for (cached in this.responseCache) {
			if (cached == license) {
				this.showPreview( this.responseCache[license] );
				return;
			}
		}
		injectSpinner( document.getElementById( 'wpLicense' ), 'license' );

		var title = document.getElementById('wpDestFile').value;
		if ( !title ) title = 'File:Sample.jpg';

		var url = mw.util.wikiScript( 'api' )
			+ '?action=parse&text={{' + encodeURIComponent( license ) + '}}'
			+ '&title=' + encodeURIComponent( title )
			+ '&prop=text&pst&format=json';

		var req = sajax_init_object();
		req.onreadystatechange = function() {
			if ( req.readyState == 4 && req.status == 200 )
				wgUploadLicenseObj.processResult( eval( '(' + req.responseText + ')' ), license );
		};
		req.open( 'GET', url, true );
		req.send( '' );
	},

	'processResult' : function( result, license ) {
		removeSpinner( 'license' );
		this.responseCache[license] = result['parse']['text']['*'];
		this.showPreview( this.responseCache[license] );
	},

	'showPreview' : function( preview ) {
		var previewPanel = document.getElementById( 'mw-license-preview' );
		if( previewPanel.innerHTML != preview )
			previewPanel.innerHTML = preview;
	}

};

$( document ).ready( uploadSetup );

}( mediaWiki, jQuery ) );
