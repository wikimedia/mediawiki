/* global moment, Uint8Array */
( function () {

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
	 *     $( document.body ).append( windowManager.$element );
	 *     windowManager.addWindows( [ uploadDialog ] );
	 *
	 * @class mw.ForeignStructuredUpload.BookletLayout
	 * @uses mw.ForeignStructuredUpload
	 * @extends mw.Upload.BookletLayout
	 *
	 * @constructor
	 * @param {Object} config Configuration options
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
					// Point the CategoryMultiselectWidget to the right wiki
					booklet.upload.getApi().then( function ( api ) {
						// If this is a ForeignApi, it will have a apiUrl, otherwise we don't need to do anything
						if ( api.apiUrl ) {
							// Can't reuse the same object, CategoryMultiselectWidget calls #abort on its mw.Api instance
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
						// These messages are documented where msgPromise resolves
						if ( mw.message( 'upload-form-label-own-work-message-' + msgs ).exists() ) {
							msgPromise = $.Deferred().resolve();
						} else {
							msgPromise = booklet.upload.apiPromise.then( function ( api ) {
								return api.loadMessages( [
									// These messages are documented where msgPromise resolves
									'upload-form-label-own-work-message-' + msgs,
									'upload-form-label-not-own-work-message-' + msgs,
									'upload-form-label-not-own-work-local-' + msgs
								] );
							} );
						}

						// Update license messages
						return msgPromise.then( function () {
							var $labels;
							// The following messages are used here:
							// * upload-form-label-own-work-message-generic-local
							// * upload-form-label-own-work-message-generic-foreign
							booklet.$ownWorkMessage.msg( 'upload-form-label-own-work-message-' + msgs );
							// * upload-form-label-not-own-work-message-generic-local
							// * upload-form-label-not-own-work-message-generic-foreign
							booklet.$notOwnWorkMessage.msg( 'upload-form-label-not-own-work-message-' + msgs );
							// * upload-form-label-not-own-work-local-generic-local
							// * upload-form-label-not-own-work-local-generic-foreign
							booklet.$notOwnWorkLocal.msg( 'upload-form-label-not-own-work-local-' + msgs );

							$labels = $( [
								booklet.$ownWorkMessage[ 0 ],
								booklet.$notOwnWorkMessage[ 0 ],
								booklet.$notOwnWorkLocal[ 0 ]
							] );

							// Improve the behavior of links inside these labels, which may point to important
							// things like licensing requirements or terms of use
							$labels.find( 'a' )
								.attr( 'target', '_blank' )
								.on( 'click', function ( e ) {
									// OO.ui.FieldLayout#onLabelClick is trying to prevent default on all clicks,
									// which causes the links to not be openable. Don't let it do that.
									e.stopPropagation();
								} );
						} );
					}, function ( errorMsg ) {
						// eslint-disable-next-line mediawiki/msg-doc
						booklet.getPage( 'upload' ).$element.msg( errorMsg );
						return $.Deferred().resolve();
					} )
				);
			}
		).catch(
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
		return new mw.ForeignStructuredUpload( this.target, {
			parameters: {
				errorformat: 'html',
				errorlang: mw.config.get( 'wgUserLanguage' ),
				errorsuselocal: 1,
				formatversion: 2
			}
		} );
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
		this.$ownWorkMessage = $( '<p>' );
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
				label: mw.msg( 'upload-form-label-own-work' ),
				help: this.$ownWorkMessage,
				helpInline: true
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
		this.descriptionWidget = new OO.ui.MultilineTextInputWidget( {
			required: true,
			validate: /\S+/,
			autosize: true
		} );
		this.categoriesWidget = new mw.widgets.CategoryMultiselectWidget( {
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
			help: mw.msg( 'upload-form-label-infoform-name-tooltip' ),
			helpInline: true
		} );
		this.descriptionField = new OO.ui.FieldLayout( this.descriptionWidget, {
			label: mw.msg( 'upload-form-label-infoform-description' ),
			align: 'top',
			help: mw.msg( 'upload-form-label-infoform-description-tooltip' ),
			helpInline: true
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

	/**
	 * @param {mw.Title} filename
	 * @return {jQuery.Promise} Resolves (on success) or rejects with OO.ui.Error
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.validateFilename = function ( filename ) {
		return ( new mw.Api() ).get( {
			action: 'query',
			prop: 'info',
			titles: filename.getPrefixedDb(),
			formatversion: 2
		} ).then(
			function ( result ) {
				// if the file already exists, reject right away, before
				// ever firing finishStashUpload()
				if ( !result.query.pages[ 0 ].missing ) {
					return $.Deferred().reject( new OO.ui.Error(
						$( '<p>' ).msg( 'fileexists', filename.getPrefixedDb() ),
						{ recoverable: false }
					) );
				}
			},
			function () {
				// API call failed - this could be a connection hiccup...
				// Let's just ignore this validation step and turn this
				// failure into a successful resolve ;)
				return $.Deferred().resolve();
			}
		);
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.saveFile = function () {
		var title = mw.Title.newFromText(
			this.getFilename(),
			mw.config.get( 'wgNamespaceIds' ).file
		);

		return this.uploadPromise
			.then( this.validateFilename.bind( this, title ) )
			.then( mw.ForeignStructuredUpload.BookletLayout.parent.prototype.saveFile.bind( this ) );
	};

	/* Getters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getText = function () {
		var language = mw.config.get( 'wgContentLanguage' ),
			categories = this.categoriesWidget.getItems().map( function ( item ) {
				return item.data;
			} );
		this.upload.clearDescriptions();
		this.upload.addDescription( language, this.descriptionWidget.getValue() );
		this.upload.setDate( this.dateWidget.getValue() );
		this.upload.clearCategories();
		this.upload.addCategories( categories );
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
				var fileStr, arr, i, metadata,
					jpegmeta = require( 'mediawiki.libs.jpegmeta' );

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
					metadata = jpegmeta( fileStr, file.name );
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
		this.categoriesWidget.setValue( [] );
		this.dateWidget.setValue( '' ).setValidityFlag( true );
	};

}() );
