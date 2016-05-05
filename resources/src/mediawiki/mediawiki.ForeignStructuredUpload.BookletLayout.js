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
		var booklet = this;
		return mw.ForeignStructuredUpload.BookletLayout.parent.prototype.initialize.call( this ).then(
			function () {
				return $.when(
					// Point the CategorySelector to the right wiki
					booklet.upload.getApi().then( function ( api ) {
						// If this is a ForeignApi, it will have a apiUrl, otherwise we don't need to do anything
						if ( api.apiUrl ) {
							// Can't reuse the same object, CategorySelector calls #abort on its mw.Api instance
							booklet.categoriesWidget.api = new mw.ForeignApi( api.apiUrl );
						}
						return $.Deferred().resolve();
					} ),
					// Set up booklet fields and license messages to match configuration
					booklet.upload.loadConfig().then( function ( config ) {
						var
							msgPromise,
							isLocal = booklet.upload.target === 'local',
							fields = config.fields,
							msgs = config.licensemessages[ isLocal ? 'local' : 'foreign' ];

						// Hide disabled fields
						booklet.descriptionField.toggle( !!fields.description );
						booklet.categoriesField.toggle( !!fields.categories );
						booklet.dateField.toggle( !!fields.date );
						// Update form validity
						booklet.onInfoFormChange();

						// Load license messages from the remote wiki if we don't have these messages locally
						// (this means that we only load messages from the foreign wiki for custom config)
						if ( mw.message( 'upload-form-label-own-work-message-' + msgs ).exists() ) {
							msgPromise = $.Deferred().resolve();
						} else {
							msgPromise = booklet.upload.apiPromise.then( function ( api ) {
								return api.loadMessages( [
									'upload-form-label-own-work-message-' + msgs,
									'upload-form-label-not-own-work-message-' + msgs,
									'upload-form-label-not-own-work-local-' + msgs
								] );
							} );
						}

						// Update license messages
						return msgPromise.then( function () {
							booklet.$ownWorkMessage
								.msg( 'upload-form-label-own-work-message-' + msgs )
								.find( 'a' ).attr( 'target', '_blank' );
							booklet.$notOwnWorkMessage
								.msg( 'upload-form-label-not-own-work-message-' + msgs )
								.find( 'a' ).attr( 'target', '_blank' );
							booklet.$notOwnWorkLocal
								.msg( 'upload-form-label-not-own-work-local-' + msgs )
								.find( 'a' ).attr( 'target', '_blank' );
						} );
					} )
				);
			}
		).then(
			null,
			// Always resolve, never reject
			function () { return $.Deferred().resolve(); }
		);
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
		var fieldset,
			layout = this;

		// These elements are filled with text in #initialize
		// TODO Refactor this to be in one place
		this.$ownWorkMessage = $( '<p>' )
			.addClass( 'mw-foreignStructuredUpload-bookletLayout-license' );
		this.$notOwnWorkMessage = $( '<p>' );
		this.$notOwnWorkLocal = $( '<p>' );

		this.selectFileWidget = new OO.ui.SelectFileWidget( {
			showDropTarget: true
		} );
		this.messageLabel = new OO.ui.LabelWidget( {
			label: $( '<div>' ).append(
				this.$notOwnWorkMessage,
				this.$notOwnWorkLocal
			)
		} );
		this.ownWorkCheckbox = new OO.ui.CheckboxInputWidget().on( 'change', function ( on ) {
			layout.messageLabel.toggle( !on );
		} );

		fieldset = new OO.ui.FieldsetLayout();
		fieldset.addItems( [
			new OO.ui.FieldLayout( this.selectFileWidget, {
				align: 'top'
			} ),
			new OO.ui.FieldLayout( this.ownWorkCheckbox, {
				align: 'inline',
				label: $( '<div>' ).append(
					$( '<p>' ).text( mw.msg( 'upload-form-label-own-work' ) ),
					this.$ownWorkMessage
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

		this.selectFileWidget.on( 'change', function () {
			var file = layout.getFile();

			// Set the date to lastModified once we have the file
			if ( layout.getDateFromLastModified( file ) !== undefined ) {
				layout.dateWidget.setValue( layout.getDateFromLastModified( file ) );
			}

			// Check if we have EXIF data and set to that where available
			layout.getDateFromExif( file ).done( function ( date ) {
				layout.dateWidget.setValue( date );
			} );

			layout.updateFilePreview();
		} );

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

		this.filePreview = new OO.ui.Widget( {
			classes: [ 'mw-upload-bookletLayout-filePreview' ]
		} );
		this.progressBarWidget = new OO.ui.ProgressBarWidget( {
			progress: 0
		} );
		this.filePreview.$element.append( this.progressBarWidget.$element );

		this.filenameWidget = new OO.ui.TextInputWidget( {
			required: true,
			validate: /.+/
		} );
		this.descriptionWidget = new OO.ui.TextInputWidget( {
			required: true,
			validate: /\S+/,
			multiline: true,
			autosize: true
		} );
		this.categoriesWidget = new mw.widgets.CategorySelector( {
			// Can't be done here because we don't know the target wiki yet... done in #initialize.
			// api: new mw.ForeignApi( ... ),
			$overlay: this.$overlay
		} );
		this.dateWidget = new mw.widgets.DateInputWidget( {
			$overlay: this.$overlay,
			required: true,
			mustBeBefore: moment().add( 1, 'day' ).locale( 'en' ).format( 'YYYY-MM-DD' ) // Tomorrow
		} );

		this.filenameField = new OO.ui.FieldLayout( this.filenameWidget, {
			label: mw.msg( 'upload-form-label-infoform-name' ),
			align: 'top',
			classes: [ 'mw-foreignStructuredUploa-bookletLayout-small-notice' ],
			notices: [ mw.msg( 'upload-form-label-infoform-name-tooltip' ) ]
		} );
		this.descriptionField = new OO.ui.FieldLayout( this.descriptionWidget, {
			label: mw.msg( 'upload-form-label-infoform-description' ),
			align: 'top',
			classes: [ 'mw-foreignStructuredUploa-bookletLayout-small-notice' ],
			notices: [ mw.msg( 'upload-form-label-infoform-description-tooltip' ) ]
		} );
		this.categoriesField = new OO.ui.FieldLayout( this.categoriesWidget, {
			label: mw.msg( 'upload-form-label-infoform-categories' ),
			align: 'top'
		} );
		this.dateField = new OO.ui.FieldLayout( this.dateWidget, {
			label: mw.msg( 'upload-form-label-infoform-date' ),
			align: 'top'
		} );

		fieldset = new OO.ui.FieldsetLayout( {
			label: mw.msg( 'upload-form-label-infoform-title' )
		} );
		fieldset.addItems( [
			this.filenameField,
			this.descriptionField,
			this.categoriesField,
			this.dateField
		] );
		this.infoForm = new OO.ui.FormLayout( {
			classes: [ 'mw-upload-bookletLayout-infoForm' ],
			items: [ this.filePreview, fieldset ]
		} );

		// Validation
		this.filenameWidget.on( 'change', this.onInfoFormChange.bind( this ) );
		this.descriptionWidget.on( 'change', this.onInfoFormChange.bind( this ) );
		this.dateWidget.on( 'change', this.onInfoFormChange.bind( this ) );

		this.on( 'fileUploadProgress', function ( progress ) {
			this.progressBarWidget.setProgress( progress * 100 );
		}.bind( this ) );

		return this.infoForm;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onInfoFormChange = function () {
		var layout = this,
			validityPromises = [];

		validityPromises.push( this.filenameWidget.getValidity() );
		if ( this.descriptionField.isVisible() ) {
			validityPromises.push( this.descriptionWidget.getValidity() );
		}
		if ( this.dateField.isVisible() ) {
			validityPromises.push( this.dateWidget.getValidity() );
		}

		$.when.apply( $, validityPromises ).done( function () {
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

	/**
	 * Get original date from EXIF data
	 *
	 * @param {Object} file
	 * @return {jQuery.Promise} Promise resolved with the EXIF date
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getDateFromExif = function ( file ) {
		var fileReader,
			deferred = $.Deferred();

		if ( file && file.type === 'image/jpeg' ) {
			fileReader = new FileReader();
			fileReader.onload = function () {
				var fileStr, arr, i, metadata;

				if ( typeof fileReader.result === 'string' ) {
					fileStr = fileReader.result;
				} else {
					// Array buffer; convert to binary string for the library.
					arr = new Uint8Array( fileReader.result );
					fileStr = '';
					for ( i = 0; i < arr.byteLength; i++ ) {
						fileStr += String.fromCharCode( arr[ i ] );
					}
				}

				try {
					metadata = mw.libs.jpegmeta( this.result, file.name );
				} catch ( e ) {
					metadata = null;
				}

				if ( metadata !== null && metadata.exif !== undefined && metadata.exif.DateTimeOriginal ) {
					deferred.resolve( moment( metadata.exif.DateTimeOriginal, 'YYYY:MM:DD' ).format( 'YYYY-MM-DD' ) );
				} else {
					deferred.reject();
				}
			};

			if ( 'readAsBinaryString' in fileReader ) {
				fileReader.readAsBinaryString( file );
			} else if ( 'readAsArrayBuffer' in fileReader ) {
				fileReader.readAsArrayBuffer( file );
			} else {
				// We should never get here
				deferred.reject();
				throw new Error( 'Cannot read thumbnail as binary string or array buffer.' );
			}
		}

		return deferred.promise();
	};

	/**
	 * Get last modified date from file
	 *
	 * @param {Object} file
	 * @return {Object} Last modified date from file
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getDateFromLastModified = function ( file ) {
		if ( file && file.lastModified ) {
			return moment( file.lastModified ).format( 'YYYY-MM-DD' );
		}
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
