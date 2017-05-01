( function ( $, mw ) {

	/**
	 * mw.Upload.Dialog controls a {@link mw.Upload.BookletLayout BookletLayout}.
	 *
	 * ## Usage
	 *
	 * To use, setup a {@link OO.ui.WindowManager window manager} like for normal
	 * dialogs:
	 *
	 *     var uploadDialog = new mw.Upload.Dialog();
	 *     var windowManager = new OO.ui.WindowManager();
	 *     $( 'body' ).append( windowManager.$element );
	 *     windowManager.addWindows( [ uploadDialog ] );
	 *     windowManager.openWindow( uploadDialog );
	 *
	 * The dialog's closing promise can be used to get details of the upload.
	 *
	 * If you want to use a different OO.ui.BookletLayout, for example the
	 * mw.ForeignStructuredUpload.BookletLayout, like in the case of of the upload
	 * interface in VisualEditor, you can pass it in the {@link #cfg-bookletClass}:
	 *
	 *     var uploadDialog = new mw.Upload.Dialog( {
	 *         bookletClass: mw.ForeignStructuredUpload.BookletLayout
	 *     } );
	 *
	 *
	 * @class mw.Upload.Dialog
	 * @uses mw.Upload
	 * @uses mw.Upload.BookletLayout
	 * @extends OO.ui.ProcessDialog
	 * @cfg {Function} [bookletClass=mw.Upload.BookletLayout] Booklet class to be
	 *     used for the steps
	 * @cfg {Object} [booklet] Booklet constructor configuration
	 */
	mw.Upload.Dialog = function ( config ) {
		// Config initialization
		config = $.extend( {
			bookletClass: mw.Upload.BookletLayout
		}, config );

		// Parent constructor
		mw.Upload.Dialog.parent.call( this, config );

		// Initialize
		this.bookletClass = config.bookletClass;
		this.bookletConfig = config.booklet;
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

	/*jshint +W024*/

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.initialize = function () {
		// Parent method
		mw.Upload.Dialog.parent.prototype.initialize.call( this );

		this.uploadBooklet = this.createUploadBooklet();
		this.uploadBooklet.connect( this, {
			set: 'onUploadBookletSet',
			uploadValid: 'onUploadValid',
			infoValid: 'onInfoValid'
		} );

		this.$body.append( this.uploadBooklet.$element );
	};

	/**
	 * Create an upload booklet
	 *
	 * @protected
	 * @return {mw.Upload.BookletLayout} An upload booklet
	 */
	mw.Upload.Dialog.prototype.createUploadBooklet = function () {
		return new this.bookletClass( $.extend( {
			$overlay: this.$overlay
		}, this.bookletConfig ) );
	};

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getBodyHeight = function () {
		return 600;
	};

	/**
	 * Handle panelNameSet events from the upload booklet
	 *
	 * @protected
	 * @param {OO.ui.PageLayout} page Current page
	 */
	mw.Upload.Dialog.prototype.onUploadBookletSet = function ( page ) {
		this.actions.setMode( page.getName() );
		this.actions.setAbilities( { upload: false, save: false } );
	};

	/**
	 * Handle uploadValid events
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
	 * Handle infoValid events
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
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getSetupProcess = function ( data ) {
		return mw.Upload.Dialog.parent.prototype.getSetupProcess.call( this, data )
			.next( function () {
				return this.uploadBooklet.initialize();
			}, this );
	};

	/**
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
			return new OO.ui.Process( function () {
				dialog.close( dialog.upload );
			} );
		}
		if ( action === 'cancel' ) {
			return new OO.ui.Process( this.close() );
		}
		if ( action === 'cancelupload' ) {
			return new OO.ui.Process( this.uploadBooklet.initialize() );
		}

		return mw.Upload.Dialog.parent.prototype.getActionProcess.call( this, action );
	};

	/**
	 * @inheritdoc
	 */
	mw.Upload.Dialog.prototype.getTeardownProcess = function ( data ) {
		return mw.Upload.Dialog.parent.prototype.getTeardownProcess.call( this, data )
			.next( function () {
				this.uploadBooklet.clear();
			}, this );
	};
}( jQuery, mediaWiki ) );
