/* jshint scripturl: true */
/**
 * Provides an interface for uploading files to MediaWiki
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
		try {
			return document.createElement( '<iframe name="' + id + '">' );
		} catch ( ex ) {
			return document.createElement( 'iframe' );
		}
	}

	/**
	 * @private
	 * Shortcut for getting hidden inputs
	 * @return {jQuery}
	 */
	function getHiddenInput( name, val ) {
		return $( '<input>' )
			.prop( {
				type: 'hidden',
				name: name,
				value: val
			} );
	}

	/**
	 * Process the result of the form submission, returned to an iframe.
	 * This is the iframe's onload event.
	 *
	 * @param {HTMLIframeElement} iframe Iframe to extract result from
	 */
	function processIframeResult( iframe ) {
		var response, json,
			doc = iframe.contentDocument || frames[iframe.id].document;

		// Fix for Opera 9.26
		if ( doc.readyState && doc.readyState !== 'complete' ) {
			return;
		}

		// Fix for Opera 9.64
		if ( doc.body && doc.body.innerHTML === 'false' ) {
			return;
		}

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
			if ( json && json.substring(0, 5) !== '<?xml' ) {
				response = $.parseJSON( json );
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
			var $file;

			if ( !file ) {
				return $.Deferred().reject( 'No file' );
			} else if ( file.nodeType && file.nodeType === file.ELEMENT_NODE ) {
				$file = $( file );
				return this.uploadWithIframe( $( file ), data );
			} else {
				return $.Deferred().reject( 'Unsupported argument type passed to mw.Api.upload' );
			}
		},

		/**
		 * Upload a file to MediaWiki with an iframe and a form.
		 * @param {jQuery} $file The file input with a file in it.
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		uploadWithIframe: function ( $file, data ) {
			var tokenPromise,
				api = this,
				filenameFound = false,
				deferred = $.Deferred(),
				nonce = getNonce(),
				id = 'uploadframe-' + nonce,
				$form = $( '<form>' )
					.append(
						getHiddenInput( 'action', 'upload' ),
						getHiddenInput( 'format', 'json' ),
						$file.prop( 'name', 'file' )
					)
					.hide()
					.appendTo( 'body' )
					.prop( {
						action: api.defaults.ajax.url,
						method: 'POST',
						target: id,
						enctype: 'multipart/form-data'
					} ),
				iframe = getNewIframe( id ),
				$iframe = $( iframe )
					.one( 'load', function () {
						$iframe.one( 'load', function () {
							deferred.notify( 1 );
							deferred.resolve( processIframeResult( iframe ) );
						} );
						tokenPromise.done( function () {
							$form.submit();
						} );
					} )
					.error( function ( error ) {
						deferred.reject( 'iframe failed to load: ' + error );
					} )
					.prop( 'id', id )
					.prop( 'name', id )
					.prop( 'src', 'javascript:false;' )
					.hide();

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
			} );

			$( 'body' ).append( $iframe );

			return deferred.promise();
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.upload
	 */
}( mediaWiki, jQuery ) );
