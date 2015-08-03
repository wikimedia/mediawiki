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

	/**
	 * @initialize
	 */
	mw.dialogs.Upload.prototype.initialize = function () {
		mw.dialogs.Upload.parent.prototype.initialize.call( this );

		this.upload = this.getUploadObject();
		this.renderUploadForm();
		this.content = new OO.ui.PanelLayout( { padded: true, expanded: false } );
		this.content.$element.append( this.uploadForm.form.$element );
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
		var self = this;

		if ( action === 'insert' ) {
			return new OO.ui.Process( function () {
				self.close( { }  ); // TODO: Return something useful
			} );
		}
		if ( action === 'upload' ) {
			return new OO.ui.Process( function () {
				self.uploadFile();
				self.renderInfoForm();
				self.content.$element
					.empty()
					.append( self.infoForm.form.$element );
				self.actions.setMode( 'save' );
			} );
		}
		if ( action === 'save' ) {
			return new OO.ui.Process( self.saveFile() );
		}
		if ( action === 'cancel' ) {
			self.close();
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
		var self = this,
			file = this.uploadForm.file.getValue();
		this.upload.setFile( file );
		this.uploadPromise = this.upload.uploadToStash();

		this.uploadPromise.then( function () {
			self.emit( 'fileUploaded' );
		} );
	};

	/**
	 * Saves the stash finalizes upload
	 *
	 * @method
	 */
	mw.dialogs.Upload.prototype.saveFile = function () {
		var self = this,
			promise = $.Deferred();

		this.upload.setFilename( this.infoForm.name.getValue() );
		this.upload.setText( this.getText() );

		// TODO: Validations
		this.uploadPromise.always( function () {

			if ( self.upload.getState() === mw.Upload.State.ERROR ) {
				promise.reject( new OO.ui.Error( "An error occurred"  ) );
				return false;
			}

			if ( self.upload.getState() === mw.Upload.State.WARNING ) {
				promise.reject( new OO.ui.Error( "A warning occurred"  ) );
				return false;
			}

			self.upload.finishStashUpload().then( function () {
				if ( self.upload.getState() === mw.Upload.State.ERROR ) {
					promise.reject( new OO.ui.Error( "An error occurred"  ) );
					return false;
				}

				if ( self.upload.getState() === mw.Upload.State.WARNING ) {
					promise.reject( new OO.ui.Error( "A warning occurred"  ) );
					return false;
				}

				promise.resolve();
				self.emit( 'fileSaved' );
			} );
		} );

		self.on( 'fileSaved', function () {
			self.renderInsertForm();
			self.content.$element
				.empty()
				.append( self.insertForm.form.$element );
			self.actions.setMode( 'insert' );
		} );

		return promise;
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.renderUploadForm = function () {
		this.uploadForm = {};
		this.uploadForm.file = new OO.ui.SelectFileWidget();
		this.uploadForm.fieldset = new OO.ui.FieldsetLayout( { label: 'Select file' } );
		this.uploadForm.fieldset.addItems( [ this.uploadForm.file ] );
		this.uploadForm.form = new OO.ui.FormLayout( { items: [ this.uploadForm.fieldset ] } );
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.renderInfoForm = function () {
		var fileName = this.uploadForm.file.getValue().name;
		this.infoForm = {};
		this.infoForm.name = new OO.ui.TextInputWidget( {
			value: fileName,
			indicator: 'required',
			required: true,
			validate: /.+/
		} );
		this.infoForm.description = new OO.ui.TextInputWidget( {
			indicator: 'required',
			required: true,
			validate: /.+/,
			multiline: true,
			autosize: true
		} );
		this.infoForm.fieldset = new OO.ui.FieldsetLayout( { label: 'Details' } );
		this.infoForm.fieldset.addItems( [
			new OO.ui.FieldLayout( this.infoForm.name, {
				label: 'Name',
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.infoForm.description, {
				label: 'Description',
				align: 'top'
			} )
		] );
		this.infoForm.form = new OO.ui.FormLayout( { items: [ this.infoForm.fieldset ] } );
	};

	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.getText = function () {
		return this.infoForm.description.getValue();
	};


	/**
	 * @method
	 */
	mw.dialogs.Upload.prototype.renderInsertForm = function () {
		var self = this;
		this.insertForm = {};
		this.insertForm.filename = new OO.ui.TextInputWidget( {
			value: '[[File:' + self.upload.getFilename() + ']]'
		} );
		this.insertForm.fieldset = new OO.ui.FieldsetLayout( { label: 'Usage' } );
		this.insertForm.fieldset.addItems( [
			new OO.ui.FieldLayout( this.insertForm.filename, {
				label: 'File name',
				align: 'top'
			} )
		] );
		this.insertForm.form = new OO.ui.FormLayout( { items: [ this.insertForm.fieldset ] } );
	};

} ( jQuery, mediaWiki ) );
