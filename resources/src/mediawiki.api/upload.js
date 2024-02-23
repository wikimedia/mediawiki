( function () {
	var
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
	 * Given a non-empty object, return one of its keys.
	 *
	 * @private
	 * @param {Object} obj
	 * @return {string}
	 */
	function getFirstKey( obj ) {
		return obj[ Object.keys( obj )[ 0 ] ];
	}

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * Upload a file to MediaWiki.
		 *
		 * The file will be uploaded using AJAX and FormData.
		 *
		 * @param {HTMLInputElement|File|Blob} file HTML input type=file element with a file already inside
		 *  of it, or a File object.
		 * @param {Object} data Other upload options, see action=upload API docs for more
		 * @return {jQuery.Promise}
		 */
		upload: function ( file, data ) {
			if ( file && file.nodeType === Node.ELEMENT_NODE && file.files ) {
				file = file.files[ 0 ];
			}

			if ( !file ) {
				throw new Error( 'No file' );
			}

			// Blobs are allowed in formdata uploads, it turns out
			if ( !( file instanceof window.File || file instanceof window.Blob ) ) {
				throw new Error( 'Unsupported argument type passed to mw.Api.upload' );
			}

			return this.uploadWithFormData( file, data );
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
		 * @param {number} [chunkSize] Size (in bytes) per chunk (default: 5 MiB)
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
				promise.done( function ( s, e, n, result ) {
					var filekey = result.upload.filekey;
					active = this.uploadChunk( file, data, s, e, filekey, chunkRetries )
						.done( e === file.size ? deferred.resolve : n.resolve )
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
					if ( result.upload && result.upload.warnings ) {
						if ( end === file.size ) {
							// uploaded last chunk = reject with result data
							return $.Deferred().reject( result.upload.warnings.code || 'unknown', result );
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
		 * @return {jQuery.Promise<function(Object): jQuery.Promise>} Promise that resolves with a
		 *  function that should be called to finish the upload.
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
						// When a file is uploaded with `ignorewarnings` and there are warnings,
						// the promise will be rejected (because of those warnings, e.g. 'duplicate')
						// but the result is actually a success
						// We don't really care about those warnings, as long as the upload got stashed...
						// Turn this back into a successful promise and allow the upload to complete
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
		 * more, or conflicting, data to pass to the server.
		 *
		 * @example
		 * // upload a file to the stash with a placeholder filename
		 * api.uploadToStash( file, { filename: 'testing.png' } ).done( function ( finish ) {
		 *     // finish is now the function we can use to finalize the upload
		 *     // pass it a new filename from user input to override the initial value
		 *     finish( { filename: getFilenameFromUser() } ).done( function ( data ) {
		 *         // the upload is complete, data holds the API response
		 *     } );
		 * } );
		 *
		 * @param {File|HTMLInputElement} file
		 * @param {Object} [data]
		 * @return {jQuery.Promise<function(Object): jQuery.Promise>} Promise that resolves with a
		 *  function that should be called to finish the upload.
		 */
		uploadToStash: function ( file, data ) {
			var promise;

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			promise = this.upload( file, { stash: true, filename: data.filename, ignorewarnings: data.ignorewarnings } );

			return this.finishUploadToStash( promise, data );
		},

		/**
		 * Upload a file to the stash, in chunks.
		 *
		 * This function will return a promise, which when resolved, will pass back a function
		 * to finish the stash upload.
		 *
		 * @see mw.Api#uploadToStash
		 * @param {File|HTMLInputElement} file
		 * @param {Object} [data]
		 * @param {number} [chunkSize] Size (in bytes) per chunk (default: 5 MiB)
		 * @param {number} [chunkRetries] Amount of times to retry a failed chunk (default: 1)
		 * @return {jQuery.Promise<function(Object): jQuery.Promise>} Promise that resolves with a
		 *  function that should be called to finish the upload.
		 */
		chunkedUploadToStash: function ( file, data, chunkSize, chunkRetries ) {
			var promise;

			if ( !data.filename ) {
				throw new Error( 'Filename not included in file data.' );
			}

			promise = this.chunkedUpload(
				file,
				{ stash: true, filename: data.filename, ignorewarnings: data.ignorewarnings },
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

		/**
		 * @private
		 * @return {boolean}
		 */
		needToken: function () {
			return true;
		}
	} );

}() );
