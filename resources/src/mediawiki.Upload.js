( function () {
	var UP;

	/**
	 * @classdesc Upload to a wiki. Most of the functionality is implemented
	 * in {@link mw.Api#upload} and friends, but this model class will tie it
	 * together as well as let you perform actions in a logical way.
	 *
	 * A simple example:
	 * ```
	 * var file = new OO.ui.SelectFileWidget(),
	 *   button = new OO.ui.ButtonWidget( { label: 'Save' } ),
	 *   upload = new mw.Upload;
	 *
	 * button.on( 'click', function () {
	 *   upload.setFile( file.getValue() );
	 *   upload.setFilename( file.getValue().name );
	 *   upload.upload();
	 * } );
	 *
	 * $( document.body ).append( file.$element, button.$element );
	 * ```
	 * You can also choose to {@link mw.Upload#uploadToStash stash the upload}
	 * and {@link mw.Upload#finishStashUpload finalize} it later:
	 * ```
	 * var file, // Some file object
	 *   upload = new mw.Upload,
	 *   stashPromise = $.Deferred();
	 *
	 * upload.setFile( file );
	 * upload.uploadToStash().then( function () {
	 *   stashPromise.resolve();
	 * } );
	 *
	 * stashPromise.then( function () {
	 *   upload.setFilename( 'foo' );
	 *   upload.setText( 'bar' );
	 *   upload.finishStashUpload().then( function () {
	 *     console.log( 'Done!' );
	 *   } );
	 * } );
	 * ```
	 * @class mw.Upload
	 *
	 * @constructor
	 * @description Used to represent an upload in progress on the frontend.
	 * @param {Object|mw.Api} [apiconfig] A mw.Api object (or subclass), or configuration
	 *     to pass to the constructor of mw.Api.
	 */
	function Upload( apiconfig ) {
		this.api = ( apiconfig instanceof mw.Api ) ? apiconfig : new mw.Api( apiconfig );

		this.watchlist = false;
		this.text = '';
		this.comment = '';
		this.filename = null;
		this.file = null;
		this.setState( Upload.State.NEW );

		this.imageinfo = undefined;
	}

	UP = Upload.prototype;

	/**
	 * Get the mw.Api instance used by this Upload object.
	 *
	 * @name mw.Upload.prototype.getApi
	 * @method
	 * @return {jQuery.Promise<mw.Api>}
	 */
	UP.getApi = function () {
		return $.Deferred().resolve( this.api ).promise();
	};

	/**
	 * Set the text of the file page, to be created on file upload.
	 *
	 * @name mw.Upload.prototype.setText
	 * @method
	 * @param {string} text
	 */
	UP.setText = function ( text ) {
		this.text = text;
	};

	/**
	 * Set the filename, to be finalized on upload.
	 *
	 * @name mw.Upload.prototype.setFilename
	 * @method
	 * @param {string} filename
	 */
	UP.setFilename = function ( filename ) {
		this.filename = filename;
	};

	/**
	 * Set the stashed file to finish uploading.
	 *
	 * @name mw.Upload.prototype.setFilekey
	 * @method
	 * @param {string} filekey
	 */
	UP.setFilekey = function ( filekey ) {
		var upload = this;

		this.setState( Upload.State.STASHED );
		this.stashPromise = $.Deferred().resolve( ( data ) => upload.api.uploadFromStash( filekey, data ) );
	};

	/**
	 * Sets the filename based on the filename as it was on the upload.
	 *
	 * @name mw.Upload.prototype.setFilenameFromFile
	 * @method
	 */
	UP.setFilenameFromFile = function () {
		var file = this.getFile();
		if ( !file ) {
			return;
		}
		if ( file.nodeType && file.nodeType === Node.ELEMENT_NODE ) {
			// File input element, use getBasename to cut out the path
			this.setFilename( this.getBasename( file.value ) );
		} else if ( file.name ) {
			// HTML5 FileAPI File object, but use getBasename to be safe
			this.setFilename( this.getBasename( file.name ) );
		} else {
			// If we ever implement uploading files from clipboard, they might not have a name
			this.setFilename( '?' );
		}
	};

	/**
	 * Set the file to be uploaded.
	 *
	 * @name mw.Upload.prototype.setFile
	 * @method
	 * @param {HTMLInputElement|File|Blob} file
	 */
	UP.setFile = function ( file ) {
		this.file = file;
	};

	/**
	 * Set whether the file should be watchlisted after upload.
	 *
	 * @name mw.Upload.prototype.setWatchlist
	 * @method
	 * @param {boolean} watchlist
	 */
	UP.setWatchlist = function ( watchlist ) {
		this.watchlist = watchlist;
	};

	/**
	 * Set the edit comment for the upload.
	 *
	 * @name mw.Upload.prototype.setComment
	 * @method
	 * @param {string} comment
	 */
	UP.setComment = function ( comment ) {
		this.comment = comment;
	};

	/**
	 * Get the text of the file page, to be created on file upload.
	 *
	 * @name mw.Upload.prototype.getText
	 * @method
	 * @return {string}
	 */
	UP.getText = function () {
		return this.text;
	};

	/**
	 * Get the filename, to be finalized on upload.
	 *
	 * @name mw.Upload.prototype.getFilename
	 * @method
	 * @return {string}
	 */
	UP.getFilename = function () {
		return this.filename;
	};

	/**
	 * Get the file being uploaded.
	 *
	 * @name mw.Upload.prototype.getFile
	 * @method
	 * @return {HTMLInputElement|File|Blob}
	 */
	UP.getFile = function () {
		return this.file;
	};

	/**
	 * Get the boolean for whether the file will be watchlisted after upload.
	 *
	 * @name mw.Upload.prototype.getWatchlist
	 * @method
	 * @return {boolean}
	 */
	UP.getWatchlist = function () {
		return this.watchlist;
	};

	/**
	 * Get the current value of the edit comment for the upload.
	 *
	 * @name mw.Upload.prototype.getComment
	 * @method
	 * @return {string}
	 */
	UP.getComment = function () {
		return this.comment;
	};

	/**
	 * Gets the base filename from a path name.
	 *
	 * @name mw.Upload.prototype.getBasename
	 * @method
	 * @param {string} path
	 * @return {string}
	 */
	UP.getBasename = function ( path ) {
		if ( path === undefined || path === null ) {
			return '';
		}

		// Find the index of the last path separator in the
		// path, and add 1. Then, take the entire string after that.
		return path.slice(
			Math.max(
				path.lastIndexOf( '/' ),
				path.lastIndexOf( '\\' )
			) + 1
		);
	};

	/**
	 * Sets the state and state details (if any) of the upload.
	 *
	 * @name mw.Upload.prototype.setState
	 * @method
	 * @param {mw.Upload.State} state
	 * @param {Object} stateDetails
	 */
	UP.setState = function ( state, stateDetails ) {
		this.state = state;
		this.stateDetails = stateDetails;
	};

	/**
	 * Gets the state of the upload.
	 *
	 * @name mw.Upload.prototype.getState
	 * @method
	 * @return {mw.Upload.State}
	 */
	UP.getState = function () {
		return this.state;
	};

	/**
	 * Gets details of the current state.
	 *
	 * @name mw.Upload.prototype.getStateDetails
	 * @method
	 * @return {string}
	 */
	UP.getStateDetails = function () {
		return this.stateDetails;
	};

	/**
	 * Get the imageinfo object for the finished upload.
	 * Only available once the upload is finished! Don't try to get it
	 * beforehand.
	 *
	 * @name mw.Upload.prototype.getImageInfo
	 * @method
	 * @return {Object|undefined}
	 */
	UP.getImageInfo = function () {
		return this.imageinfo;
	};

	/**
	 * Upload the file directly.
	 *
	 * @name mw.Upload.prototype.upload
	 * @method
	 * @return {jQuery.Promise}
	 */
	UP.upload = function () {
		var upload = this;

		if ( !this.getFile() ) {
			return $.Deferred().reject( 'No file to upload. Call setFile to add one.' );
		}

		if ( !this.getFilename() ) {
			return $.Deferred().reject( 'No filename set. Call setFilename to add one.' );
		}

		this.setState( Upload.State.UPLOADING );

		return this.api.chunkedUpload( this.getFile(), {
			watchlist: ( this.getWatchlist() ) ? 1 : undefined,
			comment: this.getComment(),
			filename: this.getFilename(),
			text: this.getText()
		} ).then( ( result ) => {
			upload.setState( Upload.State.UPLOADED );
			upload.imageinfo = result.upload.imageinfo;
			return result;
		}, ( errorCode, result ) => {
			if ( result && result.upload && result.upload.warnings ) {
				upload.setState( Upload.State.WARNING, result );
			} else {
				upload.setState( Upload.State.ERROR, result );
			}
			return $.Deferred().reject( errorCode, result );
		} );
	};

	/**
	 * Upload the file to the stash to be completed later.
	 *
	 * @name mw.Upload.prototype.uploadToStash
	 * @method
	 * @return {jQuery.Promise}
	 */
	UP.uploadToStash = function () {
		var upload = this;

		if ( !this.getFile() ) {
			return $.Deferred().reject( 'No file to upload. Call setFile to add one.' );
		}

		if ( !this.getFilename() ) {
			this.setFilenameFromFile();
		}

		this.setState( Upload.State.UPLOADING );

		this.stashPromise = this.api.chunkedUploadToStash( this.getFile(), {
			ignorewarnings: true,
			filename: this.getFilename()
		} ).then( ( finishStash ) => {
			upload.setState( Upload.State.STASHED );
			return finishStash;
		}, ( errorCode, result ) => {
			if ( result && result.upload && result.upload.warnings ) {
				upload.setState( Upload.State.WARNING, result );
			} else {
				upload.setState( Upload.State.ERROR, result );
			}
			return $.Deferred().reject( errorCode, result );
		} );

		return this.stashPromise;
	};

	/**
	 * Finish a stash upload.
	 *
	 * @name mw.Upload.prototype.finishStashUpload
	 * @method
	 * @return {jQuery.Promise}
	 */
	UP.finishStashUpload = function () {
		var upload = this;

		if ( !this.stashPromise ) {
			return $.Deferred().reject( 'This upload has not been stashed, please upload it to the stash first.' );
		}

		return this.stashPromise.then( ( finishStash ) => {
			upload.setState( Upload.State.UPLOADING );

			return finishStash( {
				ignorewarnings: false,
				watchlist: ( upload.getWatchlist() ) ? 1 : undefined,
				comment: upload.getComment(),
				filename: upload.getFilename(),
				text: upload.getText()
			} ).then( ( result ) => {
				upload.setState( Upload.State.UPLOADED );
				upload.imageinfo = result.upload.imageinfo;
				return result;
			}, ( errorCode, result ) => {
				if ( result && result.upload && result.upload.warnings ) {
					upload.setState( Upload.State.WARNING, result );
				} else {
					upload.setState( Upload.State.ERROR, result );
				}
				return $.Deferred().reject( errorCode, result );
			} );
		} );
	};

	mw.Upload = Upload;

	/**
	 * @enum
	 *
	 * State of uploads represented in simple terms.
	 */
	mw.Upload.State = {
		/** Upload not yet started */
		NEW: 0,

		/** Upload finished, but there was a warning */
		WARNING: 1,

		/** Upload finished, but there was an error */
		ERROR: 2,

		/** Upload in progress */
		UPLOADING: 3,

		/** Upload finished, but not published, call #finishStashUpload */
		STASHED: 4,

		/** Upload finished and published */
		UPLOADED: 5
	};
}() );
