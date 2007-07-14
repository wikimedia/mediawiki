function licenseSelectorCheck() {
	var selector = document.getElementById( "wpLicense" );
	if( selector.selectedIndex > 0 ) {
		var selection = selector.options[selector.selectedIndex].value;
		if( selection == "" ) {
			// Option disabled, but browser is broken and doesn't respect this
			selector.selectedIndex = 0;
		} else {
			// We might show a preview
			if( wgAjaxLicencePreview ) {
				wgUploadLicenceObj.fetchPreview( selection );
			}
		}
	}
}

function licenseSelectorFixup() {
	// for MSIE/Mac; non-breaking spaces cause the <option> not to render
	// but, for some reason, setting the text to itself works
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
}

var wgUploadWarningObj = {
	'responseCache' : { '' : '&nbsp;' },
	'nameToCheck' : '',
	'typing': false,
	'delay': 500, // ms
	'timeoutID': false,

	'keypress': function () {
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
		if ( this.nameToCheck in this.responseCache ) {
			this.setWarning(this.responseCache[this.nameToCheck]);
			return;
		}

		this.timeoutID = window.setTimeout( 'wgUploadWarningObj.timeout()', this.delay );
	},

	'checkNow': function (fname) {
		if ( this.timeoutID ) {
			window.clearTimeout( this.timeoutID );
		}
		this.nameToCheck = fname;
		this.timeout();
	},
	
	'timeout' : function() {
		injectSpinner( document.getElementById( 'wpDestFile' ), 'destcheck' );

		// Get variables into local scope so that they will be preserved for the 
		// anonymous callback. fileName is copied so that multiple overlapping 
		// ajax requests can be supported.
		var obj = this;
		var fileName = this.nameToCheck;
		sajax_do_call( 'UploadForm::ajaxGetExistsWarning', [this.nameToCheck], 
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
		var ackElt = document.getElementById( 'wpDestFileWarningAck' );
		this.setInnerHTML(warningElt, warning);

		// Set a value in the form indicating that the warning is acknowledged and 
		// doesn't need to be redisplayed post-upload
		if ( warning == '' || warning == '&nbsp' ) {
			ackElt.value = '';
		} else {
			ackElt.value = '1';
		}
	},

	'setInnerHTML' : function (element, text) {
		// Check for no change to avoid flicker in IE 7
		if (element.innerHTML != text) {
			element.innerHTML = text;
		}
	}
}

function fillDestFilename(id) {
	if (!document.getElementById) {
		return;
	}
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

	// Capitalise first letter and replace spaces by underscores
	fname = fname.charAt(0).toUpperCase().concat(fname.substring(1,10000)).replace(/ /g, '_');

	// Output result
	var destFile = document.getElementById('wpDestFile');
	if (destFile) {
		destFile.value = fname;
		if ( wgAjaxUploadDestCheck ) {
			wgUploadWarningObj.checkNow(fname) ;
		}
	}
}

var wgUploadLicenceObj = {
	
	'responseCache' : { '' : '' },

	'fetchPreview': function( licence ) {
		if( licence in this.responseCache ) {
			this.showPreview( this.responseCache[licence] );
		} else {
			injectSpinner( document.getElementById( 'wpLicense' ), 'licence' );
			sajax_do_call( 'UploadForm::ajaxGetLicencePreview', [licence],
				function( result ) {
					wgUploadLicenceObj.processResult( result, licence );
				}
			);
		}
	},

	'processResult' : function( result, licence ) {
		removeSpinner( 'licence' );
		this.showPreview( result.responseText );
		this.responseCache[licence] = result.responseText;
	},

	'showPreview' : function( preview ) {
		var previewPanel = document.getElementById( 'mw-licence-preview' );
		if( previewPanel.innerHTML != preview )
			previewPanel.innerHTML = preview;
	}
	
}

addOnloadHook( licenseSelectorFixup );