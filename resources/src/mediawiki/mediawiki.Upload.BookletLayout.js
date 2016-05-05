/*global moment*/
( function ( $, mw, moment ) {

	/**
	 * mw.Upload.BookletLayout encapsulates the process of uploading a file
	 * to MediaWiki using the {@link mw.Upload upload model}.
	 * The booklet emits events that can be used to get the stashed
	 * upload and the final file. It can be extended to accept
	 * additional fields from the user for specific scenarios like
	 * for Commons, or campaigns.
	 *
	 * ## Structure
	 *
	 * The {@link OO.ui.BookletLayout booklet layout} has three steps:
	 *
	 *  - **Upload**: Has a {@link OO.ui.SelectFileWidget field} to get the file object.
	 *
	 * - **Information**: Has a {@link OO.ui.FormLayout form} to collect metadata. This can be
	 *   extended.
	 *
	 * - **Insert**: Has details on how to use the file that was uploaded.
	 *
	 * Each step has a form associated with it defined in
	 * {@link #renderUploadForm renderUploadForm},
	 * {@link #renderInfoForm renderInfoForm}, and
	 * {@link #renderInsertForm renderInfoForm}. The
	 * {@link #getFile getFile},
	 * {@link #getFilename getFilename}, and
	 * {@link #getText getText} methods are used to get
	 * the information filled in these forms, required to call
	 * {@link mw.Upload mw.Upload}.
	 *
	 * ## Usage
	 *
	 * See the {@link mw.Upload.Dialog upload dialog}.
	 *
	 * The {@link #event-fileUploaded fileUploaded},
	 * and {@link #event-fileSaved fileSaved} events can
	 * be used to get details of the upload.
	 *
	 * ## Extending
	 *
	 * To extend using {@link mw.Upload mw.Upload}, override
	 * {@link #renderInfoForm renderInfoForm} to render
	 * the form required for the specific use-case. Update the
	 * {@link #getFilename getFilename}, and
	 * {@link #getText getText} methods to return data
	 * from your newly created form. If you added new fields you'll also have
	 * to update the {@link #clear} method.
	 *
	 * If you plan to use a different upload model, apart from what is mentioned
	 * above, you'll also have to override the
	 * {@link #createUpload createUpload} method to
	 * return the new model. The {@link #saveFile saveFile}, and
	 * the {@link #uploadFile uploadFile} methods need to be
	 * overridden to use the new model and data returned from the forms.
	 *
	 * @class
	 * @extends OO.ui.BookletLayout
	 *
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {jQuery} [$overlay] Overlay to use for widgets in the booklet
	 * @cfg {string} [filekey] Sets the stashed file to finish uploading. Overrides most of the file selection process, and fetches a thumbnail from the server.
	 */
	mw.Upload.BookletLayout = function ( config ) {
		// Parent constructor
		mw.Upload.BookletLayout.parent.call( this, config );

		this.$overlay = config.$overlay;

		this.filekey = config.filekey;

		this.renderUploadForm();
		this.renderInfoForm();
		this.renderInsertForm();

		this.addPages( [
			new OO.ui.PageLayout( 'upload', {
				scrollable: true,
				padded: true,
				content: [ this.uploadForm ]
			} ),
			new OO.ui.PageLayout( 'info', {
				scrollable: true,
				padded: true,
				content: [ this.infoForm ]
			} ),
			new OO.ui.PageLayout( 'insert', {
				scrollable: true,
				padded: true,
				content: [ this.insertForm ]
			} )
		] );
	};

	/* Setup */

	OO.inheritClass( mw.Upload.BookletLayout, OO.ui.BookletLayout );

	/* Events */

	/**
	 * Progress events for the uploaded file
	 *
	 * @event fileUploadProgress
	 * @param {number} progress In percentage
	 * @param {Object} duration Duration object from `moment.duration()`
	 */

	/**
	 * The file has finished uploading
	 *
	 * @event fileUploaded
	 */

	/**
	 * The file has been saved to the database
	 *
	 * @event fileSaved
	 * @param {Object} imageInfo See mw.Upload#getImageInfo
	 */

	/**
	 * The upload form has changed
	 *
	 * @event uploadValid
	 * @param {boolean} isValid The form is valid
	 */

	/**
	 * The info form has changed
	 *
	 * @event infoValid
	 * @param {boolean} isValid The form is valid
	 */

	/* Properties */

	/**
	 * @property {OO.ui.FormLayout} uploadForm
	 * The form rendered in the first step to get the file object.
	 * Rendered in {@link #renderUploadForm renderUploadForm}.
	 */

	/**
	 * @property {OO.ui.FormLayout} infoForm
	 * The form rendered in the second step to get metadata.
	 * Rendered in {@link #renderInfoForm renderInfoForm}
	 */

	/**
	 * @property {OO.ui.FormLayout} insertForm
	 * The form rendered in the third step to show usage
	 * Rendered in {@link #renderInsertForm renderInsertForm}
	 */

	/* Methods */

	/**
	 * Initialize for a new upload
	 *
	 * @return {jQuery.Promise} Promise resolved when everything is initialized
	 */
	mw.Upload.BookletLayout.prototype.initialize = function () {
		var booklet = this;

		this.clear();
		this.upload = this.createUpload();

		this.setPage( 'upload' );

		if ( this.filekey ) {
			this.setFilekey( this.filekey );
		}

		return this.upload.getApi().then(
			function ( api ) {
				return $.when(
					booklet.upload.loadConfig(),
					// If the user can't upload anything, don't give them the option to.
					api.getUserInfo().then( function ( userInfo ) {
						if ( userInfo.rights.indexOf( 'upload' ) === -1 ) {
							// TODO Use a better error message when not all logged-in users can upload
							booklet.getPage( 'upload' ).$element.msg( 'api-error-mustbeloggedin' );
						}
						return $.Deferred().resolve();
					} )
				).then(
					null,
					// Always resolve, never reject
					function () { return $.Deferred().resolve(); }
				);
			},
			function ( errorMsg ) {
				booklet.getPage( 'upload' ).$element.msg( errorMsg );
				return $.Deferred().resolve();
			}
		);
	};

	/**
	 * Create a new upload model
	 *
	 * @protected
	 * @return {mw.Upload} Upload model
	 */
	mw.Upload.BookletLayout.prototype.createUpload = function () {
		return new mw.Upload();
	};

	/* Uploading */

	/**
	 * Uploads the file that was added in the upload form. Uses
	 * {@link #getFile getFile} to get the HTML5
	 * file object.
	 *
	 * @protected
	 * @fires fileUploadProgress
	 * @fires fileUploaded
	 * @return {jQuery.Promise}
	 */
	mw.Upload.BookletLayout.prototype.uploadFile = function () {
		var deferred = $.Deferred(),
			startTime = new Date(),
			layout = this,
			file = this.getFile();

		this.setPage( 'info' );

		if ( this.filekey ) {
			if ( file === null ) {
				// Someone gonna get-a hurt real bad
				throw new Error( 'filekey not passed into file select widget, which is impossible. Quitting while we\'re behind.' );
			}

			// Stashed file already uploaded.
			deferred.resolve();
			this.uploadPromise = deferred;
			this.emit( 'fileUploaded' );
			return deferred;
		}

		this.setFilename( file.name );

		this.upload.setFile( file );
		// The original file name might contain invalid characters, so use our sanitized one
		this.upload.setFilename( this.getFilename() );

		this.uploadPromise = this.upload.uploadToStash();
		this.uploadPromise.then( function () {
			deferred.resolve();
			layout.emit( 'fileUploaded' );
		}, function () {
			// These errors will be thrown while the user is on the info page.
			// Pretty sure it's impossible to get a warning other than 'stashfailed' here, which should
			// really be an error...
			var errorMessage = layout.getErrorMessageForStateDetails();
			deferred.reject( errorMessage );
		}, function ( progress ) {
			var elapsedTime = new Date() - startTime,
				estimatedTotalTime = ( 1 / progress ) * elapsedTime,
				estimatedRemainingTime = moment.duration( estimatedTotalTime - elapsedTime );
			layout.emit( 'fileUploadProgress', progress, estimatedRemainingTime );
		} );

		// If there is an error in uploading, come back to the upload page
		deferred.fail( function () {
			layout.setPage( 'upload' );
		} );

		return deferred;
	};

	/**
	 * Saves the stash finalizes upload. Uses
	 * {@link #getFilename getFilename}, and
	 * {@link #getText getText} to get details from
	 * the form.
	 *
	 * @protected
	 * @fires fileSaved
	 * @return {jQuery.Promise} Rejects the promise with an
	 * {@link OO.ui.Error error}, or resolves if the upload was successful.
	 */
	mw.Upload.BookletLayout.prototype.saveFile = function () {
		var layout = this,
			deferred = $.Deferred();

		this.upload.setFilename( this.getFilename() );
		this.upload.setText( this.getText() );

		this.uploadPromise.then( function () {
			layout.upload.finishStashUpload().then( function () {
				var name;

				// Normalize page name and localise the 'File:' prefix
				name = new mw.Title( 'File:' + layout.upload.getFilename() ).toString();
				layout.filenameUsageWidget.setValue( '[[' + name + ']]' );
				layout.setPage( 'insert' );

				deferred.resolve();
				layout.emit( 'fileSaved', layout.upload.getImageInfo() );
			}, function () {
				var errorMessage = layout.getErrorMessageForStateDetails();
				deferred.reject( errorMessage );
			} );
		} );

		return deferred.promise();
	};

	/**
	 * Get an error message (as OO.ui.Error object) that should be displayed to the user for current
	 * state and state details.
	 *
	 * @protected
	 * @return {OO.ui.Error} Error to display for given state and details.
	 */
	mw.Upload.BookletLayout.prototype.getErrorMessageForStateDetails = function () {
		var message,
			state = this.upload.getState(),
			stateDetails = this.upload.getStateDetails(),
			error = stateDetails.error,
			warnings = stateDetails.upload && stateDetails.upload.warnings;

		if ( state === mw.Upload.State.ERROR ) {
			if ( !error ) {
				// If there's an 'exception' key, this might be a timeout, or other connection problem
				return new OO.ui.Error(
					$( '<p>' ).msg( 'api-error-unknownerror', JSON.stringify( stateDetails ) ),
					{ recoverable: false }
				);
			}

			// HACK We should either have a hook here to allow TitleBlacklist to handle this, or just have
			// TitleBlacklist produce sane error messages that can be displayed without arcane knowledge
			if ( error.info === 'TitleBlacklist prevents this title from being created' ) {
				// HACK Apparently the only reliable way to determine whether TitleBlacklist was involved
				return new OO.ui.Error(
					// HACK TitleBlacklist doesn't have a sensible message, this one is from UploadWizard
					$( '<p>' ).msg( 'api-error-blacklisted' ),
					{ recoverable: false }
				);
			}

			if ( error.code === 'protectedpage' ) {
				message = mw.message( 'protectedpagetext' );
			} else {
				message = mw.message( 'api-error-' + error.code );
				if ( !message.exists() ) {
					message = mw.message( 'api-error-unknownerror', JSON.stringify( stateDetails ) );
				}
			}
			return new OO.ui.Error(
				$( '<p>' ).append( message.parseDom() ),
				{ recoverable: false }
			);
		}

		if ( state === mw.Upload.State.WARNING ) {
			// We could get more than one of these errors, these are in order
			// of importance. For example fixing the thumbnail like file name
			// won't help the fact that the file already exists.
			if ( warnings.stashfailed !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'api-error-stashfailed' ),
					{ recoverable: false }
				);
			} else if ( warnings.exists !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'fileexists', 'File:' + warnings.exists ),
					{ recoverable: false }
				);
			} else if ( warnings[ 'exists-normalized' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'fileexists', 'File:' + warnings[ 'exists-normalized' ] ),
					{ recoverable: false }
				);
			} else if ( warnings[ 'page-exists' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'filepageexists', 'File:' + warnings[ 'page-exists' ] ),
					{ recoverable: false }
				);
			} else if ( warnings.duplicate !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'api-error-duplicate', warnings.duplicate.length ),
					{ recoverable: false }
				);
			} else if ( warnings[ 'thumb-name' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'filename-thumb-name' ),
					{ recoverable: false }
				);
			} else if ( warnings[ 'bad-prefix' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'filename-bad-prefix', warnings[ 'bad-prefix' ] ),
					{ recoverable: false }
				);
			} else if ( warnings[ 'duplicate-archive' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'api-error-duplicate-archive', 1 ),
					{ recoverable: false }
				);
			} else if ( warnings[ 'was-deleted' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).msg( 'api-error-was-deleted' ),
					{ recoverable: false }
				);
			} else if ( warnings.badfilename !== undefined ) {
				// Change the name if the current name isn't acceptable
				// TODO This might not really be the best place to do this
				this.setFilename( warnings.badfilename );
				return new OO.ui.Error(
					$( '<p>' ).msg( 'badfilename', warnings.badfilename )
				);
			} else {
				return new OO.ui.Error(
					// Let's get all the help we can if we can't pin point the error
					$( '<p>' ).msg( 'api-error-unknown-warning', JSON.stringify( stateDetails ) ),
					{ recoverable: false }
				);
			}
		}
	};

	/* Form renderers */

	/**
	 * Renders and returns the upload form and sets the
	 * {@link #uploadForm uploadForm} property.
	 *
	 * @protected
	 * @fires selectFile
	 * @return {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderUploadForm = function () {
		var fieldset,
			layout = this;

		this.selectFileWidget = this.getFileWidget();
		fieldset = new OO.ui.FieldsetLayout();
		fieldset.addItems( [ this.selectFileWidget ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation (if the SFW is for a stashed file, this never fires)
		this.selectFileWidget.on( 'change', this.onUploadFormChange.bind( this ) );

		this.selectFileWidget.on( 'change', function () {
			layout.updateFilePreview();
		} );

		return this.uploadForm;
	};

	/**
	 * Gets the widget for displaying or inputting the file to upload.
	 *
	 * @return {OO.ui.SelectFileWidget|mw.widgets.StashedFileWidget}
	 */
	mw.Upload.BookletLayout.prototype.getFileWidget = function () {
		if ( this.filekey ) {
			return new mw.widgets.StashedFileWidget( {
				filekey: this.filekey
			} );
		}

		return new OO.ui.SelectFileWidget( {
			showDropTarget: true
		} );
	};

	/**
	 * Updates the file preview on the info form when a file is added.
	 *
	 * @protected
	 */
	mw.Upload.BookletLayout.prototype.updateFilePreview = function () {
		this.selectFileWidget.loadAndGetImageUrl().done( function ( url ) {
			this.filePreview.$element.find( 'p' ).remove();
			this.filePreview.$element.css( 'background-image', 'url(' + url + ')' );
			this.infoForm.$element.addClass( 'mw-upload-bookletLayout-hasThumbnail' );
		}.bind( this ) ).fail( function () {
			this.filePreview.$element.find( 'p' ).remove();
			if ( this.selectFileWidget.getValue() ) {
				this.filePreview.$element.append(
					$( '<p>' ).text( this.selectFileWidget.getValue().name )
				);
			}
			this.filePreview.$element.css( 'background-image', '' );
			this.infoForm.$element.removeClass( 'mw-upload-bookletLayout-hasThumbnail' );
		}.bind( this ) );
	};

	/**
	 * Handle change events to the upload form
	 *
	 * @protected
	 * @fires uploadValid
	 */
	mw.Upload.BookletLayout.prototype.onUploadFormChange = function () {
		this.emit( 'uploadValid', !!this.selectFileWidget.getValue() );
	};

	/**
	 * Renders and returns the information form for collecting
	 * metadata and sets the {@link #infoForm infoForm}
	 * property.
	 *
	 * @protected
	 * @return {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderInfoForm = function () {
		var fieldset;

		this.filePreview = new OO.ui.Widget( {
			classes: [ 'mw-upload-bookletLayout-filePreview' ]
		} );
		this.progressBarWidget = new OO.ui.ProgressBarWidget( {
			progress: 0
		} );
		this.filePreview.$element.append( this.progressBarWidget.$element );

		this.filenameWidget = new OO.ui.TextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /.+/
		} );
		this.descriptionWidget = new OO.ui.TextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /\S+/,
			multiline: true,
			autosize: true
		} );

		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-form-label-infoform-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameWidget, {
				label: mw.msg( 'upload-form-label-infoform-name' ),
				align: 'top',
				help: mw.msg( 'upload-form-label-infoform-name-tooltip' )
			} ),
			new OO.ui.FieldLayout( this.descriptionWidget, {
				label: mw.msg( 'upload-form-label-infoform-description' ),
				align: 'top',
				help: mw.msg( 'upload-form-label-infoform-description-tooltip' )
			} )
		] );
		this.infoForm = new OO.ui.FormLayout( {
			classes: [ 'mw-upload-bookletLayout-infoForm' ],
			items: [ this.filePreview, fieldset ]
		} );

		this.on( 'fileUploadProgress', function ( progress ) {
			this.progressBarWidget.setProgress( progress * 100 );
		}.bind( this ) );

		this.filenameWidget.on( 'change', this.onInfoFormChange.bind( this ) );
		this.descriptionWidget.on( 'change', this.onInfoFormChange.bind( this ) );

		return this.infoForm;
	};

	/**
	 * Handle change events to the info form
	 *
	 * @protected
	 * @fires infoValid
	 */
	mw.Upload.BookletLayout.prototype.onInfoFormChange = function () {
		var layout = this;
		$.when(
			this.filenameWidget.getValidity(),
			this.descriptionWidget.getValidity()
		).done( function () {
			layout.emit( 'infoValid', true );
		} ).fail( function () {
			layout.emit( 'infoValid', false );
		} );
	};

	/**
	 * Renders and returns the insert form to show file usage and
	 * sets the {@link #insertForm insertForm} property.
	 *
	 * @protected
	 * @return {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderInsertForm = function () {
		var fieldset;

		this.filenameUsageWidget = new OO.ui.TextInputWidget();
		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-form-label-usage-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameUsageWidget, {
				label: mw.msg( 'upload-form-label-usage-filename' ),
				align: 'top'
			} )
		] );
		this.insertForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		return this.insertForm;
	};

	/* Getters */

	/**
	 * Gets the file object from the
	 * {@link #uploadForm upload form}.
	 *
	 * @protected
	 * @return {File|null}
	 */
	mw.Upload.BookletLayout.prototype.getFile = function () {
		return this.selectFileWidget.getValue();
	};

	/**
	 * Gets the file name from the
	 * {@link #infoForm information form}.
	 *
	 * @protected
	 * @return {string}
	 */
	mw.Upload.BookletLayout.prototype.getFilename = function () {
		var filename = this.filenameWidget.getValue();
		if ( this.filenameExtension ) {
			filename += '.' + this.filenameExtension;
		}
		return filename;
	};

	/**
	 * Prefills the {@link #infoForm information form} with the given filename.
	 *
	 * @protected
	 * @param {string} filename
	 */
	mw.Upload.BookletLayout.prototype.setFilename = function ( filename ) {
		var title = mw.Title.newFromFileName( filename );

		if ( title ) {
			this.filenameWidget.setValue( title.getNameText() );
			this.filenameExtension = mw.Title.normalizeExtension( title.getExtension() );
		} else {
			// Seems to happen for files with no extension, which should fail some checks anyway...
			this.filenameWidget.setValue( filename );
			this.filenameExtension = null;
		}
	};

	/**
	 * Gets the page text from the
	 * {@link #infoForm information form}.
	 *
	 * @protected
	 * @return {string}
	 */
	mw.Upload.BookletLayout.prototype.getText = function () {
		return this.descriptionWidget.getValue();
	};

	/* Setters */

	/**
	 * Sets the file object
	 *
	 * @protected
	 * @param {File|null} file File to select
	 */
	mw.Upload.BookletLayout.prototype.setFile = function ( file ) {
		this.selectFileWidget.setValue( file );
	};

	/**
	 * Sets the filekey of a file already stashed on the server
	 * as the target of this upload operation.
	 *
	 * @protected
	 * @param {string} filekey
	 */
	mw.Upload.BookletLayout.prototype.setFilekey = function ( filekey ) {
		this.upload.setFilekey( this.filekey );
		this.selectFileWidget.setValue( filekey );

		this.onUploadFormChange();
	};

	/**
	 * Clear the values of all fields
	 *
	 * @protected
	 */
	mw.Upload.BookletLayout.prototype.clear = function () {
		this.selectFileWidget.setValue( null );
		this.progressBarWidget.setProgress( 0 );
		this.filenameWidget.setValue( null ).setValidityFlag( true );
		this.descriptionWidget.setValue( null ).setValidityFlag( true );
		this.filenameUsageWidget.setValue( null );
	};

}( jQuery, mediaWiki, moment ) );
