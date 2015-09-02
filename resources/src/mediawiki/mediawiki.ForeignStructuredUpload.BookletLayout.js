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
	 * @cfg {string} [targetHost] Used to set up the target wiki.
	 * 	If nothing is passed, the {@link mw.ForeignUpload#property-targetHost default} is used.
	 */
	mw.ForeignStructuredUpload.BookletLayout = function ( config ) {
		config = config || {};
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
		return new mw.ForeignStructuredUpload( this.targetHost );
	};

	/* Form renderers */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm = function () {
		var fieldset,
			target = mw.config.get( 'wgRemoteUploadTarget' ),
			localName = mw.config.get( 'wgSiteName' ),
			targetMsg = 'shared-repo-name' + ( target === 'default' ? '' : '-' + target ),
			targetName = mw.message( targetMsg ).text(),
			$ownWorkMessage = '<p>' + mw.message( 'foreign-structured-upload-form-label-own-work-message-' + target, targetName ).parse() + '</p>',
			$notOwnWorkMessage = $( '<div>' ).append(
				$( '<p>' ).html( mw.message( 'foreign-structured-upload-form-label-not-own-work-message-' + target, targetName ).parse() ),
				$( '<p>' ).html( mw.message( 'foreign-structured-upload-form-label-not-own-work-local-' + target, localName ).parse()  )
			),
			layout = this;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		this.messageLabel = new OO.ui.LabelWidget( {
			label: $( $notOwnWorkMessage )
		} );
		this.ownWorkCheckbox = new OO.ui.CheckboxInputWidget().on( 'change', function ( on ) {
			if ( on ) {
				layout.messageLabel.setLabel( $( $ownWorkMessage ) );
			} else {
				layout.messageLabel.setLabel( $( $notOwnWorkMessage ) );
			}
		} );

		fieldset = new OO.ui.FieldsetLayout();
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.selectFileWidget, {
				align: 'top',
				label: mw.msg( 'upload-form-label-select-file' )
			} ),
			new OO.ui.FieldLayout( this.ownWorkCheckbox, {
				align: 'top',
				label: mw.msg( 'foreign-structured-upload-form-label-own-work' )
			} ),
			this.messageLabel
		] );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		// Validation
		this.selectFileWidget.on( 'change', this.onUploadFormChange.bind( this ) );
		this.ownWorkCheckbox.on( 'change', this.onUploadFormChange.bind( this ) );

		return this.uploadForm;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onUploadFormChange = function () {
		var file = this.selectFileWidget.getValue(),
			ownWork = this.ownWorkCheckbox.isSelected(),
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
			label: mw.msg( 'foreign-structured-upload-form-label-infoform-legal' )
		} );

		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-form-label-infoform-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameWidget, {
				label: mw.msg( 'upload-form-label-infoform-name' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.categoriesWidget, {
				label: mw.msg( 'foreign-structured-upload-form-label-infoform-categories' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.descriptionWidget, {
				label: mw.msg( 'upload-form-label-infoform-description' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.dateWidget, {
				label: mw.msg( 'foreign-structured-upload-form-label-infoform-date' ),
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
			this.filenameWidget.getValidity(),
			this.descriptionWidget.getValidity(),
			this.dateWidget.getValidity()
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
