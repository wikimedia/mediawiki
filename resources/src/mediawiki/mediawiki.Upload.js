( function ( mw, $ ) {
	var UP;

	/**
	 * @class mw.Upload
	 *
	 * Used to represent an upload in progress on the frontend.
	 * Most of the functionality is implemented in mw.Api.plugin.upload,
	 * but this model class will tie it together as well as let you perform
	 * actions in a logical way.
	 *
	 * @constructor
	 * @param {Object} apiconfig Passed to the constructor of mw.Api.
	 */
	function Upload( apiconfig ) {
		this.api = new mw.Api( apiconfig );

		this.watchlist = false;
		this.text = '';
		this.comment = '';
		this.filename = null;
		this.file = null;
		this.state = Upload.State.NEW;
	}

	UP = Upload.prototype;

	/**
	 * Set the text of the file page, to be created on file upload.
	 * @param {string} text
	 */
	UP.setText = function ( text ) {
		this.text = text;
	};

	/**
	 * Set the filename, to be finalized on upload.
	 * @param {string} filename
	 */
	UP.setFilename = function ( filename ) {
		this.filename = filename;
	};

	/**
	 * Sets the filename based on the filename as it was on the upload.
	 */
	UP.setFilenameFromFile = function () {
		if ( this.file.nodeType && this.file.nodeType === this.file.ELEMENT_NODE ) {
			// File input element, use getBasename to cut out the path
			this.setFilename( this.getBasename( this.file.value ) );
		} else if ( this.file.name && this.file.lastModified ) {
			// HTML5 FileAPI File object, but use getBasename to be safe
			this.setFilename( this.getBasename( this.file.name ) );
		}
	};

	/**
	 * Set the file to be uploaded.
	 * @param {HTMLInputElement|File} file
	 */
	UP.setFile = function ( file ) {
		this.file = file;
	};

	/**
	 * Set whether the file should be watchlisted after upload.
	 * @param {boolean} watchlist
	 */
	UP.setWatchlist = function ( watchlist ) {
		this.watchlist = watchlist;
	};

	/**
	 * Set the edit comment for the upload.
	 * @param {string} comment
	 */
	UP.setComment = function ( comment ) {
		this.comment = comment;
	};

	/**
	 * Get the text of the file page, to be created on file upload.
	 * @return {string}
	 */
	UP.getText = function () {
		return this.text;
	};

	/**
	 * Get the filename, to be finalized on upload.
	 * @return {string}
	 */
	UP.getFilename = function () {
		return this.filename;
	};

	/**
	 * Get the file being uploaded.
	 * @return {HTMLInputElement|File}
	 */
	UP.getFile = function () {
		return this.file;
	};

	/**
	 * Get the boolean for whether the file will be watchlisted after upload.
	 * @return {boolean}
	 */
	UP.getWatchlist = function () {
		return this.watchlist;
	};

	/**
	 * Get the current value of the edit comment for the upload.
	 * @return {string}
	 */
	UP.getComment = function () {
		return this.comment;
	};

	/**
	 * Gets the base filename from a path name.
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
	 * Gets the state of the upload.
	 * @return {mw.Upload.State}
	 */
	UP.getState = function () {
		return this.state;
	};

	/**
	 * Upload the file directly.
	 * @return {jQuery.Promise}
	 */
	UP.upload = function () {
		var upload = this;

		if ( !this.file ) {
			return $.Deferred().reject( 'No file to upload. Call setFile to add one.' );
		}

		if ( !this.filename ) {
			return $.Deferred().reject( 'No filename set. Call setFilename to add one.' );
		}

		this.state = Upload.State.UPLOADING;

		return this.api.upload( this.file, {
			watchlist: ( this.watchlist === true ) ? 1 : undefined,
			comment: this.comment,
			filename: this.filename,
			text: this.text
		} ).then( function () {
			upload.state = Upload.State.UPLOADED;
		}, function () {
			upload.state = Upload.State.ERROR;
		} );
	};

	/**
	 * Upload the file to the stash to be completed later.
	 * @return {jQuery.Promise}
	 */
	UP.uploadToStash = function () {
		var upload = this;

		if ( !this.file ) {
			return $.Deferred().reject( 'No file to upload. Call setFile to add one.' );
		}

		if ( !this.filename ) {
			this.setFilenameFromFile();
		}

		this.state = Upload.State.UPLOADING;

		this.stashPromise = this.api.uploadToStash( this.file, {
			filename: this.filename
		} ).then( function ( finishStash ) {
			upload.state = Upload.State.STASHED;
			return finishStash;
		}, function () {
			upload.state = Upload.State.ERROR;
		} );

		return this.stashPromise;
	};

	/**
	 * Finish a stash upload.
	 * @return {jQuery.Promise}
	 */
	UP.finishStashUpload = function () {
		var upload = this;

		if ( !this.stashPromise ) {
			return $.Deferred().reject( 'This upload has not been stashed, please upload it to the stash first.' );
		}

		return this.stashPromise.then( function ( finishStash ) {
			upload.state = Upload.State.UPLOADING;

			return finishStash( {
				watchlist: ( upload.watchlist === true ) ? 1 : undefined,
				comment: upload.getComment(),
				filename: upload.getFilename(),
				text: upload.getText()
			} ).then( function () {
				upload.state = Upload.State.UPLOADED;
			}, function () {
				upload.state = Upload.State.ERROR;
			} );
		} );
	};

	/**
	 * @enum mw.Upload.State
	 * State of uploads represented in simple terms.
	 */
	Upload.State = {
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

	mw.Upload = Upload;
}( mediaWiki, jQuery ) );
