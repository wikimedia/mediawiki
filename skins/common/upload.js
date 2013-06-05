/*jshint camelcase:false */
( function ( mw, $ ) {
var	licenseSelectorCheck, wgUploadWarningObj, wgUploadLicenseObj, fillDestFilename,
	ajaxUploadDestCheck = mw.config.get( 'wgAjaxUploadDestCheck' ),
	fileExtensions = mw.config.get( 'wgFileExtensions' );

/**
 * onchange function to show a preview when license selector is changed
 *
 * All parameters must be properly escaped
 * HTML id attribute values.
 *
 * @param {string} licenseId ID of license selector field
 * @param {string} destFileId ID of destination filename field
 * @param {string} previewId ID of element where license preview goes
 */
licenseSelectorCheck = window.licenseSelectorCheck = function( licenseId, destFileId, previewId ) {
	return function() {
		var selector = document.getElementById( licenseId ),
			selection = selector.options[selector.selectedIndex].value;
		if( selector.selectedIndex > 0 ) {
			if ( !selection ) {
				// Option disabled, but browser is broken and doesn't respect this
				selector.selectedIndex = 0;
			}
		}
		// We might show a preview
		wgUploadLicenseObj.fetchPreview( selection, licenseId, destFileId, previewId );
	};
};

/**
 * Insert event handlers, empty elements, generally get form ready for
 * the dynamics operations we're going to do when data is entered.
 *
 * All parameters must be properly escaped
 * HTML id attribute values.
 *
 * @param {string} sourceTypeUrlId ID of the radio button for the copy-from-URL field
 * @param {string} uploadUrlId ID of copy-from-URL text field
 * @param {string} licenseId ID of license selector field
 * @param {string} warningId ID to attach to table row where warnings will go
 * @param {string} ackId Id of hidden field recording that warning was acknowledged
 * @param {string} destFileId ID of destination filename field
 * @param {string} descriptionId unused
 * @param {string} previewId ID of element where license preview goes
 */
/** TODO: move away from using IDs as args? */
window.uploadSetupByIds = function( sourceTypeUrlId, uploadUrlId, licenseId, warningId, ackId, destFileId, descriptionId, previewId ) {
	// Disable URL box if the URL copy upload source type is not selected
	var ein,
		selector, ua, isMacIe, i,
		destElt, destFileRow, destFileTbody, row, td, ackElt,
		wpLicense, wpLicenseRow, wpLicenseTbody,
		e = document.getElementById( sourceTypeUrlId );
	if ( e ) {
		if ( !e.checked ) {
			ein = document.getElementById( uploadUrlId );
			if ( ein ) {
				ein.disabled = true;
			}
		}
	}

	// For MSIE/Mac: non-breaking spaces cause the <option> not to render.
	// But for some reason, setting the text to itself works
	selector = document.getElementById( licenseId );
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
		// Insert event handlers to fetch upload warnings when wpDestFile
		// has been changed
		destElt = document.getElementById( destFileId );
		if ( destElt ) {
			ackElt = document.getElementsByName( ackId );
			destElt.onchange = function () {
				wgUploadWarningObj.checkNow( this, warningId, ackElt );
			};
			destElt.onkeyup = function () {
				wgUploadWarningObj.keypress( this, warningId, ackElt );
			};
			// Insert a row where the warnings will be displayed just below the
			// wpDestFile row
			destFileRow = destElt.parentNode.parentNode;
			destFileTbody = destFileRow.parentNode;

			row = document.createElement( 'tr' );
			td = document.createElement( 'td' );
			td.id = warningId;
			td.colSpan = 2;

			row.appendChild( td );
			destFileTbody.insertBefore( row, destFileRow.nextSibling );
		}
	}

	wpLicense = document.getElementById( licenseId );
	if ( mw.config.get( 'wgAjaxLicensePreview' ) && wpLicense ) {
		// License selector table row
		wpLicenseRow = wpLicense.parentNode.parentNode;
		wpLicenseTbody = wpLicenseRow.parentNode;

		row = document.createElement( 'tr' );
		td = document.createElement( 'td' );
		row.appendChild( td );
		td = document.createElement( 'td' );
		td.id = previewId;
		row.appendChild( td );

		wpLicenseTbody.insertBefore( row, wpLicenseRow.nextSibling );

		// License selector check
		wpLicense.onchange = licenseSelectorCheck( licenseId, destFileId, previewId );
		// Something might already be selected
		wpLicense.onchange();
	}
};

/**
 * call uploadSetupByIds and do a bit of extra setup
 */
function uploadSetup() {
	window.uploadSetupByIds( 'wpSourceTypeurl', 'wpUploadFileURL', 'wpLicense', 'wpDestFile-warning',
		'wpDestFileWarningAck', 'wpDestFile', 'mw-htmlform-description', 'mw-license-preview' );

	// fillDestFile setup
	var	uploadSourceIds = mw.config.get( 'wgUploadSourceIds' ),
		i, uploadElt,
		len = Array.isArray(uploadSourceIds) ? uploadSourceIds.length : 0,
		upUrl = document.getElementById( 'wpUploadFileURL' ),
		destFile = document.getElementById( 'wpDestFile' ),
		upperm = document.getElementById( 'mw-upload-permitted' ),
		uppro = document.getElementById( 'mw-upload-prohibited' ),
		warningId = 'wpDestFile-warning',
		ackElt = document.getElementsByName( 'wpDestFileWarningAck' ),
		uploadChange = function () {
			fillDestFilename( this, upUrl, destFile, upperm, uppro, warningId, ackElt, 'wgUploadAutoFill' );
		};
	for ( i = 0; i < len; i += 1 ) {
		uploadElt = document.getElementById( uploadSourceIds[i] );
		uploadElt.onchange = uploadChange;
		uploadElt.onchange();
	}
}

/**
 * object to manage Ajax call comparing destination filename to existing
 * file pages
 */
wgUploadWarningObj = window.wgUploadWarningObj = {
	responseCache: { '' : '' },
	delay: 500, // ms
	timeoutID: false,

	/*
	 * keypress handler for destination filename field.
	 * set a timer to check the destination against existing File:
	 * pages when the uploader stops typing.
	 *
	 * warningId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {HTMLElement} destFile destination filename field
	 * @param {string} warningId ID of element where warning will go
	 * @param {HTMLElement} ackElt hidden field element for warning acknowledgement
	 */
	keypress: function ( destFile, warningId, ackElt ) {
		var cached;

		if ( !ajaxUploadDestCheck ) {
			return;
		}

		// Clear timer
		if ( this.timeoutID ) {
			clearTimeout( this.timeoutID );
		}
		// Check response cache
		for ( cached in this.responseCache ) {
			if ( cached === destFile.value ) {
				this.setWarning( this.responseCache[cached], warningId, ackElt );
				return;
			}
		}

		this.timeoutID = setTimeout( function () {
			wgUploadWarningObj.timeout( destFile, warningId, ackElt );
		}, this.delay );
	},

	/**
	 * Initiate an Ajax check of the destination filename
	 *
	 * warningId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {HTMLElement} field destination filename field
	 * @param {string} warningId ID of element where warning will go
	 * @param {HTMLElement} ackElt hidden field element for warning acknowledgement
	 */
	checkNow: function ( field, warningId, ackElt ) {
		var cached;
		if ( !ajaxUploadDestCheck ) {
			return;
		}
		if ( this.timeoutID ) {
			clearTimeout( this.timeoutID );
		}
		for ( cached in this.responseCache ) {
			if ( cached === field.value ) {
				this.setWarning( this.responseCache[cached], warningId, ackElt );
				return;
			}
		}
		this.timeout( field, warningId, ackElt );
	},

	/**
	 * Start spinner graphic and make Ajax call
	 *
	 * warningId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {HTMLElement} field destination filename field
	 * @param {string} warningId ID of element where warning will go
	 * @param {HTMLElement} ackElt hidden field element for warning acknowledgement
	 */
	timeout: function ( field, warningId, ackElt ) {
		var prefixedFilename, 
			filename = field.value;

		if ( !ajaxUploadDestCheck || filename === '' ) {
			return;
		}

		prefixedFilename = '';
		try {
			prefixedFilename = ( new mw.Title( filename, mw.config.get( 'wgNamespaceIds' ).file ) ).getPrefixedText();
		} catch(e) {
		}
		if ( prefixedFilename === '' ) {
			return;
		}
		$.removeSpinner( warningId );
		$( field ).injectSpinner( warningId );
		( new mw.Api() ).get( {
			action: 'query',
			titles: prefixedFilename,
			prop: 'imageinfo',
			iiprop: 'uploadwarning',
			indexpageids: ''
		} ).done( function ( result ) {
			var resultInfo, resultOut = '';
			if ( result.query ) {
				resultInfo = result.query.pages[result.query.pageids[0]];
				if ( 'invalid' in resultInfo ) {
					resultOut = resultInfo.invalid;
				} else if ( 'imageinfo' in resultInfo ) {
					resultOut = resultInfo.imageinfo[0].html;
				}
			}
			wgUploadWarningObj.processResult( resultOut, filename, warningId, ackElt );
		} );
	},

	/**
	 * Ajax callback function
	 *
	 * warningId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {string} resulthtml message returned by Ajax call
	 * @param {string} filename the filename being checked
	 * @param {string} warningId ID of element where warning will go
	 * @param {HTMLElement} ackElt hidden field element for warning acknowledgement
	 */
	processResult: function ( resulthtml, filename, warningId, ackElt ) {
		$.removeSpinner( warningId );
		this.setWarning( resulthtml, warningId, ackElt );
		this.responseCache[filename] = resulthtml;
	},

	/**
	 * Put the warning into the page
	 *
	 * warningId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {string} warning HTML text of warning
	 * @param {string} warningId ID of element where warning will go
	 * @param {HTMLElement} ackElt hidden field element for warning acknowledgement
	 */
	setWarning: function ( warning, warningId, ackElt ) {
		this.setInnerHTML( warningId, warning );

		// Set a value in the form indicating that the warning is acknowledged and
		// doesn't need to be redisplayed post-upload
		if ( !warning ) {
			ackElt[0].value = '';
		} else {
			ackElt[0].value = '1';
		}

	},

	/**
	 * Put message into element
	 *
	 * id parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {string} id ID of element where message will go
	 * @param {string} text HTML text of message
	 */
	setInnerHTML: function ( id, text ) {
		var element = document.getElementById( id );
		// Check for no change to avoid flicker in IE 7
		if ( element.innerHTML !== text ) {
			element.innerHTML = text;
		}
	}
};

/**
 * Autofill the destination filename with a guess built from the source
 * filename.  This is called when either the source-file or source-url is
 * updated.
 *
 * warningId parameter must be a properly escaped HTML id attribute value.
 *
 * @param {HTMLElement} upFile source-file element
 * @param {HTMLElement} upUrl source-url element
 * @param {HTMLElement} destFile destination-filename element
 * @param {HTMLElement} upperm element where upload permissions errors go
 * @param {HTMLElement} uppro element where upload-prohibited messages go
 * @param {string} warningId Id of element where upload warnings will go
 * @param {HTMLElement} ackElt hidden form field recording acknowledgement of warnings
 * @param {string} configvar name of mw.config variable controlling whether to do auto-filling
 */
fillDestFilename = window.fillDestFilename =
  function ( upFile, upUrl, destFile, upperm, uppro, warningId, ackElt, configvar ) {
	var path, slash, backslash, fname, found, ext, i;

	// e.g. mw.config.get( 'wgUploadAutoFill' )
	if ( !mw.config.get( configvar ) ) {
		return;
	}
	if ( !document.getElementById ) {
		return;
	}
	// Remove any previously flagged errors
	if ( upperm ) {
		upperm.className = '';
	}
	if ( uppro ) {
		uppro.className = '';
	}

	path = upFile.value;
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
	if ( upUrl && mw.config.get( 'wgStrictFileExtensions' ) && fileExtensions && upFile.id !== upUrl.id ) {
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
			upFile.value = '';
			if ( upperm ) {
				upperm.className = 'error';
			}
			if ( uppro ) {
				uppro.className = 'error';
			}
			// Clear wpDestFile as well
			if ( destFile ) {
				destFile.value = '';
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
	if ( destFile ) {
		// Call decodeURIComponent function to remove possible URL-encoded characters
		// from the file name (bug 30390). Especially likely with upload-form-url.
		// decodeURIComponent can throw an exception if input is invalid utf-8
		try {
			destFile.value = decodeURIComponent( fname );
		} catch ( err ) {
			destFile.value = fname;
		}
		destFile.onchange();
	}
};

/**
 * object to manage Ajax fetching of license text
 */
wgUploadLicenseObj = window.wgUploadLicenseObj = {

	responseCache: { '' : '' },

	/**
	 * Initiate an Ajax request for license text to preview
	 *
	 * licenseId, destFileId and previewId parameters must be
	 * properly escaped HTML id attribute values.
	 *
	 * @param {string} license value of license selector
	 * @param {string} licenseId ID of license selector
	 * @param {string} destFileId ID of destination filename field
	 * @param {string} previewId ID of element where text will go
	 */
	fetchPreview: function ( license, licenseId, destFileId, previewId ) {
		var cached, title;
		if ( !mw.config.get( 'wgAjaxLicensePreview' ) ) {
			return;
		}
		for ( cached in this.responseCache ) {
			if ( cached === license ) {
				this.showPreview( this.responseCache[license], previewId );
				return;
			}
		}
		$( '#' + licenseId ).injectSpinner( previewId );

		title = document.getElementById( destFileId ).value;
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
			wgUploadLicenseObj.processResult( result, license, previewId );
		} );
	},

	/**
	 * Ajax callback function.
	 *
	 * previewId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {XMLHTTPRequest} result request object from ajax call
	 * @param {string} license license selector value
	 * @param {string} previewId ID of element where preview text will go
	 */
	processResult: function ( result, license, previewId ) {
		$.removeSpinner( previewId );
		this.responseCache[license] = result.parse.text['*'];
		this.showPreview( this.responseCache[license], previewId );
	},

	/**
	 * Display the license preview
	 *
	 * previewId parameter must be a properly escaped
	 * HTML id attribute value.
	 *
	 * @param {string} preview HTML text of license
	 * @param {string} previewId ID of element where text will go
	 */
	showPreview: function ( preview, previewId ) {
		var previewPanel = document.getElementById( previewId );
		if ( previewPanel.innerHTML !== preview ) {
			previewPanel.innerHTML = preview;
		}
	}

};

$( document ).ready( function () { uploadSetup(); } );

}( mediaWiki, jQuery ) );
