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
			query = /[?&]uploadbucket=(\d)/.exec( location.search ),
			// TODO Condition based on wiki language, to match available translations
			isTestEnabled = false,
			userId = mw.config.get( 'wgUserId' );

		if ( query && query[ 1 ] ) {
			// Testing and debugging
			this.shouldRecordBucket = false;
			this.bucket = Number( query[ 1 ] );
		} else if ( !userId || !isTestEnabled ) {
			// a) Anonymous user. This can actually happen, because our software sucks.
			// b) Test is not enabled on this wiki.
			// In either case, display the old interface and don't record bucket on uploads.
			this.shouldRecordBucket = false;
			this.bucket = 1;
		} else {
			// Regular logged in user on a wiki where the test is running
			this.shouldRecordBucket = true;
			this.bucket = ( userId % 4 ) + 1; // 1, 2, 3, 4
		}

		return this[ 'renderUploadForm' + this.bucket ]();
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

		// Temporary override to make my life easier during A/B test
		target = 'shared';

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
		$ownWorkMessage.add( $notOwnWorkMessage ).find( 'a' )
			.attr( 'target', '_blank' )
			.on( 'click', function ( e ) {
				// Some stupid code is trying to prevent default on all clicks, which causes the links to
				// not be openable, don't let it
				e.stopPropagation();
			} );

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
				label: mw.message( 'foreign-structured-upload-form-2-label-intro' ).parseDom()
			} ), {
				align: 'top'
			} ),
			new OO.ui.FieldLayout( checkboxes[ 0 ], {
				align: 'inline',
				classes: [
					'mw-foreignStructuredUpload-bookletLayout-withicon',
					'mw-foreignStructuredUpload-bookletLayout-ownwork'
				],
				label: mw.message( 'foreign-structured-upload-form-2-label-ownwork' ).parseDom()
			} ),
			new OO.ui.FieldLayout( checkboxes[ 1 ], {
				align: 'inline',
				classes: [
					'mw-foreignStructuredUpload-bookletLayout-withicon',
					'mw-foreignStructuredUpload-bookletLayout-noderiv'
				],
				label: mw.message( 'foreign-structured-upload-form-2-label-noderiv' ).parseDom()
			} ),
			new OO.ui.FieldLayout( checkboxes[ 2 ], {
				align: 'inline',
				classes: [
					'mw-foreignStructuredUpload-bookletLayout-withicon',
					'mw-foreignStructuredUpload-bookletLayout-useful'
				],
				label: mw.message( 'foreign-structured-upload-form-2-label-useful' ).parseDom()
			} ),
			new OO.ui.FieldLayout( checkboxes[ 3 ], {
				align: 'inline',
				classes: [
					'mw-foreignStructuredUpload-bookletLayout-withicon',
					'mw-foreignStructuredUpload-bookletLayout-ccbysa'
				],
				label: mw.message( 'foreign-structured-upload-form-2-label-ccbysa' ).parseDom()
			} ),
			new OO.ui.FieldLayout( new OO.ui.LabelWidget( {
				label: $()
					.add( $( '<p>' ).msg( 'foreign-structured-upload-form-2-label-alternative' ) )
					.add( $( '<p>' ).msg( 'foreign-structured-upload-form-2-label-termsofuse' )
						.addClass( 'mw-foreignStructuredUpload-bookletLayout-license' ) )
			} ), {
				align: 'top'
			} )
		];

		fieldset = new OO.ui.FieldsetLayout( { items: fields } );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		this.uploadForm.$element.find( 'a' )
			.attr( 'target', '_blank' )
			.on( 'click', function ( e ) {
				// Some stupid code is trying to prevent default on all clicks, which causes the links to
				// not be openable, don't let it
				e.stopPropagation();
			} );

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
		var ownWorkCheckbox, fieldset, yesMsg, noMsg, selects, selectFields,
			alternativeField, fields, onUploadFormChange;

		this.selectFileWidget = new OO.ui.SelectFileWidget();
		this.ownWorkCheckbox = ownWorkCheckbox = new OO.ui.CheckboxInputWidget();

		yesMsg = mw.message( 'foreign-structured-upload-form-3-label-yes' ).text();
		noMsg = mw.message( 'foreign-structured-upload-form-3-label-no' ).text();
		selects = [
			new OO.ui.RadioSelectWidget( {
				items: [
					new OO.ui.RadioOptionWidget( { data: false, label: yesMsg } ),
					new OO.ui.RadioOptionWidget( { data: true, label: noMsg } )
				]
			} ),
			new OO.ui.RadioSelectWidget( {
				items: [
					new OO.ui.RadioOptionWidget( { data: true, label: yesMsg } ),
					new OO.ui.RadioOptionWidget( { data: false, label: noMsg } )
				]
			} ),
			new OO.ui.RadioSelectWidget( {
				items: [
					new OO.ui.RadioOptionWidget( { data: false, label: yesMsg } ),
					new OO.ui.RadioOptionWidget( { data: true, label: noMsg } )
				]
			} )
		];

		this.licenseSelectFields = selectFields = [
			new OO.ui.FieldLayout( selects[ 0 ], {
				align: 'top',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-question' ],
				label: mw.message( 'foreign-structured-upload-form-3-label-question-website' ).parseDom()
			} ),
			new OO.ui.FieldLayout( selects[ 1 ], {
				align: 'top',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-question' ],
				label: mw.message( 'foreign-structured-upload-form-3-label-question-ownwork' ).parseDom()
			} ).toggle( false ),
			new OO.ui.FieldLayout( selects[ 2 ], {
				align: 'top',
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-question' ],
				label: mw.message( 'foreign-structured-upload-form-3-label-question-noderiv' ).parseDom()
			} ).toggle( false )
		];

		alternativeField = new OO.ui.FieldLayout( new OO.ui.LabelWidget( {
			label: mw.message( 'foreign-structured-upload-form-3-label-alternative' ).parseDom()
		} ), {
			align: 'top'
		} ).toggle( false );

		// Choosing the right answer to each question shows the next question.
		// Switching to wrong answer hides all subsequent questions.
		selects.forEach( function ( select, i ) {
			select.on( 'choose', function ( selectedOption ) {
				var isRightAnswer = !!selectedOption.getData();
				alternativeField.toggle( !isRightAnswer );
				if ( i + 1 === selectFields.length ) {
					// Last question
					return;
				}
				if ( isRightAnswer ) {
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
			alternativeField,
			new OO.ui.FieldLayout( ownWorkCheckbox, {
				classes: [ 'mw-foreignStructuredUpload-bookletLayout-checkbox' ],
				align: 'inline',
				label: mw.message( 'foreign-structured-upload-form-label-own-work-message-shared' ).parseDom()
			} )
		];

		// Must be done late, after it's been associated with the FieldLayout
		ownWorkCheckbox.setDisabled( true );

		fieldset = new OO.ui.FieldsetLayout( { items: fields } );
		this.uploadForm = new OO.ui.FormLayout( { items: [ fieldset ] } );

		this.uploadForm.$element.find( 'a' )
			.attr( 'target', '_blank' )
			.on( 'click', function ( e ) {
				// Some stupid code is trying to prevent default on all clicks, which causes the links to
				// not be openable, don't let it
				e.stopPropagation();
			} );

		onUploadFormChange = function () {
			var file = this.selectFileWidget.getValue(),
				checkbox = ownWorkCheckbox.isSelected(),
				rightAnswers = selects.every( function ( select ) {
					return select.getSelectedItem() && !!select.getSelectedItem().getData();
				} ),
				valid = !!file && checkbox && rightAnswers;
			ownWorkCheckbox.setDisabled( !rightAnswers );
			if ( !rightAnswers ) {
				ownWorkCheckbox.setSelected( false );
			}
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
		var fieldset, $guide;
		this.renderUploadForm1();
		fieldset = this.uploadForm.getItems()[ 0 ];

		$guide = mw.template.get( 'mediawiki.ForeignStructuredUpload.BookletLayout', 'guide.html' ).render();
		$guide.find( '.mw-foreignStructuredUpload-bookletLayout-guide-text-wrapper-good span' )
			.msg( 'foreign-structured-upload-form-4-label-good' );
		$guide.find( '.mw-foreignStructuredUpload-bookletLayout-guide-text-wrapper-bad span' )
			.msg( 'foreign-structured-upload-form-4-label-bad' );

		// Note the index, we insert after the SelectFileWidget field
		fieldset.addItems( [
			new OO.ui.FieldLayout( new OO.ui.Widget( {
				$content: $guide
			} ), {
				align: 'top'
			} )
		], 1 );

		// Streamline: remove mention of local Special:Upload
		fieldset.getItems()[ 3 ].$element.find( 'p' ).last().remove();

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
