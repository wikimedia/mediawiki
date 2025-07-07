( function () {

	/**
	 * @classdesc Encapsulates the process of uploading a file to MediaWiki
	 * using the {@link mw.ForeignStructuredUpload} model.
	 *
	 * @example
	 * var uploadDialog = new mw.Upload.Dialog( {
	 *     bookletClass: mw.ForeignStructuredUpload.BookletLayout,
	 *     booklet: {
	 *         target: 'local'
	 *     }
	 * } );
	 * var windowManager = new OO.ui.WindowManager();
	 * $( document.body ).append( windowManager.$element );
	 * windowManager.addWindows( [ uploadDialog ] );
	 *
	 * @class mw.ForeignStructuredUpload.BookletLayout
	 * @extends mw.Upload.BookletLayout
	 *
	 * @constructor
	 * @description Create an instance of `mw.ForeignStructuredUpload.BookletLayout`.
	 * @param {Object} config Configuration options
	 * @param {string} [config.target] Used to choose the target repository.
	 *     If nothing is passed, the {@link mw.ForeignUpload#property-target default} is used.
	 */
	mw.ForeignStructuredUpload.BookletLayout = function ( config ) {
		config = config || {};
		// Parent constructor
		mw.ForeignStructuredUpload.BookletLayout.super.call( this, config );

		this.target = config.target;
	};

	/* Setup */

	OO.inheritClass( mw.ForeignStructuredUpload.BookletLayout, mw.Upload.BookletLayout );

	/* Uploading */

	/**
	 * @inheritdoc
	 * @ignore
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.initialize = function () {
		return mw.ForeignStructuredUpload.BookletLayout.super.prototype.initialize.call( this ).then(
			() => $.when(
				// Point the CategoryMultiselectWidget to the right wiki
				this.upload.getApi().then( ( api ) => {
					// If this is a ForeignApi, it will have a apiUrl, otherwise we don't need to do anything
					if ( api.apiUrl ) {
						// Can't reuse the same object, CategoryMultiselectWidget calls #abort on its mw.Api instance
						this.categoriesWidget.api = new mw.ForeignApi( api.apiUrl );
					}
					return $.Deferred().resolve();
				} ),
				// Set up booklet fields and license messages to match configuration
				this.upload.loadConfig().then( ( config ) => {
					const isLocal = this.upload.target === 'local',
						fields = config.fields,
						msgs = config.licensemessages[ isLocal ? 'local' : 'foreign' ];

					// Hide disabled fields
					this.descriptionField.toggle( !!fields.description );
					this.categoriesField.toggle( !!fields.categories );
					this.dateField.toggle( !!fields.date );
					// Update form validity
					this.onInfoFormChange();

					let msgPromise;
					// Load license messages from the remote wiki if we don't have these messages locally
					// (this means that we only load messages from the foreign wiki for custom config)
					// These messages are documented where msgPromise resolves
					if ( mw.message( 'upload-form-label-own-work-message-' + msgs ).exists() ) {
						msgPromise = $.Deferred().resolve();
					} else {
						msgPromise = this.upload.apiPromise.then( ( api ) => api.loadMessages( [
							// These messages are documented where msgPromise resolves
							'upload-form-label-own-work-message-' + msgs,
							'upload-form-label-not-own-work-message-' + msgs,
							'upload-form-label-not-own-work-local-' + msgs
						] ) );
					}

					// Update license messages
					return msgPromise.then( () => {
						// The following messages are used here:
						// * upload-form-label-own-work-message-generic-local
						// * upload-form-label-own-work-message-generic-foreign
						this.$ownWorkMessage.msg( 'upload-form-label-own-work-message-' + msgs );
						// * upload-form-label-not-own-work-message-generic-local
						// * upload-form-label-not-own-work-message-generic-foreign
						this.$notOwnWorkMessage.msg( 'upload-form-label-not-own-work-message-' + msgs );
						// * upload-form-label-not-own-work-local-generic-local
						// * upload-form-label-not-own-work-local-generic-foreign
						this.$notOwnWorkLocal.msg( 'upload-form-label-not-own-work-local-' + msgs );

						const $labels = $( [
							this.$ownWorkMessage[ 0 ],
							this.$notOwnWorkMessage[ 0 ],
							this.$notOwnWorkLocal[ 0 ]
						] );

						// Improve the behavior of links inside these labels, which may point to important
						// things like licensing requirements or terms of use
						$labels.find( 'a' )
							.attr( 'target', '_blank' )
							.on( 'click', ( e ) => {
								// OO.ui.FieldLayout#onLabelClick is trying to prevent default on all clicks,
								// which causes the links to not be openable. Don't let it do that.
								e.stopPropagation();
							} );
					} );
				}, ( errorMsg ) => {
					// eslint-disable-next-line mediawiki/msg-doc
					this.getPage( 'upload' ).$element.msg( errorMsg );
					return $.Deferred().resolve();
				} )
			)
		).catch(
			// Always resolve, never reject
			() => $.Deferred().resolve()
		);
	};

	/**
	 * Returns a {@link mw.ForeignStructuredUpload mw.ForeignStructuredUpload}
	 * with the `target` specified in config.
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
		// These elements are filled with text in #initialize
		// TODO Refactor this to be in one place
		this.$ownWorkMessage = $( '<p>' );
		this.$notOwnWorkMessage = $( '<p>' );
		this.$notOwnWorkLocal = $( '<p>' );

		this.selectFileWidget = new OO.ui.SelectFileInputWidget( {
			showDropTarget: true
		} );
		this.messageLabel = new OO.ui.LabelWidget( {
			label: $( '<div>' ).append(
				this.$notOwnWorkMessage,
				this.$notOwnWorkLocal
			)
		} );
		this.ownWorkCheckbox = new OO.ui.CheckboxInputWidget().on( 'change', ( on ) => {
			this.messageLabel.toggle( !on );
		} );

		const fieldset = new OO.ui.FieldsetLayout();
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

		this.selectFileWidget.on( 'change', () => {
			const file = this.getFile();

			// Set the date to lastModified once we have the file
			if ( this.getDateFromLastModified( file ) !== undefined ) {
				this.dateWidget.setValue( this.getDateFromLastModified( file ) );
			}

			// Check if we have EXIF data and set to that where available
			this.getDateFromExif( file ).done( ( date ) => {
				this.dateWidget.setValue( date );
			} );

			this.updateFilePreview();
		} );

		return this.uploadForm;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onUploadFormChange = function () {
		const file = this.selectFileWidget.getValue(),
			ownWork = this.ownWorkCheckbox.isSelected(),
			valid = !!file && ownWork;
		this.emit( 'uploadValid', valid );
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.renderInfoForm = function () {
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

		const date = new Date();
		date.setDate( date.getDate() + 1 ); // Tomorrow
		const mustBeBefore = this.isoFormat( date );
		this.dateWidget = new mw.widgets.DateInputWidget( {
			$overlay: this.$overlay,
			required: true,
			mustBeBefore
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

		const fieldset = new OO.ui.FieldsetLayout( {
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

		this.on( 'fileUploadProgress', ( progress ) => {
			this.progressBarWidget.setProgress( progress * 100 );
		} );

		return this.infoForm;
	};

	/**
	 * Format date as YYYY-MM-DD
	 *
	 * @param {Date} date
	 * @return {string} date as YYYY-MM-DD
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.isoFormat = function ( date ) {
		const year = date.getFullYear().toString();
		const month = ( date.getMonth() + 1 ).toString().padStart( 2, '0' );
		const day = date.getDate().toString().padStart( 2, '0' );

		return `${ year }-${ month }-${ day }`;
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.onInfoFormChange = function () {
		const validityPromises = [];

		validityPromises.push( this.filenameWidget.getValidity() );
		if ( this.descriptionField.isVisible() ) {
			validityPromises.push( this.descriptionWidget.getValidity() );
		}
		if ( this.dateField.isVisible() ) {
			validityPromises.push( this.dateWidget.getValidity() );
		}

		$.when( ...validityPromises ).done( () => {
			this.emit( 'infoValid', true );
		} ).fail( () => {
			this.emit( 'infoValid', false );
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
			( result ) => {
				// if the file already exists, reject right away, before
				// ever firing finishStashUpload()
				if ( !result.query.pages[ 0 ].missing ) {
					return $.Deferred().reject( new OO.ui.Error(
						$( '<p>' ).msg( 'fileexists', filename.getPrefixedDb() ),
						{ recoverable: false }
					) );
				}
			},
			// API call failed - this could be a connection hiccup...
			// Let's just ignore this validation step and turn this
			// failure into a successful resolve ;)
			() => $.Deferred().resolve()
		);
	};

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.saveFile = function () {
		const title = mw.Title.newFromText(
			this.getFilename(),
			mw.config.get( 'wgNamespaceIds' ).file
		);

		return this.uploadPromise
			.then( this.validateFilename.bind( this, title ) )
			.then( mw.ForeignStructuredUpload.BookletLayout.super.prototype.saveFile.bind( this ) );
	};

	/* Getters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getText = function () {
		const language = mw.config.get( 'wgContentLanguage' ),
			categories = this.categoriesWidget.getItems().map( ( item ) => item.data );
		this.upload.clearDescriptions();
		this.upload.addDescription( language, this.descriptionWidget.getValue() );
		this.upload.setDate( this.dateWidget.getValue() );
		this.upload.clearCategories();
		this.upload.addCategories( categories );
		return this.upload.getText();
	};

	/**
	 * Get original date from EXIF data.
	 *
	 * @param {File} file
	 * @return {jQuery.Promise} Promise resolved with the EXIF date
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getDateFromExif = function ( file ) {
		const deferred = $.Deferred();

		if ( file && file.type === 'image/jpeg' ) {
			const fileReader = new FileReader();
			fileReader.onload = function () {
				const jpegmeta = require( 'mediawiki.libs.jpegmeta' );

				let fileStr;
				if ( typeof fileReader.result === 'string' ) {
					fileStr = fileReader.result;
				} else {
					// Array buffer; convert to binary string for the library.
					const arr = new Uint8Array( fileReader.result );
					fileStr = '';
					for ( let i = 0; i < arr.byteLength; i++ ) {
						fileStr += String.fromCharCode( arr[ i ] );
					}
				}

				let metadata;
				try {
					metadata = jpegmeta( fileStr, file.name );
				} catch ( e ) {
					metadata = null;
				}

				if ( metadata && metadata.exif && typeof metadata.exif.DateTimeOriginal === 'string' ) {
					const match = metadata.exif.DateTimeOriginal.match( /^\d\d\d\d:\d\d:\d\d/ ); // YYYY:MM:DD
					if ( match ) {
						deferred.resolve( match[ 0 ].replace( /:/g, '-' ) ); // YYYY-MM-DD
						return;
					}
				}
				deferred.reject();
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
	 * Get last modified date from file.
	 *
	 * @param {File} file
	 * @return {string|undefined} Last modified date from file
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.getDateFromLastModified = function ( file ) {
		if ( file && file.lastModified ) {
			return this.isoFormat( new Date( file.lastModified ) );
		}
	};

	/* Setters */

	/**
	 * @inheritdoc
	 */
	mw.ForeignStructuredUpload.BookletLayout.prototype.clear = function () {
		mw.ForeignStructuredUpload.BookletLayout.super.prototype.clear.call( this );

		this.ownWorkCheckbox.setSelected( false );
		this.categoriesWidget.setValue( [] );
		this.dateWidget.setValue( '' ).setValidityFlag( true );
	};

}() );
