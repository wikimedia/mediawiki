/*global moment */
( function ( $, mw ) {

	/**
	 * mw.ForeignStructuredUpload.BookletLayout encapsulates the process
	 * of uploading a file to MediaWiki using the mw.ForeignStructuredUpload model.
	 *
	 *     var uploadDialog = new mw.Upload.Dialog( {
	 *         bookletClass: mw.ForeignStructuredUpload.BookletLayout,
	 *         booklet: {
	 *             target: 'local'
	 *         }
	 *     } );
	 *     var windowManager = new OO.ui.WindowManager();
	 *     $( 'body' ).append( windowManager.$element );
	 *     windowManager.addWindows( [ uploadDialog ] );
	 *
	 * @class mw.ForeignStructuredUpload.BookletLayout
	 * @uses mw.ForeignStructuredUpload
	 * @extends mw.Upload.BookletLayout
	 * @cfg {string} [target] Used to choose the target repository.
	 *     If nothing is passed, the {@link mw.ForeignUpload#property-target default} is used.
	 */
	mw.ForeignStructuredUpload.BookletLayout = function ( config ) {
		config = config || {};
		// Parent constructor
		mw.ForeignStructuredUpload.BookletLayout.parent.call( this, config );

		this.target = config.target;
	};

	/* Setup */

	OO.inheritClass( mw.ForeignStructuredUpload.BookletLayout, mw.Upload.BookletLayout );

	/* Uploading */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.initialize = function () {
		mw.ForeignStructuredUpload.BookletLayout.parent.prototype.initialize.call( this );
		// Point the CategorySelector to the right wiki as soon as we know what the right wiki is
		this.upload.apiPromise.done( function ( api ) {
			// If this is a ForeignApi, it will have a apiUrl, otherwise we don't need to do anything
			if ( api.apiUrl ) {
				// Can't reuse the same object, CategorySelector calls #abort on its mw.Api instance
				this.categoriesWidget.api = new mw.ForeignApi( api.apiUrl );
			}
		}.bind( this ) );
	};

	/**
	 * Returns a {@link mw.ForeignStructuredUpload mw.ForeignStructuredUpload}
	 * with the {@link #cfg-target target} specified in config.
	 *
	 * @protected
	 * @return {mw.Upload}
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.createUpload = function () {
		return new mw.ForeignStructuredUpload( this.target );
	};

	/* Form renderers */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm = function () {
		var fieldset, $ownWorkMessage, $notOwnWorkMessage,
			ownWorkMessage, notOwnWorkMessage, notOwnWorkLocal,
			validTargets = mw.config.get( 'wgForeignUploadTargets' ),
			target = this.target || validTargets[ 0 ] || 'local',
			layout = this;

		// foreign-structured-upload-form-label-own-work-message-local
		// foreign-structured-upload-form-label-own-work-message-shared
		ownWorkMessage = mw.message( 'foreign-structured-upload-form-label-own-work-message-' + target );
		// foreign-structured-upload-form-label-not-own-work-message-local
		// foreign-structured-upload-form-label-not-own-work-message-shared
		notOwnWorkMessage = mw.message( 'foreign-structured-upload-form-label-not-own-work-message-' + target );
		// foreign-structured-upload-form-label-not-own-work-local-local
		// foreign-structured-upload-form-label-not-own-work-local-shared
		notOwnWorkLocal = mw.message( 'foreign-structured-upload-form-label-not-own-work-local-' + target );

		if ( !ownWorkMessage.exists() ) {
			ownWorkMessage = mw.message( 'foreign-structured-upload-form-label-own-work-message-default' );
		}
		if ( !notOwnWorkMessage.exists() ) {
			notOwnWorkMessage = mw.message( 'foreign-structured-upload-form-label-not-own-work-message-default' );
		}
		if ( !notOwnWorkLocal.exists() ) {
			notOwnWorkLocal = mw.message( 'foreign-structured-upload-form-label-not-own-work-local-default' );
		}

		$ownWorkMessage = $( '<p>' ).html( ownWorkMessage.parse() )
			.addClass( 'mw-foreignStructuredUpload-bookletLayout-license' );
		$notOwnWorkMessage = $( '<div>' ).append(
			$( '<p>' ).html( notOwnWorkMessage.parse() ),
			$( '<p>' ).html( notOwnWorkLocal.parse() )
		);
		$ownWorkMessage.add( $notOwnWorkMessage ).find( 'a' ).attr( 'target', '_blank' );

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		this.messageLabel = new OO.ui.LabelWidget( {
			label: $notOwnWorkMessage
		} );
		this.ownWorkCheckbox = new OO.ui.CheckboxInputWidget().on( 'change', function ( on ) {
			layout.messageLabel.toggle( !on );
		} );

		fieldset = new OO.ui.FieldsetLayout();
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.selectFileWidget, {
				align: 'top',
				label: mw.msg( 'upload-form-label-select-file' )
			} ),
			new OO.ui.FieldLayout( this.ownWorkCheckbox, {
				align: 'inline',
				label: $( '<div>' ).append(
					$( '<p>' ).text( mw.msg( 'foreign-structured-upload-form-label-own-work' ) ),
					$ownWorkMessage
				)
			} ),
			new OO.ui.FieldLayout( this.messageLabel, {
				align: 'top'
			} )
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
			$overlay: this.$overlay,
			required: true,
			mustBeBefore: moment().add( 1, 'day' ).locale( 'en' ).format( 'YYYY-MM-DD' ) // Tomorrow
		} );
		this.categoriesWidget = new mw.widgets.CategorySelector( {
			// Can't be done here because we don't know the target wiki yet... done in #initialize.
			// api: new mw.ForeignApi( ... ),
			$overlay: this.$overlay
		} );

		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-form-label-infoform-title' )
		} );
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.filenameWidget, {
				label: mw.msg( 'upload-form-label-infoform-name' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.descriptionWidget, {
				label: mw.msg( 'upload-form-label-infoform-description' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.categoriesWidget, {
				label: mw.msg( 'foreign-structured-upload-form-label-infoform-categories' ),
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.dateWidget, {
				label: mw.msg( 'foreign-structured-upload-form-label-infoform-date' ),
				align: 'top'
			} )
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

	/* Setters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.clear = function () {
		mw.ForeignStructuredUpload.BookletLayout.parent.prototype.clear.call( this );

		this.ownWorkCheckbox.setSelected( false );
		this.categoriesWidget.setItemsFromData( [] );
		this.dateWidget.setValue( '' ).setValidityFlag( true );
	};

}( jQuery, mediaWiki ) );
