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
		var deferred = $.Deferred();
		mw.ForeignStructuredUpload.BookletLayout.parent.prototype.initialize.call( this )
			.done( function () {
				// Point the CategorySelector to the right wiki
				this.upload.apiPromise.done( function ( api ) {
					// If this is a ForeignApi, it will have a apiUrl, otherwise we don't need to do anything
					if ( api.apiUrl ) {
						// Can't reuse the same object, CategorySelector calls #abort on its mw.Api instance
						this.categoriesWidget.api = new mw.ForeignApi( api.apiUrl );
					}
					deferred.resolve();
				}.bind( this ) );
			}.bind( this ) );
		return deferred.promise();
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
		var
			query = location.search && location.search.match( /[?&]uploadbucket=(\d)/ ),
			userId = mw.config.get( 'wgUserId' );
		if ( !userId ) {
			// Anonymous user. This can actually happen, because our software sucks.
			// Display the UI anyway, and allow them to try submitting, but don't bucket.
			this.shouldRecordBucket = false;
			return this.renderUploadForm1();
		} else {
			// Testing and debugging
			if ( query && query[ 1 ] ) {
				this.shouldRecordBucket = false;
				this.bucket = Number( query[ 1 ] );
			} else {
				// Logged in user
				this.shouldRecordBucket = true;
				this.bucket = ( userId % 4 ) + 1; // 1, 2, 3, 4
			}
			return this[ 'renderUploadForm' + this.bucket ]();
		}
	};

	/**
	 * Test option 1, the original one. See T120867.
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm1 = function () {
		var fieldset, $ownWorkMessage, $notOwnWorkMessage,
			onUploadFormChange,
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

		onUploadFormChange = function () {
			var file = this.selectFileWidget.getValue(),
				ownWork = this.ownWorkCheckbox.isSelected(),
				valid = !!file && ownWork;
			this.emit( 'uploadValid', valid );
		};

		// Validation
		this.selectFileWidget.on( 'change', onUploadFormChange.bind( this ) );
		this.ownWorkCheckbox.on( 'change', onUploadFormChange.bind( this ) );

		return this.uploadForm;
	};

	/**
	 * Test option 2, idea A from T121021. See T120867.
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm2 = function () {
		var fieldset, checkboxes, fields, onUploadFormChange;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		this.licenseCheckboxes = checkboxes = [
			new OO.ui.CheckboxInputWidget(),
			new OO.ui.CheckboxInputWidget(),
			new OO.ui.CheckboxInputWidget(),
			new OO.ui.CheckboxInputWidget()
		];

		fields = [
			new OO.ui.FieldLayout( this.selectFileWidget, {
				align: 'top',
				label: mw.msg( 'upload-form-label-select-file' )
			} ),
			new OO.ui.FieldLayout( new OO.ui.LabelWidget( {
				label: 'Thank you for donating an image to be used on {{SITENAME}}. You should only continue if it meets several tests:'
			} ), {
				align: 'top'
			} ),
			new OO.ui.FieldLayout( checkboxes[ 0 ], {
				align: 'inline',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-ownwork' ],
				label: 'It must be entirely your own creation, not just taken from the Web'
			} ),
			new OO.ui.FieldLayout( checkboxes[ 1 ], {
				align: 'inline',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-noderiv' ],
				label: 'It has to contain no work by anyone else, or inspired by them'
			} ),
			new OO.ui.FieldLayout( checkboxes[ 2 ], {
				align: 'inline',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-useful' ],
				label: 'It should be educational and useful for teaching others'
			} ),
			new OO.ui.FieldLayout( checkboxes[ 3 ], {
				align: 'inline',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-ccbysa' ],
				label: 'It must be OK to publish forever on the Internet under the Creative Commons license'
			} )
		];

		fieldset = new OO.ui.FieldsetLayout( { items: fields } );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		this.uploadForm.$element.find( 'a' ).attr( 'target', '_blank' );

		onUploadFormChange = function () {
			var file = this.selectFileWidget.getValue(),
				checks = checkboxes.every( function ( checkbox ) {
					return checkbox.isSelected();
				} ),
				valid = !!file && checks;
			this.emit( 'uploadValid', valid );
		};

		// Validation
		this.selectFileWidget.on( 'change', onUploadFormChange.bind( this ) );
		checkboxes[ 0 ].on( 'change', onUploadFormChange.bind( this ) );
		checkboxes[ 1 ].on( 'change', onUploadFormChange.bind( this ) );
		checkboxes[ 2 ].on( 'change', onUploadFormChange.bind( this ) );
		checkboxes[ 3 ].on( 'change', onUploadFormChange.bind( this ) );

		return this.uploadForm;
	};

	/**
	 * Test option 3, idea D from T121021. See T120867.
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm3 = function () {
		var fieldset, selects, selectFields, fields, onUploadFormChange;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		this.ownWorkCheckbox = new OO.ui.CheckboxInputWidget();

		selects = [
			new OO.ui.RadioSelectWidget( {
				items: [
					new OO.ui.RadioOptionWidget( { data: false, label: 'Yes' } ),
					new OO.ui.RadioOptionWidget( { data: true, label: 'No' } )
				]
			} ),
			new OO.ui.RadioSelectWidget( {
				items: [
					new OO.ui.RadioOptionWidget( { data: false, label: 'Yes' } ),
					new OO.ui.RadioOptionWidget( { data: true, label: 'No' } )
				]
			} ),
			new OO.ui.RadioSelectWidget( {
				items: [
					new OO.ui.RadioOptionWidget( { data: true, label: 'Yes' } ),
					new OO.ui.RadioOptionWidget( { data: false, label: 'No' } )
				]
			} )
		];

		this.licenseSelectFields = selectFields = [
			new OO.ui.FieldLayout( selects[ 0 ], {
				align: 'top',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-question' ],
				label: 'Did you download this image from a website?'
			} ),
			new OO.ui.FieldLayout( selects[ 1 ], {
				align: 'top',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-question' ],
				label: 'Did you get this from an image search?'
			} ).toggle( false ),
			new OO.ui.FieldLayout( selects[ 2 ], {
				align: 'top',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-question' ],
				label: 'If its a photograph, did you click it yourself?'
			} ).toggle( false )
		];

		// Choosing the right answer to each question shows the next question.
		// Switching to wrong answer hides all subsequent questions.
		selects.forEach( function ( select, i ) {
			select.on( 'choose', function ( selectedOption ) {
				if ( i + 1 === selectFields.length ) {
					// Last question
					return;
				}
				if ( !!selectedOption.getData() ) {
					selectFields[ i + 1 ].toggle( true );
				} else {
					selectFields.slice( i + 1 ).forEach( function ( field ) {
						field.fieldWidget.selectItem( null );
						field.toggle( false );
					} );
				}
			} );
		} );

		fields = [
			new OO.ui.FieldLayout( this.selectFileWidget, {
				align: 'top',
				label: mw.msg( 'upload-form-label-select-file' )
			} ),
			selectFields[ 0 ],
			selectFields[ 1 ],
			selectFields[ 2 ],
			new OO.ui.FieldLayout( this.ownWorkCheckbox, {
				align: 'inline',
				label: mw.message( 'foreign-structured-upload-form-label-own-work-message-shared' ).parseDom()
			} )
		];

		fieldset = new OO.ui.FieldsetLayout( { items: fields } );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		this.uploadForm.$element.find( 'a' ).attr( 'target', '_blank' );

		onUploadFormChange = function () {
			var file = this.selectFileWidget.getValue(),
				checkbox = this.ownWorkCheckbox.isSelected(),
				questions = selects.every( function ( select ) {
					return select.getSelectedItem() && !!select.getSelectedItem().getData();
				} ),
				valid = !!file && checkbox && questions;
			this.ownWorkCheckbox.setDisabled( !questions );
			this.emit( 'uploadValid', valid );
		};

		// Validation
		this.selectFileWidget.on( 'change', onUploadFormChange.bind( this ) );
		this.ownWorkCheckbox.on( 'change', onUploadFormChange.bind( this ) );
		selects[ 0 ].on( 'choose', onUploadFormChange.bind( this ) );
		selects[ 1 ].on( 'choose', onUploadFormChange.bind( this ) );
		selects[ 2 ].on( 'choose', onUploadFormChange.bind( this ) );

		return this.uploadForm;
	};

	/**
	 * Test option 4, idea E from T121021. See T120867.
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm4 = function () {
		var fieldset;
		this.renderUploadForm1();
		fieldset = this.uploadForm.getItems()[ 0 ];
		// Note the index, we insert after the SelectFileWidget field
		fieldset.addItems( [
			new OO.ui.FieldLayout( new OO.ui.LabelWidget( {
				label: '[additional content goes here...]'
			} ), {
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-goodBadExamples' ],
				align: 'top'
			} )
		], 1 );
		return this.uploadForm;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onUploadFormChange = function () {};

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
		var language = mw.config.get( 'wgContentLanguage' );
		this.upload.clearDescriptions();
		this.upload.addDescription( language, this.descriptionWidget.getValue() );
		this.upload.setDate( this.dateWidget.getValue() );
		this.upload.clearCategories();
		this.upload.addCategories( this.categoriesWidget.getItemsData() );
		return this.upload.getText();
	};

	/* Setters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.clear = function () {
		mw.ForeignStructuredUpload.BookletLayout.parent.prototype.clear.call( this );

		if ( this.ownWorkCheckbox ) {
			this.ownWorkCheckbox.setSelected( false );
		}
		if ( this.licenseCheckboxes ) {
			this.licenseCheckboxes.forEach( function ( checkbox ) {
				checkbox.setSelected( false );
			} );
		}
		if ( this.licenseSelectFields ) {
			this.licenseSelectFields.forEach( function ( field, i ) {
				field.fieldWidget.selectItem( null );
				if ( i !== 0 ) {
					field.toggle( false );
				}
			} );
		}

		this.categoriesWidget.setItemsFromData( [] );
		this.dateWidget.setValue( '' ).setValidityFlag( true );
	};

}( jQuery, mediaWiki ) );
