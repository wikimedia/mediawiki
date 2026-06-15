/**
 * JavaScript for supporting chunked uploads in Special:Upload.
 *
 * @private
 * @module chunkedUpload
 */

const uploadWarnings = require( './warnings.js' );

class ChunkedUpload {
	/**
	 * @param {Object|null} confirmCloseWindow
	 */
	constructor( confirmCloseWindow ) {
		this.confirmCloseWindow = confirmCloseWindow;
		this.isReupload = new URLSearchParams( window.location.search ).get( 'wpForReUpload' ) === '1';

		this.$form = $( '#mw-upload-form' );
		this.$submit = this.$form.find( '[name="wpUpload"]' );

		// Track the last stashed file so a resubmission of the same exact file
		// with a modified description or title, where a change was needed to fix a
		// warning, can skip being reuploaded unnecessarily.
		this.lastFile = null;
		this.lastUploader = null;
	}

	setup() {
		this.$progressBarBar = $( '<div>' ).addClass( 'cdx-progress-bar__bar' );

		this.$progressBar = $( '<div>' )
			.addClass( 'cdx-progress-bar cdx-progress-bar--block mw-upload-progress-bar mw-upload-progress-bar--hidden' )
			.attr( {
				role: 'progressbar',
				'aria-label': mw.msg( 'upload-progressbar-label' )
			} )
			.append( this.$progressBarBar )
			.insertAfter( this.$submit.parent() );

		this.$progressStatus = $( '<div>' )
			.addClass( 'mw-upload-progress-status' )
			.insertAfter( this.$progressBar );

		this.$form.on( 'submit', this.handleSubmit.bind( this ) );
	}

	/**
	 * Clear existing warning and error messages.
	 */
	clearMessages() {
		$( '#mw-upload-form .mw-upload-notices' ).remove();
	}

	/**
	 * Show a warning or error message at the top of the upload form.
	 *
	 * The message box this function creates intentionally mirrors messages
	 * sent by the POST Special:Upload in the PHP code path as closely
	 * as possible.
	 *
	 * @param {string} headingText
	 * @param {string|Node} content
	 * @param {string} type 'warning' or 'error'
	 */
	showMessage( headingText, content, type ) {
		const wrapper = document.createElement( 'div' );
		wrapper.classList.add( 'mw-upload-notices' );

		const heading = document.createElement( 'h2' );
		heading.textContent = headingText;
		wrapper.appendChild( heading );

		wrapper.appendChild( mw.util.messageBox( content, type ) );

		$( wrapper ).prependTo( '#mw-upload-form' );
		wrapper.scrollIntoView( { behavior: 'smooth', block: 'start' } );
	}

	/**
	 * Show a warning at the top of the upload form.
	 *
	 * @param {Node} list
	 */
	showWarning( list ) {
		const body = document.createDocumentFragment();
		body.appendChild( document.createTextNode( mw.msg( 'uploadwarning-text' ) ) );
		body.appendChild( list );

		this.showMessage( mw.msg( 'uploadwarning' ), body, 'warning' );
	}

	/**
	 * Show an error at the top of the upload form.
	 *
	 * @param {string} content
	 */
	showError( content ) {
		this.showMessage( mw.msg( 'uploaderror' ), content, 'error' );
	}

	/**
	 * Set the progress bar's value.
	 *
	 * Mirrors the Codex ProgressBar component's own `value` prop contract:
	 * a number (0-100) shows a determinate bar at that value, while `null`
	 * switches to the indeterminate (scrolling) animation, for phases where
	 * progress can't be measured, such as publishing the stashed file.
	 *
	 * @param {number|null} value
	 */
	setProgress( value ) {
		const hasValue = typeof value === 'number';

		this.$progressBarBar.toggleClass( 'cdx-progress-bar__bar--determinate', hasValue );

		if ( hasValue ) {
			this.$progressBar
				.attr( {
					'aria-valuemin': 0,
					'aria-valuemax': 100,
					'aria-valuenow': value
				} )
				.css( {
					'--cdx-progress-value': value,
					'--cdx-progress-max': 100
				} );
		} else {
			this.$progressBar
				.removeAttr( 'aria-valuemin aria-valuemax aria-valuenow' )
				.css( { '--cdx-progress-value': '', '--cdx-progress-max': '' } );
		}
	}

	/**
	 * Show the progress bar in the upload form.
	 */
	showProgress() {
		this.$progressBar.removeClass( 'mw-upload-progress-bar--hidden' );
	}

	/**
	 * Hide the progress bar in the upload form.
	 */
	hideProgress() {
		this.$progressBar.addClass( 'mw-upload-progress-bar--hidden' );
		this.setProgress( null );
		this.clearStatus();
	}

	/**
	 * Show the "publishing" status message below the progress bar.
	 */
	showPublishingStatus() {
		this.$progressStatus.msg( 'upload-progress-publishing' );
	}

	/**
	 * Clear the status message below the progress bar.
	 */
	clearStatus() {
		this.$progressStatus.empty();
	}

	/**
	 * Redirect to the file page of the uploaded file.
	 *
	 * @param {Object} result
	 */
	handleSuccess( result ) {
		if ( this.confirmCloseWindow ) {
			this.confirmCloseWindow.release();
		}
		this.$submit.prop( 'disabled', false );
		window.location.assign( result.upload.imageinfo.descriptionurl );
	}

	/**
	 * Handle an upload attempt error.
	 *
	 * @param {string} errorCode
	 * @param {Object} result
	 */
	handleError( errorCode, result ) {
		this.clearMessages();

		// The API client rejects upload promises whenever warnings
		// are present, even when 'ignorewarnings' let the file be
		// published anyway. Treat this as a success.
		if ( result && result.upload && result.upload.imageinfo ) {
			this.handleSuccess( result );
			return;
		}

		if ( result && result.upload && result.upload.warnings ) {
			if ( result.upload.warnings.badfilename ) {
				$( '#wpDestFile' ).val( result.upload.warnings.badfilename );
			}

			// The 'exists' warning is expected when uploading a new
			// revision of an existing file. If uploading a new revision
			// then ignore that warning. This can't be used to overwrite
			// a file without permission if the user lacks the right.
			if ( result.upload.warnings.exists && this.isReupload ) {
				delete result.upload.warnings.exists;
			}

			// Transparently resubmit with 'ignorewarnings=1' if all
			// warnings have been filtered since the API provides no
			// way to ignore a specific warning key.
			if ( !this.resubmitted && Object.keys( result.upload.warnings ).length === 0 ) {
				this.resubmitted = true;
				this.uploader.setIgnoreWarnings( true );
				this.uploader.finishStashUpload()
					.then( this.handleSuccess.bind( this ) )
					.catch( this.handleError.bind( this ) );
				return;
			}

			const warnings = uploadWarnings.renderWarnings(
				result.upload.warnings,
				$( '#wpDestFile' ).val()
			);
			this.showWarning( warnings );

			this.hideProgress();
			this.$submit.prop( 'disabled', false );
			return;
		}

		let message;
		if ( result ) {
			message = ( new mw.Api() ).getErrorMessage( result ).text();
		}
		this.showError( message || String( errorCode ) );

		this.hideProgress();
		this.$submit.prop( 'disabled', false );
	}

	/**
	 * @param {jQuery.Event} e
	 */
	handleSubmit( e ) {
		// Only handle submissions for direct uploads, not URL uploads.
		const $sourceFile = $( '#wpSourceTypeFile' );
		if ( $sourceFile.length && !$sourceFile.prop( 'checked' ) ) {
			if ( this.confirmCloseWindow ) {
				this.confirmCloseWindow.release();
			}
			return;
		}

		// Check for existence in case a gadget removed it (T262844).
		const $wpUploadFile = $( '#wpUploadFile' );
		const file = $wpUploadFile.length && $wpUploadFile[ 0 ].files[ 0 ];
		if ( !file || !window.FormData ) {
			if ( this.confirmCloseWindow ) {
				this.confirmCloseWindow.release();
			}
			return;
		}

		const maxUploadSize = mw.config.get( 'wgMaxUploadSize' ).file;
		// null when the server reports no detectable PHP upload limit.
		const phpUploadLimit = mw.config.get( 'wgMaxPhpUploadSize' ) || null;
		// The largest a single PHP request can accept, further bounded by the
		// site's own maximum upload size (never chunk beyond what could ever
		// legally be uploaded).
		const requestSizeLimit = phpUploadLimit !== null ?
			Math.min( phpUploadLimit, maxUploadSize ) : maxUploadSize;

		// Files larger than the maximum upload size can't be uploaded by any
		// path, so reject them before uploading anything.
		if ( file.size > maxUploadSize ) {
			e.preventDefault();
			this.clearMessages();
			this.showError( mw.msg( 'largefileserver' ) );
			return;
		}

		// Files that fit within a single PHP request don't need chunking; let
		// the regular form submission handle them.
		if ( file.size <= requestSizeLimit && file !== this.lastFile ) {
			if ( this.confirmCloseWindow ) {
				this.confirmCloseWindow.release();
			}
			return;
		}

		e.preventDefault();

		this.$submit.prop( 'disabled', true );

		this.resubmitted = false;

		const filename = $( '#wpDestFile' ).val();
		const description = $( '#wpUploadDescription' ).val();
		const license = $( '#wpLicense' ).val() || '';
		const copyStatus = $( '#wpUploadCopyStatus' ).val() || '';
		const source = $( '#wpUploadSource' ).val() || '';
		const watch = $( '#wpWatchthis' ).is( ':checked' );
		const ignoreWarnings = $( '#wpIgnoreWarning' ).is( ':checked' );

		this.clearMessages();

		// Upload the file and stash it if the file currently in the input
		// field hasn't been uploaded yet.
		let uploadResult;
		if ( file === this.lastFile && this.lastUploader ) {
			this.uploader = this.lastUploader;
			uploadResult = this.uploader.stashPromise;
		} else {
			this.uploader = new mw.Upload();
			this.uploader.setFile( file );
			this.uploader.setFilename( filename );

			// Aim for 50 MiB chunks, but never below the server's minimum
			// chunk size, and never above what a single PHP request accepts.
			const minChunkSize = mw.config.get( 'wgMinUploadChunkSize' ) || 0;
			const chunkSize = Math.min(
				Math.max( 50 * 1024 * 1024, minChunkSize ),
				requestSizeLimit
			);
			this.uploader.setChunkSize( chunkSize );

			uploadResult = this.uploader.uploadToStash();
			this.showProgress();

			// Keep references to the file and uploader only once the stash
			// is successfully uploaded so any warnings caused by the title
			// can be reattempted.
			uploadResult
				.progress( ( fraction ) => {
					const clampedPercent = Math.round( Math.min( fraction, 1 ) * 100 );
					this.setProgress( clampedPercent );
				} )
				.then( () => {
					this.lastFile = file;
					this.lastUploader = this.uploader;
				} );
		}

		uploadResult
			.then( () => {
				this.uploader.setFilename( filename );
				this.uploader.setComment( description );
				this.uploader.setLicense( license );
				this.uploader.setCopyStatus( copyStatus );
				this.uploader.setSource( source );
				this.uploader.setWatchlist( watch );
				this.uploader.setIgnoreWarnings( ignoreWarnings );

				// Generate the file description text server-side at publish.
				this.uploader.setAutoText( true );

				// Publishing the stashed file has no measurable progress of
				// its own, so switch to the indeterminate animation and let
				// the user know what's happening.
				this.showProgress();
				this.setProgress( null );
				this.showPublishingStatus();

				return this.uploader.finishStashUpload();
			} )
			.then( this.handleSuccess.bind( this ) )
			.catch( this.handleError.bind( this ) );
	}
}

module.exports = ChunkedUpload;
