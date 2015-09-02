/*global moment */
( function ( $, mw ) {

	/**
	 * mw.ForeignStructuredUpload.BookletLayout encapsulates the process
	 * of uploading a file to MediaWiki using the mw.ForeignStructuredUpload model.
	 *
	 *     var uploadBooklet = new mw.ForeignStructuredUpload.BookletLayout( {
	 *     	targetHost: 'localhost:8080'
	 *     } );
	 *     var uploadDialog = new mw.Upload.Dialog( {
	 *     	uploadBooklet: uploadBooklet
	 *     } );
	 *     var windowManager = new OO.ui.WindowManager();
	 *     $( 'body' ).append( windowManager.$element );
	 *     windowManager.addWindows( [ uploadDialog ] );
	 *
	 * @class mw.ForeignStructuredUpload.BookletLayout
	 * @uses mw.ForeignStructuredUpload
	 * @extends mw.Upload.BookletLayout
	 * @cfg {string} targetHost Used to set up the target wiki.
	 * 	If nothing is passed, the {@link mw.ForeignUpload#property-targetHost default} is used.
	 */
	mw.ForeignStructuredUpload.BookletLayout = function ( config ) {
		// Parent constructor
		mw.ForeignStructuredUpload.BookletLayout.parent.call( this, config );

		this.targetHost = config.targetHost;
	};

	/* Setup */

	OO.inheritClass( mw.ForeignStructuredUpload.BookletLayout, mw.Upload.BookletLayout );

	/* Uploading */

	/**
	 * Returns a {@link mw.ForeignStructuredUpload mw.ForeignStructuredUpload}
	 * with the {@link #cfg-targetHost targetHost} specified in config.
	 *
	 * @protected
	 * @return {mw.Upload}
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.createUpload = function () {
		if ( this.targetHost === undefined ) {
			return new mw.ForeignStructuredUpload();
		} else {
			return new mw.ForeignStructuredUpload( this.targetHost );
		}
	};

	/* Form renderers */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm = function () {
		var fieldset,
			ownWorkMessage = '<p>' + mw.message( 'foreign-structured-upload-dialog-label-own-work-message' ).parse() + '</p>',
			notOwnWorkMessage = '<p>' + mw.message( 'foreign-structured-upload-dialog-label-not-own-work-message' ).parse() + '</p>',
			layout = this;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		this.messageLabel = new OO.ui.LabelWidget( {
			label: $( notOwnWorkMessage )
		} );
		this.ownWorkToggle = new OO.ui.ToggleSwitchWidget().on( 'change', function ( on ) {
			if ( on ) {
				layout.messageLabel.setLabel( $( ownWorkMessage ) );
			} else {
				layout.messageLabel.setLabel( $( notOwnWorkMessage ) );
			}
		} );

		fieldset = new OO.ui.FieldsetLayout();
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.selectFileWidget, {
				align: 'top',
				label: mw.msg( 'upload-dialog-label-select-file' )
			} ),
			new OO.ui.FieldLayout( this.ownWorkToggle, {
				align: 'top',
				label: 'Is this your own work?'
			} ),
			this.messageLabel
		] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		this.selectFileWidget.on( 'change', this.onUploadFormChange.bind( this ) );
		this.ownWorkToggle.on( 'change', this.onUploadFormChange.bind( this ) );

		return this.uploadForm;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onUploadFormChange = function () {
		var file = this.selectFileWidget.getValue(),
			ownWork = this.ownWorkToggle.getValue(),
			valid = !!file && ownWork;
		this.emit( 'uploadValid', valid );
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderInfoForm = function () {
		var fieldset;

		this.filenameWidget = new OO.ui.TextInputWidget( {
			required: true,
			validate: /.+/
		} );
		this.descriptionWidget = new OO.ui.TextInputWidget( {
			required: true,
			validate: /.+/,
			multiline: true,
			autosize: true
		} );
		this.dateWidget = new mw.widgets.DateInputWidget( {
			required: true,
			mustBeBefore: moment().add( 1, 'day' ).locale( 'en' ).format( 'YYYY-MM-DD' ) // Tomorrow
		} );
		this.categoriesWidget = new mw.widgets.CategorySelector( {
			$overlay: this.$overlay
		} );
		this.legalLabel = new OO.ui.LabelWidget( {
			label: mw.msg( 'foreign-structured-upload-dialog-label-infoform-legal' )
		} );

		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-dialog-label-infoform-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameWidget, {
				label: mw.msg( 'upload-dialog-label-infoform-name' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.categoriesWidget, {
				label: mw.msg( 'foreign-structured-upload-dialog-label-infoform-categories' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.descriptionWidget, {
				label: mw.msg( 'upload-dialog-label-infoform-description' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.dateWidget, {
				label: mw.msg( 'foreign-structured-upload-dialog-label-infoform-date' ),
				align: 'top'
			} ),
			this.legalLabel
		] );
		this.infoForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		this.filenameWidget.on( 'change', this.onInfoFormChange.bind( this ) );
		this.descriptionWidget.on( 'change', this.onInfoFormChange.bind( this ) );
		this.dateWidget.on( 'change', this.onInfoFormChange.bind( this ) );

		return this.infoForm;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onInfoFormChange = function () {
		var layout = this;
		$.when(
			layout.filenameWidget.getValidity(),
			layout.descriptionWidget.getValidity(),
			layout.dateWidget.getValidity()
		).done( function () {
			layout.emit( 'infoValid', true );
		} ).fail( function () {
			layout.emit( 'infoValid', false );
		} );
	};

	/* Getters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getText = function () {
		this.upload.addDescription( 'en', this.descriptionWidget.getValue() );
		this.upload.setDate( this.dateWidget.getValue() );
		this.upload.addCategories( this.categoriesWidget.getItemsData() );
		return this.upload.getText();
	};
}( jQuery, mediaWiki ) );
