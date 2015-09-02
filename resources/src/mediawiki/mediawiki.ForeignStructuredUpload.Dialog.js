/*global moment */
( function ( $, mw ) {

	/**
	 * mw.ForeignStructuredUpload.Dialog encapsulates the process
	 * of uploading a file to MediaWiki using the mw.ForeignStructuredUpload model.
	 *
	 * @class mw.ForeignStructuredUpload.Dialog
	 * @uses mw.ForeignStructuredUpload
	 * @extends mw.Upload.Dialog
	 * @cfg {string} targetHost Used to set up the target wiki.
	 * 	If nothing is passed, the {@link mw.ForeignUpload#property-targetHost default} is used.
	 */
	mw.ForeignStructuredUpload.Dialog = function ( config ) {
		// Parent constructor
		mw.ForeignStructuredUpload.Dialog.parent.call( this, config );

		this.targetHost = config.targetHost;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.Dialog.prototype.initialize = function () {
		// Parent constructor
		mw.ForeignStructuredUpload.Dialog.initialize.call( this );

		// Larger form in this one
		this.panels.scrollable = true;
	};

	/* Setup */

	OO.inheritClass( mw.ForeignStructuredUpload.Dialog, mw.Upload.Dialog );

	/* Uploading */

	/**
	 * Returns a {@link mw.ForeignStructuredUpload mw.ForeignStructuredUpload}
	 * with the {@link #cfg-targetHost targetHost} specified in config.
	 *
	 * @return {mw.Upload}
	 */
	mw.ForeignStructuredUpload.Dialog.prototype.getUploadObject = function () {
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
	mw.ForeignStructuredUpload.Dialog.prototype.renderInfoForm = function () {
		var fieldset,
			dialog = this;

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
			mustBeBefore: moment().add( 1, 'day' ).format( 'YYYY-MM-DD' ) // Tomorrow
		} );
		this.categoriesWidget = new mw.widgets.CategorySelector();
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
		function checkValidity() {
			$.when(
				dialog.filenameWidget.getValidity(),
				dialog.descriptionWidget.getValidity(),
				dialog.dateWidget.getValidity()
			).done( function () {
				dialog.actions.setAbilities( { save: true } );
			} ).fail( function () {
				dialog.actions.setAbilities( { save: false } );
			} );
		}
		this.filenameWidget.on( 'change', checkValidity );
		this.descriptionWidget.on( 'change', checkValidity );
		this.dateWidget.on( 'change', checkValidity );

		return this.infoForm;
	};

	/* Getters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.Dialog.prototype.getText = function () {
		this.upload.addDescription( 'en', this.descriptionWidget.getValue() );
		this.upload.setDate( this.dateWidget.getValue() );
		this.upload.addCategories( this.categoriesWidget.getItemsData() );
		return this.upload.getText();
	};
}( jQuery, mediaWiki ) );
