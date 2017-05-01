/**
 * Provides an interface for uploading files to MediaWiki.
 *
 * @class mw.Api.plugin.upload
 * @singleton
 */
( function ( mw, $ ) {
	var nonce = 0,
		fieldsAllowed = {
			stash: true,
			filekey: true,
			filename: true,
			comment: true,
			text: true,
			watchlist: true,
			ignorewarnings: true
		};

	/**
	 * Get nonce for iframe IDs on the page.
	 *
	 * @private
	 * @return {number}
	 */
	function getNonce() {
		return nonce++;
	}

	/**
	 * Given a non-empty object, return one of its keys.
	 *
	 * @private
	 * @param {Object} obj
	 * @return {string}
	 */
	function getFirstKey( obj ) {
		var key;
		for ( key in obj ) {
			if ( obj.hasOwnProperty( key ) ) {
				return key;
			}
		}
	}

	/**
	 * Get new iframe object for an upload.
	 *
	 * @private
	 * @param {string} id
	 * @return {HTMLIframeElement}
	 */
	function getNewIframe( id ) {
		var frame = document.createElement( 'iframe' );
		frame.id = id;
		frame.name = id;
		return frame;
	}

	/**
	 * Shortcut for getting hidden inputs
	 *
	 * @private
	 * @param {string} name
	 * @param {string} val
	 * @return {jQuery}
	 */
	function getHiddenInput( name, val ) {
		return $( '<input>' ).attr( 'type', 'hidden' )
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
		var json,
			doc = iframe.contentDocument || frames[ iframe.id ].document;

		if ( doc.XMLDocument ) {
			// The response is a document property in IE
			return doc.XMLDocument;
		}

		if ( doc.body ) {
			// Get the json string
			// We're actually searching through an HTML doc here --
			// according to mdale we need to do this
			// because IE does not load JSON properly in an iframe
			json = $( doc.body ).find( 'pre' ).text();

			return JSON.parse( json );
		}

		// Response is a xml document
		return doc;
	}

	function formDataAvailable() {
		return window.FormData !== undefined &&
			window.File !== undefined &&
			window.File.prototype.slice !== undefined;
	}

	$.extend( mw.Api.prototype, {
		/**
		 * Upload a file to MediaWiki.
		 *
		 * The file will be uploaded using AJAX and FormData, if the browser supports it, or via an
		 * iframe if it doesn't.
		 *
		 * Caveats of iframe upload:
		 * - The returned jQuery.Promise will not receive `progress` notifications during the upload
		 * - It is incompatible with uploads to a foreign wiki using mw.ForeignApi
		 * - You must pass a HTMLInputElement and not a File for it to be possible
		 *
		 * @param {HTMLInputElement|File|Blob} file HTML input type=file element with a file already inside
		 *  of it, or a File object.
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		upload: function ( file, data ) {
			var isFileInput, canUseFormData;

			isFileInput = file && file.nodeType === Node.ELEMENT_NODE;

			if ( formDataAvailable() && isFileInput && file.files ) {
				file = file.files[ 0 ];
			}

			if ( !file ) {
				throw new Error( 'No file' );
			}

			// Blobs are allowed in formdata uploads, it turns out
			canUseFormData = formDataAvailable() && ( file instanceof window.File || file instanceof window.Blob );

			if ( !isFileInput && !canUseFormData ) {
				throw new Error( 'Unsupported argument type passed to mw.Api.upload' );
			}

			if ( canUseFormData ) {
				return this.uploadWithFormData( file, data );
			}

			return this.uploadWithIframe( file, data );
		},

		/**
		 * Upload a file to MediaWiki with an iframe and a form.
		 *
		 * This method is necessary for browsers without the File/FormData
		 * APIs, and continues to work in browsers with those APIs.
		 *
		 * The rough sketch of how this method works is as follows:
		 * 1. An iframe is loaded with no content.
		 * 2. A form is submitted with the passed-in file input and some extras.
		 * 3. The MediaWiki API receives that form data, and sends back a response.
		 * 4. The response is sent to the iframe, because we set target=(iframe id)
		 * 5. The response is parsed out of the iframe's document, and passed back
		 *    through the promise.
		 *
		 * @private
		 * @param {HTMLInputElement} file The file input with a file in it.
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		uploadWithIframe: function ( file, data ) {
			var key,
				tokenPromise = $.Deferred(),
				api = this,
				deferred = $.Deferred(),
				nonce = getNonce(),
				id = 'uploadframe-' + nonce,
				$form = $( '<form>' ),
				iframe = getNewIframe( id ),
				$iframe = $( iframe );

			for ( key in data ) {
				if ( !fieldsAllowed[ key ] ) {
					delete data[ key ];
				}
			}

			data = $.extend( {}, this.defaults.parameters, { action: 'upload' }, data );
			$form.addClass( 'mw-api-upload-form' );

			$form.css( 'display', 'none' )
				.attr( {
					action: this.defaults.ajax.url,
					method: 'POST',
					target: id,
					enctype: 'multipart/form-data'
				} );

			$iframe.one( 'load', function () {
				$iframe.one( 'load', function () {
					var result = processIframeResult( iframe );
					deferred.notify( 1 );

					if ( !result ) {
						deferred.reject( 'ok-but-empty', 'No response from API on upload attempt.' );
					} else if ( result.error ) {
						if ( result.error.code === 'badtoken' ) {
							api.badToken( 'csrf' );
						}

						deferred.reject( result.error.code, result );
					} else if ( result.upload && result.upload.warnings ) {
						deferred.reject( getFirstKey( result.upload.warnings ), result );
					} else {
						deferred.resolve( result );
					}
				} );
				tokenPromise.done( function () {
					$form.submit();
				} );
			} );

			$iframe.on( 'error', function ( error ) {
				deferred.reject( 'http', error );
			} );

			$iframe.prop( 'src', 'about:blank' ).hide();

			file.name = 'file';

			$.each( data, function ( key, val ) {
				$form.append( getHiddenInput( key, val ) );
			} );

			if ( !data.filename && !data.stash ) {
				throw new Error( 'Filename not included in file data.' );
			}

			if ( this.needToken() ) {
				this.getEditToken().then( function ( token ) {
					$form.append( getHiddenInput( 'token', token ) );
					tokenPromise.resolve();
				}, tokenPromise.reject );
			} else {
				tokenPromise.resolve();
			}

			$( 'body' ).append( $form, $iframe );

			deferred.always( function () {
				$form.remove();
				$iframe.remove();
			} );

			return deferred.promise();
		},

		/**
		 * Uploads a file using the FormData API.
		 *
		 * @private
		 * @param {File} file
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		uploadWithFormData: function ( file, data ) {
			var key,
				deferred = $.Deferred();

			for ( key in data ) {
				if ( !fieldsAllowed[ key ] ) {
					delete data[ key ];
				}
			}

			data = $.extend( {}, this.defaults.parameters, { action: 'upload' }, data );
			data.file = file;

			if ( !data.filename && !data.stash ) {
				throw new Error( 'Filename not included in file data.' );
			}

			// Use this.postWithEditToken() or this.post()
			this[ this.needToken() ? 'postWithEditToken' : 'post' ]( data, {
				// Use FormData (if we got here, we know that it's available)
				contentType: 'multipart/form-data',
				// No timeout (default from mw.Api is 30 seconds)
				timeout: 0,
				// Provide upload progress notifications
				xhr: function () {
					var xhr = $.ajaxSettings.xhr();
					if ( xhr.upload ) {
						// need to bind this event before we open the connection (see note at
						// https://developer.mozilla.org/en-US/docs/DOM/XMLHttpRequest/Using_XMLHttpRequest#Monitoring_progress)
						xhr.upload.addEventListener( 'progress', function ( ev ) {
							if ( ev.lengthComputable ) {
								deferred.notify( ev.loaded / ev.total );
							}
						} );
					}
					return xhr;
				}
			} )
				.done( function ( result ) {
					deferred.notify( 1 );
					if ( result.upload && result.upload.warnings ) {
						deferred.reject( getFirstKey( result.upload.warnings ), result );
					} else {
						deferred.resolve( result );
					}
				} )
				.fail( function ( errorCode, result ) {
					deferred.notify( 1 );
					deferred.reject( errorCode, result );
				} );

			return deferred.promise();
		},

		/**
		 * Upload a file to the stash.
		 *
		 * This function will return a promise, which when resolved, will pass back a function
		 * to finish the stash upload. You can call that function with an argument containing
		 * more, or conflicting, data to pass to the server. For example:
		 *
		 *     // upload a file to the stash with a placeholder filename
		 *     api.uploadToStash( file, { filename: 'testing.png' } ).done( function ( finish ) {
		 *         // finish is now the function we can use to finalize the upload
		 *         // pass it a new filename from user input to override the initial value
		 *         finish( { filename: getFilenameFromUser() } ).done( function ( data ) {
		 *             // the upload is complete, data holds the API response
		 *         } );
		 *     } );
		 *
		 * @param {File|HTMLInputElement} file
		 * @param {Object} [data]
		 * @return {jQuery.Promise}
		 * @return {Function} return.finishStashUpload Call this function to finish the upload.
		 * @return {Object} return.finishStashUpload.data Additional data for the upload.
		 * @return {jQuery.Promise} return.finishStashUpload.return API promise for the final upload
		 * @return {Object} return.finishStashUpload.return.data API return value for the final upload
		 */
		uploadToStash: function ( file, data ) {
			var filekey,
				api = this;

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			function finishUpload( moreData ) {
				return api.uploadFromStash( filekey, $.extend( data, moreData ) );
			}

			return this.upload( file, { stash: true, filename: data.filename } ).then(
				function ( result ) {
					filekey = result.upload.filekey;
					return finishUpload;
				},
				function ( errorCode, result ) {
					if ( result && result.upload && result.upload.filekey ) {
						// Ignore any warnings if 'filekey' was returned, that's all we care about
						filekey = result.upload.filekey;
						return $.Deferred().resolve( finishUpload );
					}
					return $.Deferred().reject( errorCode, result );
				}
			);
		},

		/**
		 * Finish an upload in the stash.
		 *
		 * @param {string} filekey
		 * @param {Object} data
		 * @return {jQuery.Promise}
		 */
		uploadFromStash: function ( filekey, data ) {
			data.filekey = filekey;
			data.action = 'upload';
			data.format = 'json';

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			return this.postWithEditToken( data ).then( function ( result ) {
				if ( result.upload && result.upload.warnings ) {
					return $.Deferred().reject( getFirstKey( result.upload.warnings ), result ).promise();
				}
				return result;
			} );
		},

		needToken: function () {
			return true;
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.upload
	 */
}( mediaWiki, jQuery ) );
