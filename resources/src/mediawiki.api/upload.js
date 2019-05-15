/**
 * Provides an interface for uploading files to MediaWiki.
 *
 * @class mw.Api.plugin.upload
 * @singleton
 */
( function () {
	var nonce = 0,
		fieldsAllowed = {
			stash: true,
			filekey: true,
			filename: true,
			comment: true,
			text: true,
			watchlist: true,
			ignorewarnings: true,
			chunk: true,
			offset: true,
			filesize: true,
			async: true
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
			return key;
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
					$form.trigger( 'submit' );
				} );
			} );

			$iframe.on( 'error', function ( error ) {
				deferred.reject( 'http', error );
			} );

			$iframe.prop( 'src', 'about:blank' ).hide();

			file.name = 'file';

			// eslint-disable-next-line no-jquery/no-each-util
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
			var key, request,
				deferred = $.Deferred();

			for ( key in data ) {
				if ( !fieldsAllowed[ key ] ) {
					delete data[ key ];
				}
			}

			data = $.extend( {}, this.defaults.parameters, { action: 'upload' }, data );
			if ( !data.chunk ) {
				data.file = file;
			}

			if ( !data.filename && !data.stash ) {
				throw new Error( 'Filename not included in file data.' );
			}

			// Use this.postWithEditToken() or this.post()
			request = this[ this.needToken() ? 'postWithEditToken' : 'post' ]( data, {
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

			return deferred.promise( { abort: request.abort } );
		},

		/**
		 * Upload a file in several chunks.
		 *
		 * @param {File} file
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @param {number} [chunkSize] Size (in bytes) per chunk (default: 5MB)
		 * @param {number} [chunkRetries] Amount of times to retry a failed chunk (default: 1)
		 * @return {jQuery.Promise}
		 */
		chunkedUpload: function ( file, data, chunkSize, chunkRetries ) {
			var start, end, promise, next, active,
				deferred = $.Deferred();

			chunkSize = chunkSize === undefined ? 5 * 1024 * 1024 : chunkSize;
			chunkRetries = chunkRetries === undefined ? 1 : chunkRetries;

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			// Submit first chunk to get the filekey
			active = promise = this.uploadChunk( file, data, 0, chunkSize, '', chunkRetries )
				.done( chunkSize >= file.size ? deferred.resolve : null )
				.fail( deferred.reject )
				.progress( deferred.notify );

			// Now iteratively submit the rest of the chunks
			for ( start = chunkSize; start < file.size; start += chunkSize ) {
				end = Math.min( start + chunkSize, file.size );
				next = $.Deferred();

				// We could simply chain one this.uploadChunk after another with
				// .then(), but then we'd hit an `Uncaught RangeError: Maximum
				// call stack size exceeded` at as low as 1024 calls in Firefox
				// 47. This'll work around it, but comes with the drawback of
				// having to properly relay the results to the returned promise.
				// eslint-disable-next-line no-loop-func
				promise.done( function ( start, end, next, result ) {
					var filekey = result.upload.filekey;
					active = this.uploadChunk( file, data, start, end, filekey, chunkRetries )
						.done( end === file.size ? deferred.resolve : next.resolve )
						.fail( deferred.reject )
						.progress( deferred.notify );
				// start, end & next must be bound to closure, or they'd have
				// changed by the time the promises are resolved
				}.bind( this, start, end, next ) );

				promise = next;
			}

			return deferred.promise( { abort: active.abort } );
		},

		/**
		 * Uploads 1 chunk.
		 *
		 * @private
		 * @param {File} file
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @param {number} start Chunk start position
		 * @param {number} end Chunk end position
		 * @param {string} [filekey] File key, for follow-up chunks
		 * @param {number} [retries] Amount of times to retry request
		 * @return {jQuery.Promise}
		 */
		uploadChunk: function ( file, data, start, end, filekey, retries ) {
			var upload,
				api = this,
				chunk = this.slice( file, start, end );

			// When uploading in chunks, we're going to be issuing a lot more
			// requests and there's always a chance of 1 getting dropped.
			// In such case, it could be useful to try again: a network hickup
			// doesn't necessarily have to result in upload failure...
			retries = retries === undefined ? 1 : retries;

			data.filesize = file.size;
			data.chunk = chunk;
			data.offset = start;

			// filekey must only be added when uploading follow-up chunks; the
			// first chunk should never have a filekey (it'll be generated)
			if ( filekey && start !== 0 ) {
				data.filekey = filekey;
			}

			upload = this.uploadWithFormData( file, data );
			return upload.then(
				null,
				function ( code, result ) {
					var retry;

					// uploadWithFormData will reject uploads with warnings, but
					// these warnings could be "harmless" or recovered from
					// (e.g. exists-normalized, when it'll be renamed later)
					// In the case of (only) a warning, we still want to
					// continue the chunked upload until it completes: then
					// reject it - at least it's been fully uploaded by then and
					// failure handlers have a complete result object (including
					// possibly more warnings, e.g. duplicate)
					// This matches .upload, which also completes the upload.
					if ( result.upload && result.upload.warnings && code in result.upload.warnings ) {
						if ( end === file.size ) {
							// uploaded last chunk = reject with result data
							return $.Deferred().reject( code, result );
						} else {
							// still uploading chunks = resolve to keep going
							return $.Deferred().resolve( result );
						}
					}

					if ( retries === 0 ) {
						return $.Deferred().reject( code, result );
					}

					// If the call flat out failed, we may want to try again...
					retry = api.uploadChunk.bind( api, file, data, start, end, filekey, retries - 1 );
					return api.retry( code, result, retry );
				},
				function ( fraction ) {
					// Since we're only uploading small parts of a file, we
					// need to adjust the reported progress to reflect where
					// we actually are in the combined upload
					return ( start + fraction * ( end - start ) ) / file.size;
				}
			).promise( { abort: upload.abort } );
		},

		/**
		 * Launch the upload anew if it failed because of network issues.
		 *
		 * @private
		 * @param {string} code Error code
		 * @param {Object} result API result
		 * @param {Function} callable
		 * @return {jQuery.Promise}
		 */
		retry: function ( code, result, callable ) {
			var uploadPromise,
				retryTimer,
				deferred = $.Deferred(),
				// Wrap around the callable, so that once it completes, it'll
				// resolve/reject the promise we'll return
				retry = function () {
					uploadPromise = callable();
					uploadPromise.then( deferred.resolve, deferred.reject );
				};

			// Don't retry if the request failed because we aborted it (or if
			// it's another kind of request failure)
			if ( code !== 'http' || result.textStatus === 'abort' ) {
				return deferred.reject( code, result );
			}

			retryTimer = setTimeout( retry, 1000 );
			return deferred.promise( { abort: function () {
				// Clear the scheduled upload, or abort if already in flight
				if ( retryTimer ) {
					clearTimeout( retryTimer );
				}
				if ( uploadPromise.abort ) {
					uploadPromise.abort();
				}
			} } );
		},

		/**
		 * Slice a chunk out of a File object.
		 *
		 * @private
		 * @param {File} file
		 * @param {number} start
		 * @param {number} stop
		 * @return {Blob}
		 */
		slice: function ( file, start, stop ) {
			if ( file.mozSlice ) {
				// FF <= 12
				return file.mozSlice( start, stop, file.type );
			} else if ( file.webkitSlice ) {
				// Chrome <= 20
				return file.webkitSlice( start, stop, file.type );
			} else {
				// On really old browser versions (before slice was prefixed),
				// slice() would take (start, length) instead of (start, end)
				// We'll ignore that here...
				return file.slice( start, stop, file.type );
			}
		},

		/**
		 * This function will handle how uploads to stash (via uploadToStash or
		 * chunkedUploadToStash) are resolved/rejected.
		 *
		 * After a successful stash, it'll resolve with a callback which, when
		 * called, will finalize the upload in stash (with the given data, or
		 * with additional/conflicting data)
		 *
		 * A failed stash can still be recovered from as long as 'filekey' is
		 * present. In that case, it'll also resolve with the callback to
		 * finalize the upload (all warnings are then ignored.)
		 * Otherwise, it'll just reject as you'd expect, with code & result.
		 *
		 * @private
		 * @param {jQuery.Promise} uploadPromise
		 * @param {Object} data
		 * @return {jQuery.Promise}
		 * @return {Function} return.finishUpload Call this function to finish the upload.
		 * @return {Object} return.finishUpload.data Additional data for the upload.
		 * @return {jQuery.Promise} return.finishUpload.return API promise for the final upload
		 * @return {Object} return.finishUpload.return.data API return value for the final upload
		 */
		finishUploadToStash: function ( uploadPromise, data ) {
			var filekey,
				api = this;

			function finishUpload( moreData ) {
				return api.uploadFromStash( filekey, $.extend( data, moreData ) );
			}

			return uploadPromise.then(
				function ( result ) {
					filekey = result.upload.filekey;
					return finishUpload;
				},
				function ( errorCode, result ) {
					if ( result && result.upload && result.upload.result === 'Success' && result.upload.filekey ) {
						// Catch handler is also called in case of warnings (e.g. 'duplicate')
						// We don't really care about those warnings, as long as the upload got stashed...
						filekey = result.upload.filekey;
						return $.Deferred().resolve( finishUpload );
					}
					return $.Deferred().reject( errorCode, result );
				}
			);
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
		 * @return {Function} return.finishUpload Call this function to finish the upload.
		 * @return {Object} return.finishUpload.data Additional data for the upload.
		 * @return {jQuery.Promise} return.finishUpload.return API promise for the final upload
		 * @return {Object} return.finishUpload.return.data API return value for the final upload
		 */
		uploadToStash: function ( file, data ) {
			var promise;

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			promise = this.upload( file, { stash: true, filename: data.filename } );

			return this.finishUploadToStash( promise, data );
		},

		/**
		 * Upload a file to the stash, in chunks.
		 *
		 * This function will return a promise, which when resolved, will pass back a function
		 * to finish the stash upload.
		 *
		 * @see #method-uploadToStash
		 * @param {File|HTMLInputElement} file
		 * @param {Object} [data]
		 * @param {number} [chunkSize] Size (in bytes) per chunk (default: 5MB)
		 * @param {number} [chunkRetries] Amount of times to retry a failed chunk (default: 1)
		 * @return {jQuery.Promise}
		 * @return {Function} return.finishUpload Call this function to finish the upload.
		 * @return {Object} return.finishUpload.data Additional data for the upload.
		 * @return {jQuery.Promise} return.finishUpload.return API promise for the final upload
		 * @return {Object} return.finishUpload.return.data API return value for the final upload
		 */
		chunkedUploadToStash: function ( file, data, chunkSize, chunkRetries ) {
			var promise;

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			promise = this.chunkedUpload(
				file,
				{ stash: true, filename: data.filename },
				chunkSize,
				chunkRetries
			);

			return this.finishUploadToStash( promise, data );
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
}() );
