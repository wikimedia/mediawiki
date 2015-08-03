/*!
* MediaWiki Dialogs - UploadDialog class.
*/
( function ( $, mw ) {

	/**
	 * Uploader interface for a single file in a ProcessDialog
	 *
	 * @class
	 * @extends OO.ui.ProcessDialog
	 */
	mw.dialogs.Upload = function ( config ) {
		// Parent constructor
		mw.dialogs.Upload.parent.call( this, config );
	};

	/* Setup */

	OO.inheritClass( mw.dialogs.Upload, OO.ui.ProcessDialog );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	/*jshint -W024*/
	mw.dialogs.Upload.static.title = 'Upload file';

	/**
	 * @inheritdoc
	 */
	mw.dialogs.Upload.static.actions = [
		{ flags: 'safe', action: 'cancel', label: 'Cancel', modes: [ 'upload', 'insert', 'save' ] },
		{ flags: [ 'primary', 'progressive' ], label: 'Done', action: 'insert', modes: 'insert' },
		{ flags: [ 'primary', 'progressive' ], label: 'Save', action: 'save', modes: 'save' },
		{ flags: [ 'primary', 'progressive' ], label: 'Upload', action: 'upload', modes: 'upload' }
	];
	/*jshint +W024*/

	/**
	 * @initialize
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
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.dialogs.Upload.prototype.getActionProcess = function ( action ) {
		var dialog = this;

		if ( action === 'insert' ) {
			return new OO.ui.Process( function () {
				dialog.close( { } ); // TODO: Return something useful
			} );
		}
		if ( action === 'upload' ) {
			return new OO.ui.Process( function () {
				dialog.uploadFile();
				dialog.content.$element // TODO: Is there a better way to do this?
					.empty()
					.append( dialog.renderInfoForm().$element );
				dialog.actions.setMode( 'save' );
			} );
		}
		if ( action === 'save' ) {
			return new OO.ui.Process( dialog.saveFile() );
		}
		if ( action === 'cancel' ) {
			return new OO.ui.Process( dialog.close() );
		}

		return mw.dialogs.Upload.parent.prototype.getActionProcess.call( this, action );
	};

	/**
	 * Get the upload model object required for this dialog
	 *
	 * @method
	 */
	mw.dialogs.Upload.prototype.getUploadObject = function () {
		return new mw.Upload();
	};

	/**
	 * Uploads the file that was added to uploadForm.file
	 *
	 * @method
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
	 * Saves the stash finalizes upload
	 *
	 * @method
	 */
	mw.dialogs.Upload.prototype.saveFile = function () {
		var dialog = this,
			promise = $.Deferred();

		this.upload.setFilename( this.getFilename() );
		this.upload.setText( this.getText() );

		// TODO: Validations
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

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.renderUploadForm = function () {
		var file, fieldset;

		file = new OO.ui.SelectFileWidget();
		fieldset = new OO.ui.FieldsetLayout( { label: 'Select file' } );
		fieldset.addItems( [ file ] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		return this.uploadForm;
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.renderInfoForm = function () {
		var name, description, fieldset,
			fileName = this.getFile().name;

		name = new OO.ui.TextInputWidget( {
			value: fileName,
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

		return this.infoForm;
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.getFile = function () {
		return this.uploadForm // FormLayout
			.getItems()[0] // Fieldset (the only one)
			.getItems()[0] // SelectFileWidget (the only one)
			.getValue();

	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.getFilename = function () {
		return this.infoForm // FormLayout
			.getItems()[0] // FieldsetLayout (the only one)
			.getItems()[0] // FieldLayout (the first one has the name)
			.getField() // TextInputWidget
			.getValue();
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.getText = function () {
		return this.infoForm // FormLayout
			.getItems()[0] // FieldsetLayout (the only one)
			.getItems()[1] // FieldLayout (the second one has the description)
			.getField() // TextInputWidget
			.getValue();
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.renderInsertForm = function () {
		var filename, fielset,
			filename = this.upload.getFilename();

		filename = new OO.ui.TextInputWidget( {
			value: '[[File:' + filename + ']]'
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

}( jQuery, mediaWiki ) );
