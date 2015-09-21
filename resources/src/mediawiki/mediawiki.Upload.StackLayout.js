( function ( $, mw ) {

	/**
	 * mw.Upload.StackLayout encapsulates the process of uploading a file
	 * to MediaWiki using the {@link mw.Upload mw.Upload} model.
	 * The stack emits events that can be used to get the stashed
	 * upload and the final file. It can be extended to accept
	 * additional fields from the user for specific scenarios like
	 * for Commons, or campaigns.
	 *
	 * ## Structure
	 *
	 * The {@link OO.ui.LayoutStack stack layout} has three steps:
	 *
	 *  - **Upload**: Has a {@link OO.ui.SelectFileWidget field} to get the file object.
	 *
	 * - **Information**: Has a {@link OO.ui.FormLayout form} to collect metadata. This can be
	 *   extended.
	 *
	 * - **Insert**: Has details on how to use the file that was uploaded.
	 *
	 * Each step has a form associated with it defined in
	 * {@link mw.Upload.StackLayout#renderUploadForm renderUploadForm},
	 * {@link mw.Upload.StackLayout#renderInfoForm renderInfoForm}, and
	 * {@link mw.Upload.StackLayout#renderInsertForm renderInfoForm}. The
	 * {@link mw.Upload.StackLayout#getFile getFile},
	 * {@link mw.Upload.StackLayout#getFilename getFilename}, and
	 * {@link mw.Upload.StackLayout#getText getText} methods are used to get
	 * the information filled in these forms, required to call
	 * {@link mw.Upload mw.Upload}.
	 *
	 * ## Usage
	 *
	 * See the {@link mw.Upload.UploadDialog upload dialog}.
	 *
	 * The {@link mw.Upload.Dialog#event-fileUploaded fileUploaded},
	 * and {@link mw.Upload.Dialog#event-fileSaved fileSaved} events can
	 * be used to get details of the upload.
	 *
	 * ## Extending
	 *
	 * To extend using {@link mw.Upload mw.Upload}, override
	 * {@link mw.Upload.StackLayout#renderInfoForm renderInfoForm} to render
	 * the form required for the specific use-case. Update the
	 * {@link mw.Upload.StackLayout#getFilename getFilename}, and
	 * {@link mw.Upload.StackLayout#getText getText} methods to return data
	 * from your newly created form. If you added new fields you'll also have
	 * to update the {@link #getTeardownProcess} method.
	 *
	 * If you plan to use a different upload model, apart from what is mentioned
	 * above, you'll also have to override the
	 * {@link mw.Upload.StackLayout#createUpload createUpload} method to
	 * return the new model. The {@link mw.Upload.StackLayout#saveFile saveFile}, and
	 * the {@link mw.Upload.StackLayout#uploadFile uploadFile} methods need to be
	 * overriden to use the new model and data returned from the forms.
	 *
	 * @class
	 * @extends OO.ui.StackLayout
	 *
	 * @constructor
	 * @param {Object} config Configuration options
	 */
	mw.Upload.StackLayout = function ( config ) {
		// Parent constructor
		mw.Upload.StackLayout.parent.call( this, config );

		this.renderUploadForm();
		this.renderInfoForm();
		this.renderInsertForm();

		this.panels = {
			upload: new OO.ui.PanelLayout( {
				scrollable: true,
				padded: true,
				content: [ this.uploadForm ]
			} ),
			info: new OO.ui.PanelLayout( {
				scrollable: true,
				padded: true,
				content: [ this.infoForm ]
			} ),
			insert: new OO.ui.PanelLayout( {
				scrollable: true,
				padded: true,
				content: [ this.insertForm ]
			} )
		};

		this.addItems( [
			this.panels.upload,
			this.panels.info,
			this.panels.insert
		] );
	};

	/* Setup */

	OO.inheritClass( mw.Upload.StackLayout, OO.ui.StackLayout );

	/* Events */

	/**
	 * @event uploadValid
	 * @param {boolean} isValid The form is valid
	 */

	/**
	 * @event infoValid
	 * @param {boolean} isValid The form is valid
	 */

	/**
	 * @event panelNameSet
	 * @param panelName Active panel name
	 *
	 * The active panel name has changed
	 */

	/* Properties */

	/**
	 * @property {OO.ui.FormLayout} uploadForm
	 * The form rendered in the first step to get the file object.
	 * Rendered in {@link mw.Upload.StackLayout#renderUploadForm renderUploadForm}.
	 */

	/**
	 * @property {OO.ui.FormLayout} infoForm
	 * The form rendered in the second step to get metadata.
	 * Rendered in {@link mw.Upload.StackLayout#renderInfoForm renderInfoForm}
	 */

	/**
	 * @property {OO.ui.FormLayout} insertForm
	 * The form rendered in the third step to show usage
	 * Rendered in {@link mw.Upload.StackLayout#renderInsertForm renderInsertForm}
	 */

	/* Methods */

	/**
	 * Switch between the panels by name
	 *
	 * @param {string} panelName Panel name: 'upload', 'info', 'insert'
	 */
	mw.Upload.StackLayout.prototype.setPanelName = function ( panelName ) {
		this.setItem( this.panels[ panelName ] );
		this.emit( 'panelNameSet', panelName );
	};

	/**
	 * Initialize for a new upload
	 */
	mw.Upload.StackLayout.prototype.initialize = function () {
		this.clear();
		this.upload = this.createUpload();
		this.setPanelName( 'upload' );
	};

	/**
	 * Create a new upload model
	 *
	 * @return {mw.Upload} Upload model
	 */
	mw.Upload.StackLayout.prototype.createUpload = function () {
		return new mw.Upload();
	};

	/* Uploading */

	/**
	 * Uploads the file that was added in the upload form. Uses
	 * {@link mw.Upload.StackLayout#getFile getFile} to get the HTML5
	 * file object.
	 *
	 * @protected
	 * @fires fileUploaded
	 * @return {jQuery.Promise}
	 */
	mw.Upload.StackLayout.prototype.uploadFile = function () {
		var file = this.getFile();

		this.filenameWidget.setValue( file.name );
		this.setPanelName( 'info' );

		this.upload.setFile( file );
		this.uploadPromise = this.upload.uploadToStash();
		this.uploadPromise.then( this.emit.bind( this, 'fileUploaded' ) );

		return this.uploadPromise;
	};

	/**
	 * Saves the stash finalizes upload. Uses
	 * {@link mw.Upload.StackLayout#getFilename getFilename}, and
	 * {@link mw.Upload.StackLayout#getText getText} to get details from
	 * the form.
	 *
	 * @protected
	 * @fires fileSaved
	 * @returns {jQuery.Promise} Rejects the promise with an
	 * {@link OO.ui.Error error}, or resolves if the upload was successful.
	 */
	mw.Upload.StackLayout.prototype.saveFile = function () {
		var layout = this,
			deferred = $.Deferred();

		this.upload.setFilename( this.getFilename() );
		this.upload.setText( this.getText() );

		this.uploadPromise.always( function () {

			if ( layout.upload.getState() === mw.Upload.State.ERROR ) {
				deferred.reject( new OO.ui.Error( mw.msg( 'upload-dialog-error' )  ) );
				return false;
			}

			if ( layout.upload.getState() === mw.Upload.State.WARNING ) {
				deferred.reject( new OO.ui.Error( mw.msg( 'upload-dialog-error' )  ) );
				return false;
			}

			layout.upload.finishStashUpload().always( function () {
				var name;

				if ( layout.upload.getState() === mw.Upload.State.ERROR ) {
					deferred.reject( new OO.ui.Error( mw.msg( 'upload-dialog-error' ) ) );
					return false;
				}

				if ( layout.upload.getState() === mw.Upload.State.WARNING ) {
					deferred.reject( new OO.ui.Error( mw.msg( 'upload-dialog-warning' ) ) );
					return false;
				}

				// Normalize page name and localise the 'File:' prefix
				name = new mw.Title( 'File:' + layout.upload.getFilename() ).toString();
				layout.filenameUsageWidget.setValue( '[[' + name + ']]' );
				layout.setPanelName( 'insert' );

				deferred.resolve();
				layout.emit( 'fileSaved' );
			} );
		} );

		return deferred.promise();
	};

	/* Form renderers */

	/**
	 * Renders and returns the upload form and sets the
	 * {@link mw.Upload.StackLayout#uploadForm uploadForm} property.
	 *
	 * @protected
	 * @emits selectFile
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.StackLayout.prototype.renderUploadForm = function () {
		var fieldset;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		fieldset = new OO.ui.FieldsetLayout( { label: mw.msg( 'upload-dialog-label-select-file' ) } );
		fieldset.addItems( [ this.selectFileWidget ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		this.selectFileWidget.on( 'change', this.onUploadFormChange.bind( this ) );

		return this.uploadForm;
	};

	/**
	 * Handle change events to the upload form
	 *
	 * @emit uploadValid
	 */
	mw.Upload.StackLayout.prototype.onUploadFormChange = function () {
		this.emit( 'uploadValid', !!this.selectFileWidget.getValue() );
	};

	/**
	 * Renders and returns the information form for collecting
	 * metadata and sets the {@link mw.Upload.StackLayout#infoForm infoForm}
	 * property.
	 *
	 * @protected
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.StackLayout.prototype.renderInfoForm = function () {
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
			label: mw.msg( 'upload-dialog-label-infoform-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameWidget, {
				label: mw.msg( 'upload-dialog-label-infoform-name' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.descriptionWidget, {
				label: mw.msg( 'upload-dialog-label-infoform-description' ),
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
	 * @emit infoValid
	 */
	mw.Upload.StackLayout.prototype.onInfoFormChange = function () {
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
	 * sets the {@link mw.Upload.StackLayout#insertForm insertForm} property.
	 *
	 * @protected
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.StackLayout.prototype.renderInsertForm = function () {
		var fieldset;

		this.filenameUsageWidget = new OO.ui.TextInputWidget();
		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-dialog-label-usage-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameUsageWidget, {
				label: mw.msg( 'upload-dialog-label-usage-filename' ),
				align: 'top'
			} )
		] );
		this.insertForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		return this.insertForm;
	};

	/* Getters */

	/**
	 * Gets the file object from the
	 * {@link mw.Upload.StackLayout#uploadForm upload form}.
	 *
	 * @protected
	 * @returns {File|null}
	 */
	mw.Upload.StackLayout.prototype.getFile = function () {
		return this.selectFileWidget.getValue();
	};

	/**
	 * Gets the file name from the
	 * {@link mw.Upload.StackLayout#infoForm information form}.
	 *
	 * @protected
	 * @returns {string}
	 */
	mw.Upload.StackLayout.prototype.getFilename = function () {
		return this.filenameWidget.getValue();
	};

	/**
	 * Gets the page text from the
	 * {@link mw.Upload.StackLayout#infoForm information form}.
	 *
	 * @protected
	 * @returns {string}
	 */
	mw.Upload.StackLayout.prototype.getText = function () {
		return this.descriptionWidget.getValue();
	};

	/* Setters */

	/**
	 * Clear the values of all fields
	 */
	mw.Upload.StackLayout.prototype.clear = function () {
		this.selectFileWidget.setValue( null );
		this.filenameWidget.setValue( null ).setValidityFlag( true );
		this.descriptionWidget.setValue( null ).setValidityFlag( true );
		this.filenameUsageWidget.setValue( null );
	};

}( jQuery, mediaWiki ) );
