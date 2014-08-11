/*jshint camelcase:false */
( function ( mw, $ ) {
var	licenseSelectorCheck, wgUploadWarningObj, wgUploadLicenseObj, fillDestFilename,
	ajaxUploadDestCheck = mw.config.get( 'wgAjaxUploadDestCheck' ),
	fileExtensions = mw.config.get( 'wgFileExtensions' ),
	$spinnerDestCheck, $spinnerLicense;

licenseSelectorCheck = window.licenseSelectorCheck = function () {
	var selector = document.getElementById( 'wpLicense' ),
		selection = selector.options[selector.selectedIndex].value;
	if ( selector.selectedIndex > 0 ) {
		if ( !selection ) {
			// Option disabled, but browser is broken and doesn't respect this
			selector.selectedIndex = 0;
		}
	}
	// We might show a preview
	wgUploadLicenseObj.fetchPreview( selection );
};

function uploadSetup() {
	// Disable URL box if the URL copy upload source type is not selected
	var ein,
		selector, ua, isMacIe, i,
		optionsTable, row, td,
		wpLicense, wpLicenseRow, wpLicenseTbody,
		uploadSourceIds, len, onchange,
		e = document.getElementById( 'wpSourceTypeurl' );
	if ( e ) {
		if ( !e.checked ) {
			ein = document.getElementById( 'wpUploadFileURL' );
			if ( ein ) {
				ein.disabled = true;
			}
		}
	}

	// For MSIE/Mac: non-breaking spaces cause the <option> not to render.
	// But for some reason, setting the text to itself works
	selector = document.getElementById( 'wpLicense' );
	if ( selector ) {
		ua = navigator.userAgent;
		isMacIe = ua.indexOf( 'MSIE' ) !== -1 && ua.indexOf( 'Mac' ) !== -1;
		if ( isMacIe ) {
			for ( i = 0; i < selector.options.length; i++ ) {
				selector.options[i].text = selector.options[i].text;
			}
		}
	}

	// AJAX wpDestFile warnings
	if ( ajaxUploadDestCheck ) {
		// Insert an event handler that fetches upload warnings when wpDestFile
		// has been changed
		document.getElementById( 'wpDestFile' ).onchange = function () {
			wgUploadWarningObj.checkNow( this.value );
		};
		// Insert a row where the warnings will be displayed just below the
		// wpDestFile row
		optionsTable = document.getElementById( 'mw-htmlform-description' ).tBodies[0];
		row = optionsTable.insertRow( 1 );
		td = document.createElement( 'td' );
		td.id = 'wpDestFile-warning';
		td.colSpan = 2;

		row.appendChild( td );
	}

	wpLicense = document.getElementById( 'wpLicense' );
	if ( mw.config.get( 'wgAjaxLicensePreview' ) && wpLicense ) {
		// License selector check
		wpLicense.onchange = licenseSelectorCheck;

		// License selector table row
		wpLicenseRow = wpLicense.parentNode.parentNode;
		wpLicenseTbody = wpLicenseRow.parentNode;

		row = document.createElement( 'tr' );
		td = document.createElement( 'td' );
		row.appendChild( td );
		td = document.createElement( 'td' );
		td.id = 'mw-license-preview';
		row.appendChild( td );

		wpLicenseTbody.insertBefore( row, wpLicenseRow.nextSibling );
	}

	// fillDestFile setup
	uploadSourceIds = mw.config.get( 'wgUploadSourceIds' );
	len = uploadSourceIds.length;
	onchange = function () {
		fillDestFilename( this.id );
	};
	for ( i = 0; i < len; i += 1 ) {
		document.getElementById( uploadSourceIds[i] ).onchange = onchange;
	}
}

wgUploadWarningObj = window.wgUploadWarningObj = {
	responseCache: { '': '&nbsp;' },
	nameToCheck: '',
	typing: false,
	delay: 500, // ms
	timeoutID: false,

	keypress: function () {
		var cached, destFile, warningElt;

		if ( !ajaxUploadDestCheck ) {
			return;
		}

		// Find file to upload
		destFile = document.getElementById( 'wpDestFile' );
		warningElt = document.getElementById( 'wpDestFile-warning' );
		if ( !destFile || !warningElt ) {
			return;
		}

		this.nameToCheck = destFile.value;

		// Clear timer
		if ( this.timeoutID ) {
			clearTimeout( this.timeoutID );
		}
		// Check response cache
		for ( cached in this.responseCache ) {
			if ( this.nameToCheck === cached ) {
				this.setWarning(this.responseCache[this.nameToCheck]);
				return;
			}
		}

		this.timeoutID = setTimeout( function () {
			wgUploadWarningObj.timeout();
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
		if ( !ajaxUploadDestCheck || this.nameToCheck === '' ) {
			return;
		}
		$spinnerDestCheck = $.createSpinner().insertAfter( '#wpDestFile' );

		var uploadWarningObj = this;
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
			uploadWarningObj.processResult( resultOut, uploadWarningObj.nameToCheck );
		} );
	},

	processResult: function ( result, fileName ) {
		$spinnerDestCheck.remove();
		$spinnerDestCheck = undefined;
		this.setWarning( result.html );
		this.responseCache[fileName] = result.html;
	},

	setWarning: function ( warning ) {
		var warningElt = document.getElementById( 'wpDestFile-warning' ),
			ackElt = document.getElementsByName( 'wpDestFileWarningAck' );

		this.setInnerHTML( warningElt, warning );

		// Set a value in the form indicating that the warning is acknowledged and
		// doesn't need to be redisplayed post-upload
		if ( !warning ) {
			ackElt[0].value = '';
		} else {
			ackElt[0].value = '1';
		}

	},
	setInnerHTML: function ( element, text ) {
		// Check for no change to avoid flicker in IE 7
		if ( element.innerHTML !== text ) {
			element.innerHTML = text;
		}
	}
};

fillDestFilename = window.fillDestFilename = function ( id ) {
	var e, path, slash, backslash, fname,
		found, ext, i,
		destFile;
	if ( !mw.config.get( 'wgUploadAutoFill' ) ) {
		return;
	}
	if ( !document.getElementById ) {
		return;
	}
	// Remove any previously flagged errors
	e = document.getElementById( 'mw-upload-permitted' );
	if ( e ) {
		e.className = '';
	}

	e = document.getElementById( 'mw-upload-prohibited' );
	if ( e ) {
		e.className = '';
	}

	path = document.getElementById( id ).value;
	// Find trailing part
	slash = path.lastIndexOf( '/' );
	backslash = path.lastIndexOf( '\\' );
	if ( slash === -1 && backslash === -1 ) {
		fname = path;
	} else if ( slash > backslash ) {
		fname = path.substring( slash + 1, 10000 );
	} else {
		fname = path.substring( backslash + 1, 10000 );
	}

	// Clear the filename if it does not have a valid extension.
	// URLs are less likely to have a useful extension, so don't include them in the
	// extension check.
	if ( mw.config.get( 'wgStrictFileExtensions' ) && fileExtensions && id !== 'wpUploadFileURL' ) {
		found = false;
		if ( fname.lastIndexOf( '.' ) !== -1 ) {
			ext = fname.substr( fname.lastIndexOf( '.' ) + 1 );
			for ( i = 0; i < fileExtensions.length; i += 1 ) {
				if ( fileExtensions[i].toLowerCase() === ext.toLowerCase() ) {
					found = true;
					break;
				}
			}
		}
		if ( !found ) {
			// Not a valid extension
			// Clear the upload and set mw-upload-permitted to error
			document.getElementById( id ).value = '';
			e = document.getElementById( 'mw-upload-permitted' );
			if ( e ) {
				e.className = 'error';
			}

			e = document.getElementById( 'mw-upload-prohibited' );
			if ( e ) {
				e.className = 'error';
			}

			// Clear wpDestFile as well
			e = document.getElementById( 'wpDestFile' );
			if ( e ) {
				e.value = '';
			}

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
	destFile = document.getElementById( 'wpDestFile' );
	if ( destFile ) {
		// Call decodeURIComponent function to remove possible URL-encoded characters
		// from the file name (bug 30390). Especially likely with upload-form-url.
		// decodeURIComponent can throw an exception in input is invalid utf-8
		try {
			destFile.value = decodeURIComponent( fname );
		} catch ( err ) {
			destFile.value = fname;
		}
		wgUploadWarningObj.checkNow( fname );
	}
};

window.toggleFilenameFiller = function () {
	if ( !document.getElementById ) {
		return;
	}
	var destName = document.getElementById( 'wpDestFile' ).value;
	mw.config.set( 'wgUploadAutoFill', !destName );
};

wgUploadLicenseObj = window.wgUploadLicenseObj = {

	responseCache: { '': '' },

	fetchPreview: function ( license ) {
		var cached, title;
		if ( !mw.config.get( 'wgAjaxLicensePreview' ) ) {
			return;
		}
		for ( cached in this.responseCache ) {
			if ( cached === license ) {
				this.showPreview( this.responseCache[license] );
				return;
			}
		}

		$spinnerLicense = $.createSpinner().insertAfter( '#wpLicense' );

		title = document.getElementById( 'wpDestFile' ).value;
		if ( !title ) {
			title = 'File:Sample.jpg';
		}

		( new mw.Api() ).get( {
			action: 'parse',
			text: '{{' + license + '}}',
			title: title,
			prop: 'text',
			pst: ''
		} ).done( function ( result ) {
			wgUploadLicenseObj.processResult( result, license );
		} );
	},

	processResult: function ( result, license ) {
		$spinnerLicense.remove();
		$spinnerLicense = undefined;
		this.responseCache[license] = result.parse.text['*'];
		this.showPreview( this.responseCache[license] );
	},

	showPreview: function ( preview ) {
		var previewPanel = document.getElementById( 'mw-license-preview' );
		if ( previewPanel.innerHTML !== preview ) {
			previewPanel.innerHTML = preview;
		}
	}

};

$( uploadSetup );

}( mediaWiki, jQuery ) );
