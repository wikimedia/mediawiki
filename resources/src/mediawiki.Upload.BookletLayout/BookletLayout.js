( function () {

	/**
	 * @classdesc Encapsulates the process of uploading a file
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
	 *  - **Upload**: Has a {@link OO.ui.SelectFileInputWidget field} to get the file object.
	 *
	 * - **Information**: Has a {@link OO.ui.FormLayout form} to collect metadata. This can be
	 *   extended.
	 *
	 * - **Insert**: Has details on how to use the file that was uploaded.
	 *
	 * Each step has a form associated with it defined in
	 * {@link mw.Upload.BookletLayout#renderUploadForm renderUploadForm},
	 * {@link mw.Upload.BookletLayout#renderInfoForm renderInfoForm}, and
	 * {@link mw.Upload.BookletLayout#renderInsertForm renderInfoForm}. The
	 * {@link mw.Upload.BookletLayout#getFile getFile},
	 * {@link mw.Upload.BookletLayout#getFilename getFilename}, and
	 * {@link mw.Upload.BookletLayout#getText getText} methods are used to get
	 * the information filled in these forms, required to call
	 * {@link mw.Upload mw.Upload}.
	 *
	 * ## Usage
	 *
	 * See the {@link mw.Upload.Dialog upload dialog}.
	 *
	 * The {@link mw.Upload.BookletLayout.event:fileUploaded fileUploaded},
	 * and {@link mw.Upload.BookletLayout.event:fileSaved fileSaved} events can
	 * be used to get details of the upload.
	 *
	 * ## Extending
	 *
	 * To extend using {@link mw.Upload mw.Upload}, override
	 * {@link mw.Upload.BookletLayout#renderInfoForm renderInfoForm} to render
	 * the form required for the specific use-case. Update the
	 * {@link mw.Upload.BookletLayout#getFilename getFilename}, and
	 * {@link mw.Upload.BookletLayout#getText getText} methods to return data
	 * from your newly created form. If you added new fields you'll also have
	 * to update the {@link mw.Upload.BookletLayout#clear} method.
	 *
	 * If you plan to use a different upload model, apart from what is mentioned
	 * above, you'll also have to override the
	 * {@link mw.Upload.BookletLayout#createUpload createUpload} method to
	 * return the new model. The {@link #saveFile saveFile}, and
	 * the {@link mw.Upload.BookletLayout#uploadFile uploadFile} methods need to be
	 * overridden to use the new model and data returned from the forms.
	 *
	 * @class mw.Upload.BookletLayout
	 * @extends OO.ui.BookletLayout
	 *
	 * @constructor
	 * @description Create an instance of `mw.Upload.BookletLayout`.
	 * @param {Object} config Configuration options; see also the config parameter for the
	 *  {@link mw.Upload.BookletLayout} constructor.
	 * @param {jQuery} [config.$overlay] Overlay to use for widgets in the booklet
	 * @param {string} [config.filekey] Sets the stashed file to finish uploading. Overrides most of the file selection process, and fetches a thumbnail from the server.
	 */
	mw.Upload.BookletLayout = function ( config ) {
		// Parent constructor
		mw.Upload.BookletLayout.super.call( this, config );

		this.$overlay = config.$overlay;

		this.filekey = config.filekey;

		this.renderUploadForm();
		this.renderInfoForm();
		this.renderInsertForm();

		this.addPages( [
			new OO.ui.PageLayout( 'initializing', {
				scrollable: true,
				padded: true,
				content: [ new OO.ui.ProgressBarWidget( { indeterminate: true } ) ]
			} ),
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
	 * Progress events for the uploaded file.
	 *
	 * @event mw.Upload.BookletLayout.fileUploadProgress
	 * @param {number} progress In percentage
	 */

	/**
	 * The file has finished uploading.
	 *
	 * @event mw.Upload.BookletLayout.fileUploaded
	 */

	/**
	 * The file has been saved to the database.
	 *
	 * @event mw.Upload.BookletLayout.fileSaved
	 * @param {Object} imageInfo See {@link mw.Upload#getImageInfo}
	 */

	/**
	 * The upload form has changed.
	 *
	 * @event mw.Upload.BookletLayout.uploadValid
	 * @param {boolean} isValid The form is valid
	 */

	/**
	 * The info form has changed.
	 *
	 * @event mw.Upload.BookletLayout.infoValid
	 * @param {boolean} isValid The form is valid
	 */

	/* Properties */

	/**
	 * The form rendered in the first step to get the file object.
	 * Rendered in {@link mw.Upload.BookletLayout#renderUploadForm renderUploadForm}.
	 *
	 * @name mw.Upload.BookletLayout.prototype.uploadForm
	 * @type {OO.ui.FormLayout}
	 */

	/**
	 * The form rendered in the second step to get metadata.
	 * Rendered in {@link mw.Upload.BookletLayout#renderInfoForm renderInfoForm}.
	 *
	 * @name mw.Upload.BookletLayout.prototype.infoForm
	 * @type {OO.ui.FormLayout}
	 */

	/**
	 * The form rendered in the third step to show usage.
	 * Rendered in {@link mw.Upload.BookletLayout#renderInsertForm renderInsertForm}.
	 *
	 * @name mw.Upload.BookletLayout.prototype.insertForm
	 * @type {OO.ui.FormLayout}
	 */

	/* Methods */

	/**
	 * Initialize for a new upload.
	 *
	 * @return {jQuery.Promise} Promise resolved when everything is initialized
	 */
	mw.Upload.BookletLayout.prototype.initialize = function () {
		this.clear();
		this.upload = this.createUpload();

		this.setPage( 'initializing' );

		if ( this.filekey ) {
			this.setFilekey( this.filekey );
		}

		return this.upload.getApi().then(
			// If the user can't upload anything, don't give them the option to.
			( api ) => api.getUserInfo().then(
				( userInfo ) => {
					this.setPage( 'upload' );
					if ( !userInfo.rights.includes( 'upload' ) ) {
						if ( !mw.user.isNamed() ) {
							this.getPage( 'upload' ).$element.msg( 'apierror-mustbeloggedin', mw.msg( 'action-upload' ) );
						} else {
							this.getPage( 'upload' ).$element.msg( 'apierror-permissiondenied', mw.msg( 'action-upload' ) );
						}
					}
					return $.Deferred().resolve();
				},
				// Always resolve, never reject
				() => {
					this.setPage( 'upload' );
					return $.Deferred().resolve();
				}
			),
			( errorMsg ) => {
				this.setPage( 'upload' );
				// eslint-disable-next-line mediawiki/msg-doc
				this.getPage( 'upload' ).$element.msg( errorMsg );
				return $.Deferred().resolve();
			}
		);
	};

	/**
	 * Create a new upload model.
	 *
	 * @protected
	 * @return {mw.Upload} Upload model
	 */
	mw.Upload.BookletLayout.prototype.createUpload = function () {
		return new mw.Upload( {
			parameters: {
				errorformat: 'html',
				errorlang: mw.config.get( 'wgUserLanguage' ),
				errorsuselocal: 1,
				formatversion: 2
			}
		} );
	};

	/* Uploading */

	/**
	 * Uploads the file that was added in the upload form. Uses
	 * {@link mw.Upload.BookletLayout#getFile getFile} to get the HTML5
	 * file object.
	 *
	 * @protected
	 * @fires mw.Upload.BookletLayout.fileUploadProgress
	 * @fires mw.Upload.BookletLayout.fileUploaded
	 * @return {jQuery.Promise}
	 */
	mw.Upload.BookletLayout.prototype.uploadFile = function () {
		const deferred = $.Deferred(),
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
		this.uploadPromise.then( () => {
			deferred.resolve();
			this.emit( 'fileUploaded' );
		}, () => {
			// These errors will be thrown while the user is on the info page.
			this.getErrorMessageForStateDetails().then( ( errorMessage ) => {
				deferred.reject( errorMessage );
			} );
		}, ( progress ) => {
			this.emit( 'fileUploadProgress', progress );
		} );

		// If there is an error in uploading, come back to the upload page
		deferred.fail( () => {
			this.setPage( 'upload' );
		} );

		return deferred;
	};

	/**
	 * Saves the stash finalizes upload. Uses
	 * {@link mw.Upload.BookletLayout#getFilename getFilename}, and
	 * {@link mw.Upload.BookletLayout#getText getText} to get details from
	 * the form.
	 *
	 * @protected
	 * @fires mw.Upload.BookletLayout.fileSaved
	 * @return {jQuery.Promise} Rejects the promise with an
	 * {@link OO.ui.Error error}, or resolves if the upload was successful.
	 */
	mw.Upload.BookletLayout.prototype.saveFile = function () {
		const deferred = $.Deferred();

		this.upload.setFilename( this.getFilename() );
		this.upload.setText( this.getText() );

		this.uploadPromise.then( () => {
			this.upload.finishStashUpload().then( () => {
				// Normalize page name and localise the 'File:' prefix
				const name = new mw.Title( 'File:' + this.upload.getFilename() ).toString();
				this.filenameUsageWidget.setValue( '[[' + name + ']]' );
				this.setPage( 'insert' );

				deferred.resolve();
				this.emit( 'fileSaved', this.upload.getImageInfo() );
			}, () => {
				this.getErrorMessageForStateDetails().then( ( errorMessage ) => {
					deferred.reject( errorMessage );
				} );
			} );
		} );

		return deferred.promise();
	};

	/**
	 * Get an error message (as OO.ui.Error object) that should be displayed to the user for current
	 * state and state details.
	 *
	 * @protected
	 * @return {jQuery.Promise|undefined} A Promise that will be resolved with an OO.ui.Error.
	 */
	mw.Upload.BookletLayout.prototype.getErrorMessageForStateDetails = function () {
		const state = this.upload.getState(),
			stateDetails = this.upload.getStateDetails(),
			warnings = stateDetails.upload && stateDetails.upload.warnings,
			$ul = $( '<ul>' );

		if ( state === mw.Upload.State.ERROR ) {
			const $error = ( new mw.Api() ).getErrorMessage( stateDetails );

			return $.Deferred().resolve( new OO.ui.Error(
				$error,
				{ recoverable: false }
			) );
		}

		if ( state === mw.Upload.State.WARNING ) {
			// We could get more than one of these errors, these are in order
			// of importance. For example fixing the thumbnail like file name
			// won't help the fact that the file already exists.
			if ( warnings.exists !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'fileexists', 'File:' + warnings.exists ),
					{ recoverable: false }
				) );
			} else if ( warnings[ 'exists-normalized' ] !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'fileexists', 'File:' + warnings[ 'exists-normalized' ] ),
					{ recoverable: false }
				) );
			} else if ( warnings[ 'page-exists' ] !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'filepageexists', 'File:' + warnings[ 'page-exists' ] ),
					{ recoverable: false }
				) );
			} else if ( Array.isArray( warnings.duplicate ) ) {
				warnings.duplicate.forEach( ( filename ) => {
					const $a = $( '<a>' ).text( filename ),
						href = mw.Title.makeTitle( mw.config.get( 'wgNamespaceIds' ).file, filename ).getUrl( {} );

					$a.attr( { href: href, target: '_blank' } );
					$ul.append( $( '<li>' ).append( $a ) );
				} );

				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'file-exists-duplicate', warnings.duplicate.length ).append( $ul ),
					{ recoverable: false }
				) );
			} else if ( warnings[ 'thumb-name' ] !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'filename-thumb-name' ),
					{ recoverable: false }
				) );
			} else if ( warnings[ 'bad-prefix' ] !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'filename-bad-prefix', warnings[ 'bad-prefix' ] ),
					{ recoverable: false }
				) );
			} else if ( warnings[ 'duplicate-archive' ] !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'file-deleted-duplicate', 'File:' + warnings[ 'duplicate-archive' ] ),
					{ recoverable: false }
				) );
			} else if ( warnings[ 'was-deleted' ] !== undefined ) {
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'filewasdeleted', 'File:' + warnings[ 'was-deleted' ] ),
					{ recoverable: false }
				) );
			} else if ( warnings.badfilename !== undefined ) {
				// Change the name if the current name isn't acceptable
				// TODO This might not really be the best place to do this
				this.setFilename( warnings.badfilename );
				return $.Deferred().resolve( new OO.ui.Error(
					$( '<p>' ).msg( 'badfilename', warnings.badfilename )
				) );
			} else {
				return $.Deferred().resolve( new OO.ui.Error(
					// Let's get all the help we can if we can't pin point the error
					$( '<p>' ).msg( 'api-error-unknown-warning', JSON.stringify( stateDetails ) ),
					{ recoverable: false }
				) );
			}
		}
	};

	/* Form renderers */

	/**
	 * Renders and returns the upload form and sets the
	 * {@link mw.Upload.BookletLayout#uploadForm uploadForm} property.
	 *
	 * @protected
	 * @return {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderUploadForm = function () {
		this.selectFileWidget = this.getFileWidget();
		const fieldset = new OO.ui.FieldsetLayout();
		fieldset.addItems( [ this.selectFileWidget ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation (if the SFW is for a stashed file, this never fires)
		this.selectFileWidget.on( 'change', this.onUploadFormChange.bind( this ) );

		this.selectFileWidget.on( 'change', () => {
			this.updateFilePreview();
		} );

		return this.uploadForm;
	};

	/**
	 * Gets the widget for displaying or inputting the file to upload.
	 *
	 * @return {OO.ui.SelectFileInputWidget|mw.widgets.StashedFileWidget}
	 */
	mw.Upload.BookletLayout.prototype.getFileWidget = function () {
		if ( this.filekey ) {
			return new mw.widgets.StashedFileWidget( {
				filekey: this.filekey
			} );
		}

		return new OO.ui.SelectFileInputWidget( {
			showDropTarget: true
		} );
	};

	/**
	 * Updates the file preview on the info form when a file is added.
	 *
	 * @protected
	 */
	mw.Upload.BookletLayout.prototype.updateFilePreview = function () {
		this.selectFileWidget.loadAndGetImageUrl().done( ( url ) => {
			this.filePreview.$element.find( 'p' ).remove();
			this.filePreview.$element.css( 'background-image', 'url(' + url + ')' );
			this.infoForm.$element.addClass( 'mw-upload-bookletLayout-hasThumbnail' );
		} ).fail( () => {
			this.filePreview.$element.find( 'p' ).remove();
			if ( this.selectFileWidget.getValue() ) {
				this.filePreview.$element.append(
					$( '<p>' ).text( this.selectFileWidget.getValue().name )
				);
			}
			this.filePreview.$element.css( 'background-image', '' );
			this.infoForm.$element.removeClass( 'mw-upload-bookletLayout-hasThumbnail' );
		} );
	};

	/**
	 * Handle change events to the upload form.
	 *
	 * @protected
	 * @fires mw.Upload.BookletLayout.uploadValid
	 */
	mw.Upload.BookletLayout.prototype.onUploadFormChange = function () {
		this.emit( 'uploadValid', !!this.selectFileWidget.getValue() );
	};

	/**
	 * Renders and returns the information form for collecting
	 * metadata and sets the {@link mw.Upload.BookletLayout#infoForm infoForm}
	 * property.
	 *
	 * @protected
	 * @return {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderInfoForm = function () {
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
		this.descriptionWidget = new OO.ui.MultilineTextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /\S+/,
			autosize: true
		} );

		const fieldset = new OO.ui.FieldsetLayout( {
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

		this.on( 'fileUploadProgress', ( progress ) => {
			this.progressBarWidget.setProgress( progress * 100 );
		} );

		this.filenameWidget.on( 'change', this.onInfoFormChange.bind( this ) );
		this.descriptionWidget.on( 'change', this.onInfoFormChange.bind( this ) );

		return this.infoForm;
	};

	/**
	 * Handle change events to the info form.
	 *
	 * @protected
	 * @fires mw.Upload.BookletLayout.infoValid
	 */
	mw.Upload.BookletLayout.prototype.onInfoFormChange = function () {
		$.when(
			this.filenameWidget.getValidity(),
			this.descriptionWidget.getValidity()
		).done( () => {
			this.emit( 'infoValid', true );
		} ).fail( () => {
			this.emit( 'infoValid', false );
		} );
	};

	/**
	 * Renders and returns the insert form to show file usage and
	 * sets the {@link mw.Upload.BookletLayout#insertForm insertForm} property.
	 *
	 * @protected
	 * @return {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderInsertForm = function () {
		this.filenameUsageWidget = new OO.ui.TextInputWidget();
		const fieldset = new OO.ui.FieldsetLayout( {
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
	 * {@link mw.Upload.BookletLayout#uploadForm upload form}.
	 *
	 * @protected
	 * @return {File|null}
	 */
	mw.Upload.BookletLayout.prototype.getFile = function () {
		return this.selectFileWidget.getValue();
	};

	/**
	 * Gets the file name from the
	 * {@link mw.Upload.BookletLayout#infoForm information form}.
	 *
	 * @protected
	 * @return {string}
	 */
	mw.Upload.BookletLayout.prototype.getFilename = function () {
		let filename = this.filenameWidget.getValue();
		if ( this.filenameExtension ) {
			filename += '.' + this.filenameExtension;
		}
		return filename;
	};

	/**
	 * Prefills the {@link mw.Upload.BookletLayout#infoForm information form} with the given filename.
	 *
	 * @protected
	 * @param {string} filename
	 */
	mw.Upload.BookletLayout.prototype.setFilename = function ( filename ) {
		const title = mw.Title.newFromFileName( filename );

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
	 * {@link mw.Upload.BookletLayout#infoForm information form}.
	 *
	 * @protected
	 * @return {string}
	 */
	mw.Upload.BookletLayout.prototype.getText = function () {
		return this.descriptionWidget.getValue();
	};

	/* Setters */

	/**
	 * Sets the file object.
	 *
	 * @protected
	 * @param {File|null} file File to select
	 */
	mw.Upload.BookletLayout.prototype.setFile = function ( file ) {
		this.selectFileWidget.setValue( [ file ] );
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
	 * Clear the values of all fields.
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

}() );
