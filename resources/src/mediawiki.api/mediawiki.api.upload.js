/**
 * Provides an interface for uploading files to MediaWiki.
 * @class mw.Api.plugin.upload
 * @singleton
 */
( function ( mw, $ ) {
	var nonce = 0,
		fieldsAllowed = {
			filename: true,
			comment: true,
			text: true,
			watchlist: true,
			ignorewarnings: true
		};

	/**
	 * @private
	 * Get nonce for iframe IDs on the page.
	 * @return {number}
	 */
	function getNonce() {
		return nonce++;
	}

	/**
	 * @private
	 * Get new iframe object for an upload.
	 * @return {HTMLIframeElement}
	 */
	function getNewIframe( id ) {
		var frame = document.createElement( 'iframe' );
		frame.id = id;
		frame.name = id;
		return frame;
	}

	/**
	 * @private
	 * Shortcut for getting hidden inputs
	 * @return {jQuery}
	 */
	function getHiddenInput( name, val ) {
		return $( '<input type="hidden" />')
			.attr( 'name', name )
			.val( val );
	}

	/**
	 * Process the result of the form submission, returned to an iframe.
	 * This is the iframe's onload event.
	 *
	 * @param {HTMLIframeElement} iframe Iframe to extract result from
	 * @return {Object} Response from the server. The return value may or may
	 *   not be an XMLDocument, this code was copied from elsewhere, so if you
	 *   see an unexpected return type, please file a bug.
	 */
	function processIframeResult( iframe ) {
		var response, json,
			doc = iframe.contentDocument || frames[iframe.id].document;

		if ( doc.XMLDocument ) {
			// The response is a document property in IE
			response = doc.XMLDocument;
		} else if ( doc.body ) {
			// Get the json string
			// We're actually searching through an HTML doc here --
			// according to mdale we need to do this
			// because IE does not load JSON properly in an iframe
			json = $( doc.body ).find( 'pre' ).text();

			// check that the JSON is not an XML error message
			// (this happens when user aborts upload, we get the API docs in XML wrapped in HTML)
			if ( json && json.slice( 0, 5 ) !== '<?xml' ) {
				response = JSON.parse( json );
			} else {
				response = {};
			}
		} else {
			// Response is a xml document
			response = doc;
		}

		// Process the API result
		return response;
	}

	$.extend( mw.Api.prototype, {
		/**
		 * Upload a file to MediaWiki.
		 * @param {HTMLInputElement} file HTML input type=file element with a file already inside of it.
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		upload: function ( file, data ) {
			if ( !file ) {
				return $.Deferred().reject( 'No file' );
			}

			if ( !file.nodeType || file.nodeType !== file.ELEMENT_NODE ) {
				return $.Deferred().reject( 'Unsupported argument type passed to mw.Api.upload' );
			}

			return this.uploadWithIframe( file, data );
		},

		/**
		 * Upload a file to MediaWiki with an iframe and a form.
		 *
		 * The rough sketch of how this method works is as follows:
		 * * An iframe is loaded with no content.
		 * * A form is submitted with the passed-in file input and some extras.
		 * * The MediaWiki API receives that form data, and sends back a response.
		 * * The response is sent to the iframe, because we set target=(iframe id)
		 * * The response is parsed out of the iframe's document, and passed back
		 *   through the promise.
		 * @param {HTMLInputElement} file The file input with a file in it.
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		uploadWithIframe: function ( file, data ) {
			var tokenPromise,
				api = this,
				filenameFound = false,
				deferred = $.Deferred(),
				nonce = getNonce(),
				id = 'uploadframe-' + nonce,
				$form = $( '<form>' ),
				iframe = getNewIframe( id ),
				$iframe = $( iframe );

			$form.addClass( 'mw-api-upload-form' );
			
			$form.append(
				getHiddenInput( 'action', 'upload' ),
				getHiddenInput( 'format', 'json' ),
				file
			);

			$form.css( 'display', 'none' )
				.attr( {
					action: api.defaults.ajax.url,
					method: 'POST',
					target: id,
					enctype: 'multipart/form-data'
				} );

			$iframe.one( 'load', function () {
				$iframe.one( 'load', function () {
					deferred.notify( 1 );
					deferred.resolve( processIframeResult( iframe ) );
				} );
				tokenPromise.done( function () {
					$form.submit();
				} );
			} );
			
			$iframe.error( function ( error ) {
				deferred.reject( 'iframe failed to load: ' + error );
			} );

			$iframe.prop( 'src', 'about:blank' ).hide();

			file.name = 'file';

			$.each( data, function ( key, val ) {
				if ( key === 'filename' ) {
					filenameFound = true;
				}

				if ( fieldsAllowed[key] === true ) {
					$form.append( getHiddenInput( key, val ) );
				}
			} );

			if ( !filenameFound ) {
				return $.Deferred().reject( 'Filename not included in file data.' );
			}

			tokenPromise = this.getEditToken().then( function ( token ) {
				$form.append( getHiddenInput( 'token', token ) );
			}, function () {
				// Mark the edit token as bad, it's been used.
				api.badToken( 'edit' );
			} );

			$( 'body' ).append( $form, $iframe );

			return deferred.promise();
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.upload
	 */
}( mediaWiki, jQuery ) );
