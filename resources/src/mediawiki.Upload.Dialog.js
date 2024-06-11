( function () {

	/**
	 * @classdesc Controls a {@link mw.Upload.BookletLayout BookletLayout}.
	 *
	 * ## Usage
	 *
	 * To use, set up a {@link OO.ui.WindowManager window manager} like for normal
	 * dialogs:
	 * ```
	 * var uploadDialog = new mw.Upload.Dialog();
	 * var windowManager = new OO.ui.WindowManager();
	 * $( document.body ).append( windowManager.$element );
	 * windowManager.addWindows( [ uploadDialog ] );
	 * windowManager.openWindow( uploadDialog );
	 * ```
	 *
	 * The dialog's closing promise can be used to get details of the upload.
	 *
	 * If you want to use a different {@link OO.ui.BookletLayout}, for example the
	 * {@link mw.ForeignStructuredUpload.BookletLayout}, like in the case of the upload
	 * interface in VisualEditor, you can pass it in through the `bookletClass` config option:
	 * ```
	 * var uploadDialog = new mw.Upload.Dialog( {
	 *     bookletClass: mw.ForeignStructuredUpload.BookletLayout
	 * } );
	 * ```
	 *
	 * @class mw.Upload.Dialog
	 * @extends OO.ui.ProcessDialog
	 *
	 * @constructor
	 * @description Create an instance of `mw.Upload.Dialog`.
	 * @param {Object} [config] Configuration options
	 * @param {Function} [config.bookletClass=mw.Upload.BookletLayout] Booklet class to be
	 *     used for the steps
	 * @param {Object} [config.booklet] Booklet constructor configuration
	 */
	mw.Upload.Dialog = function ( config ) {
		// Config initialization
		config = Object.assign( {
			bookletClass: mw.Upload.BookletLayout
		}, config );

		// Parent constructor
		mw.Upload.Dialog.super.call( this, config );

		// Initialize
		this.bookletClass = config.bookletClass;
		this.bookletConfig = config.booklet;
	};

	/* Setup */

	OO.inheritClass( mw.Upload.Dialog, OO.ui.ProcessDialog );

	/* Static Properties */

	/**
	 * @inheritdoc
	 * @property {string} name
	 */
	mw.Upload.Dialog.static.name = 'mwUploadDialog';

	/**
	 * @inheritdoc
	 * @property {Function|string} title
	 */
	mw.Upload.Dialog.static.title = mw.msg( 'upload-dialog-title' );

	/**
	 * @inheritdoc
	 * @property {Object[]} actions
	 */
	mw.Upload.Dialog.static.actions = [
		{
			flags: 'safe',
			action: 'cancel',
			label: mw.msg( 'upload-dialog-button-cancel' ),
			modes: [ 'upload', 'insert' ]
		},
		{
			flags: 'safe',
			action: 'cancelupload',
			label: mw.msg( 'upload-dialog-button-back' ),
			modes: [ 'info' ]
		},
		{
			flags: [ 'primary', 'progressive' ],
			label: mw.msg( 'upload-dialog-button-done' ),
			action: 'insert',
			modes: 'insert'
		},
		{
			flags: [ 'primary', 'progressive' ],
			label: mw.msg( 'upload-dialog-button-save' ),
			action: 'save',
			modes: 'info'
		},
		{
			flags: [ 'primary', 'progressive' ],
			label: mw.msg( 'upload-dialog-button-upload' ),
			action: 'upload',
			modes: 'upload'
		}
	];

	/* Methods */

	/**
	 * @ignore
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.initialize = function () {
		// Parent method
		mw.Upload.Dialog.super.prototype.initialize.call( this );

		this.uploadBooklet = this.createUploadBooklet();
		this.uploadBooklet.connect( this, {
			set: 'onUploadBookletSet',
			uploadValid: 'onUploadValid',
			infoValid: 'onInfoValid'
		} );

		this.$body.append( this.uploadBooklet.$element );
	};

	/**
	 * Create an upload booklet.
	 *
	 * @protected
	 * @return {mw.Upload.BookletLayout} An upload booklet
	 */
	mw.Upload.Dialog.prototype.createUploadBooklet = function () {
		// eslint-disable-next-line new-cap
		return new this.bookletClass( Object.assign( {
			$overlay: this.$overlay
		}, this.bookletConfig ) );
	};

	/**
	 * @ignore
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getBodyHeight = function () {
		return 600;
	};

	/**
	 * Handle panelNameSet events from the upload booklet.
	 *
	 * @protected
	 * @param {OO.ui.PageLayout} page Current page
	 */
	mw.Upload.Dialog.prototype.onUploadBookletSet = function ( page ) {
		this.actions.setMode( page.getName() );
		this.actions.setAbilities( { upload: false, save: false } );
	};

	/**
	 * Handle uploadValid events.
	 *
	 * {@link OO.ui.ActionSet#setAbilities Sets abilities}
	 * for the dialog accordingly.
	 *
	 * @protected
	 * @param {boolean} isValid The panel is complete and valid
	 */
	mw.Upload.Dialog.prototype.onUploadValid = function ( isValid ) {
		this.actions.setAbilities( { upload: isValid } );
	};

	/**
	 * Handle infoValid events.
	 *
	 * {@link OO.ui.ActionSet#setAbilities Sets abilities}
	 * for the dialog accordingly.
	 *
	 * @protected
	 * @param {boolean} isValid The panel is complete and valid
	 */
	mw.Upload.Dialog.prototype.onInfoValid = function ( isValid ) {
		this.actions.setAbilities( { save: isValid } );
	};

	/**
	 * @ignore
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getSetupProcess = function ( data ) {
		return mw.Upload.Dialog.super.prototype.getSetupProcess.call( this, data )
			.next( function () {
				return this.uploadBooklet.initialize();
			}, this );
	};

	/**
	 * @ignore
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getActionProcess = function ( action ) {
		var dialog = this;

		if ( action === 'upload' ) {
			return new OO.ui.Process( this.uploadBooklet.uploadFile() );
		}
		if ( action === 'save' ) {
			return new OO.ui.Process( this.uploadBooklet.saveFile() );
		}
		if ( action === 'insert' ) {
			return new OO.ui.Process( () => {
				dialog.close( dialog.upload );
			} );
		}
		if ( action === 'cancel' ) {
			return new OO.ui.Process( this.close().closed );
		}
		if ( action === 'cancelupload' ) {
			return new OO.ui.Process( this.uploadBooklet.initialize() );
		}

		return mw.Upload.Dialog.super.prototype.getActionProcess.call( this, action );
	};

	/**
	 * @ignore
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getTeardownProcess = function ( data ) {
		return mw.Upload.Dialog.super.prototype.getTeardownProcess.call( this, data )
			.next( function () {
				this.uploadBooklet.clear();
			}, this );
	};
}() );
