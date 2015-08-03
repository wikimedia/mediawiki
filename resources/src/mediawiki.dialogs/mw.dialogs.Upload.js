/*!
* MediaWiki Dialogs - UploadDialog class.
*/
( function ( $, mw ) {

	/**
	 * UploadDialog encapsulates the process of uploading a file
	 * to MediaWiki using the {@link mw.Upload mw.Upload} model.
	 * The dialog emits events that can be used to get the stashed
	 * upload and the final file. It can be extended to accept
	 * additional fields from the user for specific scenarios like
	 * for Commons, or campaigns.
	 *
	 * ## Structure
	 *
	 * The {@link OO.ui.ProcessDialog dialog} has three steps-
	 *
	 *  - **Upload**: Has a {@link OO.ui.SelectFileWidget field} to get the
	 * file object.
	 *
	 * - **Information**: Has a {@link OO.ui.FormLayout form} to
         *  collect metadata. This can be extended.
	 *
	 * - **Insert**: Has details on how to use the file that was uploaded.
	 *
	 * Each step has a form associated with it defined in
	 * {@link mw.dialogs.Upload#renderUploadForm renderUploadForm},
	 * {@link mw.dialogs.Upload#renderInfoForm renderInfoForm}, and
	 * {@link mw.dialogs.Upload#renderInsertForm renderInfoForm}. The
	 * {@link mw.dialogs.Upload#getFile getFile},
	 * {@link mw.dialogs.Upload#getFilename getFilename}, and
	 * {@link mw.dialogs.Upload#getText getText} methods are used to get
	 * the information filled in these forms, required to call
	 * {@link mw.Upload mw.Upload}.
	 *
	 * ## Usage
	 *
	 * To use, setup a {@link OO.ui.WindowManager window manager} like for normal
	 * dialogs-
	 *
	 *     var uploadDialog = new mw.dialogs.Upload( { size: 'small' } );
	 *     var windowManager = new OO.ui.WindowManager();
	 *     $( 'body' ).append( windowManager.$element );
	 *     windowManager.addWindows( [ uploadDialog ] );
	 *     windowManager.openWindow( uploadDialog );
	 *
	 * The dialog's closing promise,
	 * {@link mw.dialogs.Upload#event-fileUploaded fileUploaded},
	 * and {@link mw.dialogs.Upload#event-fileSaved fileSaved} events can
	 * be used to get details of the upload
	 *
	 * ## Extending
	 *
	 * To extend using {@link mw.Upload mw.Upload}, override
	 * {@link mw.dialogs.Upload#renderInfoForm renderInfoForm} to render
	 * the form required for the specific use-case. Update the
	 * {@link mw.dialogs.Upload#getFilename getFilename}, and
	 * {@link mw.dialogs.Upload#getText getText} methods to return data
	 * from your newly created form.
	 *
	 * If you plan to use a different upload model, apart from what is mentioned
	 * above, you'll also have to override the
	 * {@link mw.dialogs.Upload#getUploadObject getUploadObject} method to
	 * return the new model. The {@link mw.dialogs.Upload#saveFile saveFile}, and
	 * the {@link mw.dialogs.Upload#uploadFile uploadFile} methods need to be
	 * overriden to use the new model and data returned from the forms.
	 *
	 * @class
	 * @uses mw.Upload
	 * @extends OO.ui.ProcessDialog
	 */
	mw.dialogs.Upload = function ( config ) {
		// Parent constructor
		mw.dialogs.Upload.parent.call( this, config );
	};

	/* Setup */

	OO.inheritClass( mw.dialogs.Upload, OO.ui.ProcessDialog );

	/* Static Properties */

	/**
	 * @inheritdoc
	 * @property title
	 */
	/*jshint -W024*/
	mw.dialogs.Upload.static.title = 'Upload file';

	/**
	 * @inheritdoc
	 * @property actions
	 */
	mw.dialogs.Upload.static.actions = [
		{ flags: 'safe', action: 'cancel', label: 'Cancel', modes: [ 'upload', 'insert', 'save' ] },
		{ flags: [ 'primary', 'progressive' ], label: 'Done', action: 'insert', modes: 'insert' },
		{ flags: [ 'primary', 'progressive' ], label: 'Save', action: 'save', modes: 'save' },
		{ flags: [ 'primary', 'progressive' ], label: 'Upload', action: 'upload', modes: 'upload' }
	];
	/*jshint +W024*/

	/* Properties */

	/**
	 * @property {OO.ui.FormLayout} uploadForm
	 * The form rendered in the first step to get the file object.
	 * Rendered in {@link mw.dialogs.Upload#renderUploadForm renderUploadForm}.
	 */
	mw.dialogs.Upload.uploadForm = null;

	/**
	 * @property {OO.ui.FormLayout} infoForm
	 * The form rendered in the second step to get metadata.
	 * Rendered in {@link mw.dialogs.Upload#renderInfoForm renderInfoForm}
	 */
	mw.dialogs.Upload.infoForm = null;

	/**
	 * @property {OO.ui.FormLayout} insertForm
	 * The form rendered in the third step to show usage
	 * Rendered in {@link mw.dialogs.Upload#renderInsertForm renderInsertForm}
	 */
	mw.dialogs.Upload.insertForm = null;

	/* Events */

	/**
	 * A `fileUploaded` event is emitted from the
	 * {@link mw.dialogs.Upload#uploadFile uploadFile} method.
	 *
	 * @event fileUploaded
	 */

	/**
	 * A `fileSaved` event is emitted from the
	 * {@link mw.dialogs.Upload#saveFile saveFile} method.
	 *
	 * @event fileSaved
	 */

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.dialogs.Upload.prototype.initialize = function () {
		mw.dialogs.Upload.parent.prototype.initialize.call( this );

		this.upload = this.getUploadObject();
		this.content = new OO.ui.PanelLayout( {
			padded: true,
			expanded: false,
			content: [ this.renderUploadForm() ]
		} );
		this.$body.append( this.content.$element );
	};

	/**
	 * @inheritdoc
	 */
	mw.dialogs.Upload.prototype.getBodyHeight = function () {
		return 300;
	};

	/**
	 * @inheritdoc
	 */
	mw.dialogs.Upload.prototype.getSetupProcess = function ( data ) {
		return mw.dialogs.Upload.parent.prototype.getSetupProcess.call( this, data )
			.next( function () {
				this.actions.setMode( 'upload' );
				this.actions.setAbilities( { upload: false } );
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.dialogs.Upload.prototype.getActionProcess = function ( action ) {
		var dialog = this;

		if ( action === 'upload' ) {
			return new OO.ui.Process( function () {
				dialog.uploadFile();
				dialog.content.$element // TODO: Is there a better way to do this?
					.empty()
					.append( dialog.renderInfoForm().$element );
				dialog.actions.setMode( 'save' );
				dialog.actions.setAbilities( { save: false } );
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

		return mw.dialogs.Upload.parent.prototype.getActionProcess.call( this, action );
	};

	/* Uploading */

	/**
	 * Get the upload model object required for this dialog. Can be
	 * extended to different models.
	 *
	 * @return {mw.Upload}
	 */
	mw.dialogs.Upload.prototype.getUploadObject = function () {
		return new mw.Upload();
	};

	/**
	 * Uploads the file that was added in the upload form. Uses
	 * {@link mw.dialogs.Upload#getFile getFile} to get the HTML5
	 * file object.
	 *
	 * @fires fileUploaded
	 */
	mw.dialogs.Upload.prototype.uploadFile = function () {
		var dialog = this,
			file = this.getFile();
		this.upload.setFile( file );
		this.uploadPromise = this.upload.uploadToStash();
		this.uploadPromise.then( function () {
			dialog.emit( 'fileUploaded' );
		} );
	};

	/**
	 * Saves the stash finalizes upload. Uses
	 * {@link mw.dialogs.Upload#getFilename getFilename}, and
	 * {@link mw.dialogs.Upload#getText getText} to get details from
	 * the form.
	 *
	 * @fires fileSaved
	 * @returns {jQuery.Promise} Rejects the promise with an
	 * {@link OO.ui.Error error}, or resolves if the upload was successful.
	 */
	mw.dialogs.Upload.prototype.saveFile = function () {
		var dialog = this,
			promise = $.Deferred();

		this.upload.setFilename( this.getFilename() );
		this.upload.setText( this.getText() );

		this.uploadPromise.always( function () {

			if ( dialog.upload.getState() === mw.Upload.State.ERROR ) {
				promise.reject( new OO.ui.Error( 'An error occurred'  ) );
				return false;
			}

			if ( dialog.upload.getState() === mw.Upload.State.WARNING ) {
				promise.reject( new OO.ui.Error( 'A warning occurred'  ) );
				return false;
			}

			dialog.upload.finishStashUpload().then( function () {
				if ( dialog.upload.getState() === mw.Upload.State.ERROR ) {
					promise.reject( new OO.ui.Error( 'An error occurred'  ) );
					return false;
				}

				if ( dialog.upload.getState() === mw.Upload.State.WARNING ) {
					promise.reject( new OO.ui.Error( 'A warning occurred'  ) );
					return false;
				}

				dialog.content.$element
					.empty()
					.append( dialog.renderInsertForm().$element );
				dialog.actions.setMode( 'insert' );

				promise.resolve();
				dialog.emit( 'fileSaved' );
			} );
		} );

		return promise.promise();
	};

	/* Form renderers */

	/**
	 * Renders and returns the upload form and sets the
	 * {@link mw.dialogs.Upload#uploadForm uploadForm} property.
	 * Validates the form and
	 * {@link OO.ui.ActionSet#setAbilities sets abilities}
	 * for the dialog accordingly.
	 *
	 * @returns {OO.ui.FormLayout}
	 */
	mw.dialogs.Upload.prototype.renderUploadForm = function () {
		var dialog = this,
			file, fieldset;

		file = new OO.ui.SelectFileWidget();
		file.on( 'change', function ( value ) {
			dialog.actions.setAbilities( { upload: !!value } );
		} );

		fieldset = new OO.ui.FieldsetLayout( { label: 'Select file' } );
		fieldset.addItems( [ file ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		return this.uploadForm;
	};

	/**
	 * Renders and returns the information form for collecting
	 * metadata and sets the {@link mw.dialogs.Upload#infoForm infoForm}
	 * property.
	 * Validates the form and
	 * {@link OO.ui.ActionSet#setAbilities sets abilities}
	 * for the dialog accordingly.
	 *
	 * @returns {OO.ui.FormLayout}
	 */
	mw.dialogs.Upload.prototype.renderInfoForm = function () {
		var name, description, fieldset,
			dialog = this,
			filename = this.getFile().name;

		name = new OO.ui.TextInputWidget( {
			value: filename,
			indicator: 'required',
			required: true,
			validate: /.+/
		} );
		description = new OO.ui.TextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /.+/,
			multiline: true,
			autosize: true
		} );

		fieldset = new OO.ui.FieldsetLayout( { label: 'Details' } );
		fieldset.addItems( [
			new OO.ui.FieldLayout( name, {
				label: 'Name',
				align: 'top'
			} ),
			new OO.ui.FieldLayout( description, {
				label: 'Description',
				align: 'top'
			} )
		] );
		this.infoForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		function checkValidity() {
			var validityPromises = [
				name.isValid(),
				description.isValid()
			];

			$.when.apply( $, validityPromises ).done( function () {
				var allValid,
					values = Array.prototype.slice.apply( arguments );
				allValid = values.every( function ( value ) {
					return value;
				} );

				dialog.actions.setAbilities( {
					save: allValid
				} );
			} );
		}
		name.on( 'change', checkValidity );
		description.on( 'change', checkValidity );

		return this.infoForm;
	};

	/**
	 * Renders and returns the insert form to show file usage and
	 * sets the {@link mw.dialogs.Upload#insertForm insertForm} property.
	 *
	 * @returns {OO.ui.FormLayout}
	 */
	mw.dialogs.Upload.prototype.renderInsertForm = function () {
		var filename, fieldset,
			name = this.upload.getFilename();

		filename = new OO.ui.TextInputWidget( {
			value: '[[File:' + name + ']]'
		} );
		fieldset = new OO.ui.FieldsetLayout( { label: 'Usage' } );
		fieldset.addItems( [
			new OO.ui.FieldLayout( filename, {
				label: 'File name',
				align: 'top'
			} )
		] );
		this.insertForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		return this.insertForm;
	};

	/* Getters */

	/**
	 * Gets the file object from the
	 * {@link mw.dialogs.Upload#uploadForm upload form}.
	 *
	 * @returns {File|null}
	 */
	mw.dialogs.Upload.prototype.getFile = function () {
		return this.uploadForm // FormLayout
			.getItems()[0] // Fieldset (the only one)
			.getItems()[0] // SelectFileWidget (the only one)
			.getValue();

	};

	/**
	 * Gets the file name from the
	 * {@link mw.dialogs.Upload#infoForm information form}.
	 *
	 * @returns {string}
	 */
	mw.dialogs.Upload.prototype.getFilename = function () {
		return this.infoForm // FormLayout
			.getItems()[0] // FieldsetLayout (the only one)
			.getItems()[0] // FieldLayout (the first one has the name)
			.getField() // TextInputWidget
			.getValue();
	};

	/**
	 * Gets the page text from the
	 * {@link mw.dialogs.Upload#infoForm information form}.
	 *
	 * @returns {string}
	 */
	mw.dialogs.Upload.prototype.getText = function () {
		return this.infoForm // FormLayout
			.getItems()[0] // FieldsetLayout (the only one)
			.getItems()[1] // FieldLayout (the second one has the description)
			.getField() // TextInputWidget
			.getValue();
	};
}( jQuery, mediaWiki ) );
