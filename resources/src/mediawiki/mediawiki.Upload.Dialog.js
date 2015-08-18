( function ( $, mw ) {

	/**
	 * mw.Upload.Dialog encapsulates the process of uploading a file
	 * to MediaWiki using the {@link mw.Upload mw.Upload} model.
	 * The dialog emits events that can be used to get the stashed
	 * upload and the final file. It can be extended to accept
	 * additional fields from the user for specific scenarios like
	 * for Commons, or campaigns.
	 *
	 * ## Structure
	 *
	 * The {@link OO.ui.ProcessDialog dialog} has three steps:
	 *
	 *  - **Upload**: Has a {@link OO.ui.SelectFileWidget field} to get the file object.
	 *
	 * - **Information**: Has a {@link OO.ui.FormLayout form} to collect metadata. This can be
	 *   extended.
	 *
	 * - **Insert**: Has details on how to use the file that was uploaded.
	 *
	 * Each step has a form associated with it defined in
	 * {@link mw.Upload.Dialog#renderUploadForm renderUploadForm},
	 * {@link mw.Upload.Dialog#renderInfoForm renderInfoForm}, and
	 * {@link mw.Upload.Dialog#renderInsertForm renderInfoForm}. The
	 * {@link mw.Upload.Dialog#getFile getFile},
	 * {@link mw.Upload.Dialog#getFilename getFilename}, and
	 * {@link mw.Upload.Dialog#getText getText} methods are used to get
	 * the information filled in these forms, required to call
	 * {@link mw.Upload mw.Upload}.
	 *
	 * ## Usage
	 *
	 * To use, setup a {@link OO.ui.WindowManager window manager} like for normal
	 * dialogs:
	 *
	 *     var uploadDialog = new mw.Upload.Dialog( { size: 'small' } );
	 *     var windowManager = new OO.ui.WindowManager();
	 *     $( 'body' ).append( windowManager.$element );
	 *     windowManager.addWindows( [ uploadDialog ] );
	 *     windowManager.openWindow( uploadDialog );
	 *
	 * The dialog's closing promise,
	 * {@link mw.Upload.Dialog#event-fileUploaded fileUploaded},
	 * and {@link mw.Upload.Dialog#event-fileSaved fileSaved} events can
	 * be used to get details of the upload.
	 *
	 * ## Extending
	 *
	 * To extend using {@link mw.Upload mw.Upload}, override
	 * {@link mw.Upload.Dialog#renderInfoForm renderInfoForm} to render
	 * the form required for the specific use-case. Update the
	 * {@link mw.Upload.Dialog#getFilename getFilename}, and
	 * {@link mw.Upload.Dialog#getText getText} methods to return data
	 * from your newly created form. If you added new fields you'll also have
	 * to update the {@link #getTeardownProcess} method.
	 *
	 * If you plan to use a different upload model, apart from what is mentioned
	 * above, you'll also have to override the
	 * {@link mw.Upload.Dialog#getUploadObject getUploadObject} method to
	 * return the new model. The {@link mw.Upload.Dialog#saveFile saveFile}, and
	 * the {@link mw.Upload.Dialog#uploadFile uploadFile} methods need to be
	 * overriden to use the new model and data returned from the forms.
	 *
	 * @class mw.Upload.Dialog
	 * @uses mw.Upload
	 * @extends OO.ui.ProcessDialog
	 */
	mw.Upload.Dialog = function ( config ) {
		// Parent constructor
		mw.Upload.Dialog.parent.call( this, config );
	};

	/* Setup */

	OO.inheritClass( mw.Upload.Dialog, OO.ui.ProcessDialog );

	/* Static Properties */

	/**
	 * @inheritdoc
	 * @property title
	 */
	/*jshint -W024*/
	mw.Upload.Dialog.static.title = mw.msg( 'upload-dialog-title' );

	/**
	 * @inheritdoc
	 * @property actions
	 */
	mw.Upload.Dialog.static.actions = [
		{
			flags: 'safe',
			action: 'cancel',
			label: mw.msg( 'upload-dialog-button-cancel' ),
			modes: [ 'upload', 'insert', 'save' ]
		},
		{
			flags: [ 'primary', 'progressive' ],
			label: mw.msg( 'upload-dialog-button-done' ),
			action: 'insert',
			modes: 'insert'
		},
		{
			flags: [ 'primary', 'constructive' ],
			label: mw.msg( 'upload-dialog-button-save' ),
			action: 'save',
			modes: 'save'
		},
		{
			flags: [ 'primary', 'progressive' ],
			label: mw.msg( 'upload-dialog-button-upload' ),
			action: 'upload',
			modes: 'upload'
		}
	];
	/*jshint +W024*/

	/* Properties */

	/**
	 * @property {OO.ui.FormLayout} uploadForm
	 * The form rendered in the first step to get the file object.
	 * Rendered in {@link mw.Upload.Dialog#renderUploadForm renderUploadForm}.
	 */

	/**
	 * @property {OO.ui.FormLayout} infoForm
	 * The form rendered in the second step to get metadata.
	 * Rendered in {@link mw.Upload.Dialog#renderInfoForm renderInfoForm}
	 */

	/**
	 * @property {OO.ui.FormLayout} insertForm
	 * The form rendered in the third step to show usage
	 * Rendered in {@link mw.Upload.Dialog#renderInsertForm renderInsertForm}
	 */

	/* Events */

	/**
	 * A `fileUploaded` event is emitted from the
	 * {@link mw.Upload.Dialog#uploadFile uploadFile} method.
	 *
	 * @event fileUploaded
	 */

	/**
	 * A `fileSaved` event is emitted from the
	 * {@link mw.Upload.Dialog#saveFile saveFile} method.
	 *
	 * @event fileSaved
	 */

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.initialize = function () {
		mw.Upload.Dialog.parent.prototype.initialize.call( this );

		this.renderUploadForm();
		this.renderInfoForm();
		this.renderInsertForm();

		this.uploadFormPanel = new OO.ui.PanelLayout( {
			scrollable: true,
			padded: true,
			content: [ this.uploadForm ]
		} );
		this.infoFormPanel = new OO.ui.PanelLayout( {
			scrollable: true,
			padded: true,
			content: [ this.infoForm ]
		} );
		this.insertFormPanel = new OO.ui.PanelLayout( {
			scrollable: true,
			padded: true,
			content: [ this.insertForm ]
		} );

		this.panels = new OO.ui.StackLayout();
		this.panels.addItems( [
			this.uploadFormPanel,
			this.infoFormPanel,
			this.insertFormPanel
		] );

		this.$body.append( this.panels.$element );
	};

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getBodyHeight = function () {
		return 300;
	};

	/**
	 * Switch between the panels.
	 *
	 * @param {string} panel Panel name: 'upload', 'info', 'insert'
	 */
	mw.Upload.Dialog.prototype.switchPanels = function ( panel ) {
		switch ( panel ) {
		case 'upload':
			this.panels.setItem( this.uploadFormPanel );
			this.actions.setMode( 'upload' );
			break;
		case 'info':
			this.panels.setItem( this.infoFormPanel );
			this.actions.setMode( 'save' );
			break;
		case 'insert':
			this.panels.setItem( this.insertFormPanel );
			this.actions.setMode( 'insert' );
			break;
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getSetupProcess = function ( data ) {
		return mw.Upload.Dialog.parent.prototype.getSetupProcess.call( this, data )
			.next( function () {
				this.upload = this.getUploadObject();
				this.switchPanels( 'upload' );
				this.actions.setAbilities( { upload: false } );
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getActionProcess = function ( action ) {
		var dialog = this;

		if ( action === 'upload' ) {
			return new OO.ui.Process( function () {
				dialog.filenameWidget.setValue( dialog.getFile().name );
				dialog.switchPanels( 'info' );
				dialog.actions.setAbilities( { save: false } );
				return dialog.uploadFile();
			} );
		}
		if ( action === 'save' ) {
			return new OO.ui.Process( dialog.saveFile() );
		}
		if ( action === 'insert' ) {
			return new OO.ui.Process( function () {
				dialog.close( dialog.upload );
			} );
		}
		if ( action === 'cancel' ) {
			return new OO.ui.Process( dialog.close() );
		}

		return mw.Upload.Dialog.parent.prototype.getActionProcess.call( this, action );
	};

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getTeardownProcess = function ( data ) {
		return mw.Upload.Dialog.parent.prototype.getTeardownProcess.call( this, data )
			.next( function () {
				// Clear the values of all fields
				this.selectFileWidget.setValue( null );
				this.filenameWidget.setValue( null ).setValidityFlag( true );
				this.descriptionWidget.setValue( null ).setValidityFlag( true );
				this.filenameUsageWidget.setValue( null );
			}, this );
	};

	/* Uploading */

	/**
	 * Get the upload model object required for this dialog. Can be
	 * extended to different models.
	 *
	 * @return {mw.Upload}
	 */
	mw.Upload.Dialog.prototype.getUploadObject = function () {
		return new mw.Upload();
	};

	/**
	 * Uploads the file that was added in the upload form. Uses
	 * {@link mw.Upload.Dialog#getFile getFile} to get the HTML5
	 * file object.
	 *
	 * @protected
	 * @fires fileUploaded
	 * @return {jQuery.Promise}
	 */
	mw.Upload.Dialog.prototype.uploadFile = function () {
		var dialog = this,
			file = this.getFile();
		this.upload.setFile( file );
		this.uploadPromise = this.upload.uploadToStash();
		this.uploadPromise.then( function () {
			dialog.emit( 'fileUploaded' );
		} );

		return this.uploadPromise;
	};

	/**
	 * Saves the stash finalizes upload. Uses
	 * {@link mw.Upload.Dialog#getFilename getFilename}, and
	 * {@link mw.Upload.Dialog#getText getText} to get details from
	 * the form.
	 *
	 * @protected
	 * @fires fileSaved
	 * @returns {jQuery.Promise} Rejects the promise with an
	 * {@link OO.ui.Error error}, or resolves if the upload was successful.
	 */
	mw.Upload.Dialog.prototype.saveFile = function () {
		var dialog = this,
			promise = $.Deferred();

		this.upload.setFilename( this.getFilename() );
		this.upload.setText( this.getText() );

		this.uploadPromise.always( function () {

			if ( dialog.upload.getState() === mw.Upload.State.ERROR ) {
				promise.reject( new OO.ui.Error( mw.msg( 'upload-dialog-error' )  ) );
				return false;
			}

			if ( dialog.upload.getState() === mw.Upload.State.WARNING ) {
				promise.reject( new OO.ui.Error( mw.msg( 'upload-dialog-error' )  ) );
				return false;
			}

			dialog.upload.finishStashUpload().then( function () {
				var name;

				if ( dialog.upload.getState() === mw.Upload.State.ERROR ) {
					promise.reject( new OO.ui.Error( mw.msg( 'upload-dialog-error' ) ) );
					return false;
				}

				if ( dialog.upload.getState() === mw.Upload.State.WARNING ) {
					promise.reject( new OO.ui.Error( mw.msg( 'upload-dialog-warning' ) ) );
					return false;
				}

				// Normalize page name and localise the 'File:' prefix
				name = new mw.Title( 'File:' + dialog.upload.getFilename() ).toString();
				dialog.filenameUsageWidget.setValue( '[[' + name + ']]' );
				dialog.switchPanels( 'insert' );

				promise.resolve();
				dialog.emit( 'fileSaved' );
			} );
		} );

		return promise.promise();
	};

	/* Form renderers */

	/**
	 * Renders and returns the upload form and sets the
	 * {@link mw.Upload.Dialog#uploadForm uploadForm} property.
	 * Validates the form and
	 * {@link OO.ui.ActionSet#setAbilities sets abilities}
	 * for the dialog accordingly.
	 *
	 * @protected
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.Dialog.prototype.renderUploadForm = function () {
		var fieldset,
			dialog = this;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		fieldset = new OO.ui.FieldsetLayout( { label: mw.msg( 'upload-dialog-label-select-file' ) } );
		fieldset.addItems( [ this.selectFileWidget ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		this.selectFileWidget.on( 'change', function ( value ) {
			dialog.actions.setAbilities( { upload: !!value } );
		} );

		return this.uploadForm;
	};

	/**
	 * Renders and returns the information form for collecting
	 * metadata and sets the {@link mw.Upload.Dialog#infoForm infoForm}
	 * property.
	 * Validates the form and
	 * {@link OO.ui.ActionSet#setAbilities sets abilities}
	 * for the dialog accordingly.
	 *
	 * @protected
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.Dialog.prototype.renderInfoForm = function () {
		var fieldset,
			dialog = this;

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

		// Validation
		function checkValidity() {
			var validityPromises = [
				dialog.filenameWidget.isValid(),
				dialog.descriptionWidget.isValid()
			];

			$.when.apply( $, validityPromises ).done( function () {
				var allValid,
					values = Array.prototype.slice.apply( arguments );
				allValid = values.every( function ( value ) {
					return value;
				} );

				dialog.actions.setAbilities( { save: allValid } );
			} );
		}
		this.filenameWidget.on( 'change', checkValidity );
		this.descriptionWidget.on( 'change', checkValidity );

		return this.infoForm;
	};

	/**
	 * Renders and returns the insert form to show file usage and
	 * sets the {@link mw.Upload.Dialog#insertForm insertForm} property.
	 *
	 * @protected
	 * @returns {OO.ui.FormLayout}
	 */
	mw.Upload.Dialog.prototype.renderInsertForm = function () {
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
	 * {@link mw.Upload.Dialog#uploadForm upload form}.
	 *
	 * @protected
	 * @returns {File|null}
	 */
	mw.Upload.Dialog.prototype.getFile = function () {
		return this.selectFileWidget.getValue();
	};

	/**
	 * Gets the file name from the
	 * {@link mw.Upload.Dialog#infoForm information form}.
	 *
	 * @protected
	 * @returns {string}
	 */
	mw.Upload.Dialog.prototype.getFilename = function () {
		return this.filenameWidget.getValue();
	};

	/**
	 * Gets the page text from the
	 * {@link mw.Upload.Dialog#infoForm information form}.
	 *
	 * @protected
	 * @returns {string}
	 */
	mw.Upload.Dialog.prototype.getText = function () {
		return this.descriptionWidget.getValue();
	};
}( jQuery, mediaWiki ) );
