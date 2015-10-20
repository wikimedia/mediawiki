( function ( $, mw ) {

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
	 * overriden to use the new model and data returned from the forms.
	 *
	 * @class
	 * @extends OO.ui.BookletLayout
	 *
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {jQuery} [$overlay] Overlay to use for widgets in the booklet
	 */
	mw.Upload.BookletLayout = function ( config ) {
		// Parent constructor
		mw.Upload.BookletLayout.parent.call( this, config );

		this.$overlay = config.$overlay;

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
	 */
	mw.Upload.BookletLayout.prototype.initialize = function () {
		this.clear();
		this.upload = this.createUpload();
		this.setPage( 'upload' );
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
	 * @fires fileUploaded
	 * @return {jQuery.Promise}
	 */
	mw.Upload.BookletLayout.prototype.uploadFile = function () {
		var deferred = $.Deferred(),
			layout = this,
			file = this.getFile();

		this.filenameWidget.setValue( file.name );
		this.setPage( 'info' );

		this.upload.setFile( file );
		// Explicitly set the filename so that the old filename isn't used in case of retry
		this.upload.setFilenameFromFile();

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
	 * @returns {jQuery.Promise} Rejects the promise with an
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
	 * @returns {OO.ui.Error} Error to display for given state and details.
	 */
	mw.Upload.BookletLayout.prototype.getErrorMessageForStateDetails = function () {
		var message,
			state = this.upload.getState(),
			stateDetails = this.upload.getStateDetails(),
			error = stateDetails.error,
			warnings = stateDetails.upload && stateDetails.upload.warnings;

		if ( state === mw.Upload.State.ERROR ) {
			// HACK We should either have a hook here to allow TitleBlacklist to handle this, or just have
			// TitleBlacklist produce sane error messages that can be displayed without arcane knowledge
			if ( error.info === 'TitleBlacklist prevents this title from being created' ) {
				// HACK Apparently the only reliable way to determine whether TitleBlacklist was involved
				return new OO.ui.Error(
					$( '<p>' ).html(
						// HACK TitleBlacklist doesn't have a sensible message, this one is from UploadWizard
						mw.message( 'api-error-blacklisted' ).parse()
					),
					{ recoverable: false }
				);
			}

			message = mw.message( 'api-error-' + error.code );
			if ( !message.exists() ) {
				message = mw.message( 'api-error-unknownerror', JSON.stringify( stateDetails ) );
			}
			return new OO.ui.Error(
				$( '<p>' ).html(
					message.parse()
				),
				{ recoverable: false }
			);
		}

		if ( state === mw.Upload.State.WARNING ) {
			// We could get more than one of these errors, these are in order
			// of importance. For example fixing the thumbnail like file name
			// won't help the fact that the file already exists.
			if ( warnings.stashfailed !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'api-error-stashfailed' ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings.exists !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'fileexists', 'File:' + warnings.exists ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings[ 'page-exists' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'filepageexists', 'File:' + warnings[ 'page-exists' ] ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings.duplicate !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'api-error-duplicate', warnings.duplicate.length ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings[ 'thumb-name' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'filename-thumb-name' ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings[ 'bad-prefix' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'filename-bad-prefix', warnings[ 'bad-prefix' ] ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings[ 'duplicate-archive' ] !== undefined ) {
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'api-error-duplicate-archive', 1 ).parse()
					),
					{ recoverable: false }
				);
			} else if ( warnings.badfilename !== undefined ) {
				// Change the name if the current name isn't acceptable
				// TODO This might not really be the best place to do this
				this.filenameWidget.setValue( warnings.badfilename );
				return new OO.ui.Error(
					$( '<p>' ).html(
						mw.message( 'badfilename', warnings.badfilename ).parse()
					)
				);
			} else {
				return new OO.ui.Error(
					$( '<p>' ).html(
						// Let's get all the help we can if we can't pin point the error
						mw.message( 'api-error-unknown-warning', JSON.stringify( stateDetails ) ).parse()
					),
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
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderUploadForm = function () {
		var fieldset;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		fieldset = new OO.ui.FieldsetLayout( { label: mw.msg( 'upload-form-label-select-file' ) } );
		fieldset.addItems( [ this.selectFileWidget ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		this.selectFileWidget.on( 'change', this.onUploadFormChange.bind( this ) );

		return this.uploadForm;
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
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.BookletLayout.prototype.renderInfoForm = function () {
		var fieldset;

		this.filenameWidget = new OO.ui.TextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /.+/
		} );
		this.descriptionWidget = new OO.ui.TextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /.+/,
			multiline: true,
			autosize: true
		} );

		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-form-label-infoform-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameWidget, {
				label: mw.msg( 'upload-form-label-infoform-name' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.descriptionWidget, {
				label: mw.msg( 'upload-form-label-infoform-description' ),
				align: 'top'
			} )
		] );
		this.infoForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

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
	 * @returns {OO.ui.FormLayout}
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
	 * @returns {File|null}
	 */
	mw.Upload.BookletLayout.prototype.getFile = function () {
		return this.selectFileWidget.getValue();
	};

	/**
	 * Gets the file name from the
	 * {@link #infoForm information form}.
	 *
	 * @protected
	 * @returns {string}
	 */
	mw.Upload.BookletLayout.prototype.getFilename = function () {
		return this.filenameWidget.getValue();
	};

	/**
	 * Gets the page text from the
	 * {@link #infoForm information form}.
	 *
	 * @protected
	 * @returns {string}
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
	 * Clear the values of all fields
	 *
	 * @protected
	 */
	mw.Upload.BookletLayout.prototype.clear = function () {
		this.selectFileWidget.setValue( null );
		this.filenameWidget.setValue( null ).setValidityFlag( true );
		this.descriptionWidget.setValue( null ).setValidityFlag( true );
		this.filenameUsageWidget.setValue( null );
	};

}( jQuery, mediaWiki ) );
