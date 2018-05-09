( function ( mw, $, OO ) {
	/**
	 * Used to represent an upload in progress on the frontend.
	 *
	 * This subclass will upload to a wiki using a structured metadata
	 * system similar to (or identical to) the one on Wikimedia Commons.
	 *
	 * See <https://commons.wikimedia.org/wiki/Commons:Structured_data> for
	 * a more detailed description of how that system works.
	 *
	 * **TODO: This currently only supports uploads under CC-BY-SA 4.0,
	 * and should really have support for more licenses.**
	 *
	 * @class mw.ForeignStructuredUpload
	 * @extends mw.ForeignUpload
	 *
	 * @constructor
	 * @param {string} [target]
	 * @param {Object} [apiconfig]
	 */
	function ForeignStructuredUpload( target, apiconfig ) {
		this.date = undefined;
		this.descriptions = [];
		this.categories = [];

		// Config for uploads to local wiki.
		// Can be overridden with foreign wiki config when #loadConfig is called.
		this.config = mw.config.get( 'wgUploadDialog' );

		mw.ForeignUpload.call( this, target, apiconfig );
	}

	OO.inheritClass( ForeignStructuredUpload, mw.ForeignUpload );

	/**
	 * Get the configuration for the form and filepage from the foreign wiki, if any, and use it for
	 * this upload.
	 *
	 * @return {jQuery.Promise} Promise returning config object
	 */
	ForeignStructuredUpload.prototype.loadConfig = function () {
		var deferred,
			upload = this;

		if ( this.configPromise ) {
			return this.configPromise;
		}

		if ( this.target === 'local' ) {
			deferred = $.Deferred();
			setTimeout( function () {
				// Resolve asynchronously, so that it's harder to accidentally write synchronous code that
				// will break for cross-wiki uploads
				deferred.resolve( upload.config );
			} );
			this.configPromise = deferred.promise();
		} else {
			this.configPromise = this.apiPromise.then( function ( api ) {
				// Get the config from the foreign wiki
				return api.get( {
					action: 'query',
					meta: 'siteinfo',
					siprop: 'uploaddialog',
					// For convenient true/false booleans
					formatversion: 2
				} ).then( function ( resp ) {
					// Foreign wiki might be running a pre-1.27 MediaWiki, without support for this
					if ( resp.query && resp.query.uploaddialog ) {
						upload.config = resp.query.uploaddialog;
						return upload.config;
					} else {
						return $.Deferred().reject( 'upload-foreign-cant-load-config' );
					}
				}, function () {
					return $.Deferred().reject( 'upload-foreign-cant-load-config' );
				} );
			} );
		}

		return this.configPromise;
	};

	/**
	 * Add categories to the upload.
	 *
	 * @param {string[]} categories Array of categories to which this upload will be added.
	 */
	ForeignStructuredUpload.prototype.addCategories = function ( categories ) {
		// The length of the array must be less than 10000.
		// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/push#Merging_two_arrays
		Array.prototype.push.apply( this.categories, categories );
	};

	/**
	 * Empty the list of categories for the upload.
	 */
	ForeignStructuredUpload.prototype.clearCategories = function () {
		this.categories = [];
	};

	/**
	 * Add a description to the upload.
	 *
	 * @param {string} language The language code for the description's language. Must have a template on the target wiki to work properly.
	 * @param {string} description The description of the file.
	 */
	ForeignStructuredUpload.prototype.addDescription = function ( language, description ) {
		this.descriptions.push( {
			language: language,
			text: description
		} );
	};

	/**
	 * Empty the list of descriptions for the upload.
	 */
	ForeignStructuredUpload.prototype.clearDescriptions = function () {
		this.descriptions = [];
	};

	/**
	 * Set the date of creation for the upload.
	 *
	 * @param {Date} date
	 */
	ForeignStructuredUpload.prototype.setDate = function ( date ) {
		this.date = date;
	};

	/**
	 * Get the text of the file page, to be created on upload. Brings together
	 * several different pieces of information to create useful text.
	 *
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getText = function () {
		return this.config.format.filepage
			// Replace "named parameters" with the given information
			.replace( '$DESCRIPTION', this.getDescriptions() )
			.replace( '$DATE', this.getDate() )
			.replace( '$SOURCE', this.getSource() )
			.replace( '$AUTHOR', this.getUser() )
			.replace( '$LICENSE', this.getLicense() )
			.replace( '$CATEGORIES', this.getCategories() );
	};

	/**
	 * @inheritdoc
	 */
	ForeignStructuredUpload.prototype.getComment = function () {
		var
			isLocal = this.target === 'local',
			comment = typeof this.config.comment === 'string' ?
				this.config.comment :
				this.config.comment[ isLocal ? 'local' : 'foreign' ];
		return comment
			.replace( '$PAGENAME', mw.config.get( 'wgPageName' ) )
			.replace( '$HOST', location.host );
	};

	/**
	 * Gets the wikitext for the creation date of this upload.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getDate = function () {
		if ( !this.date ) {
			return '';
		}

		return this.date.toString();
	};

	/**
	 * Fetches the wikitext for any descriptions that have been added
	 * to the upload.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getDescriptions = function () {
		var upload = this;
		return this.descriptions.map( function ( desc ) {
			return upload.config.format.description
				.replace( '$LANGUAGE', desc.language )
				.replace( '$TEXT', desc.text );
		} ).join( '\n' );
	};

	/**
	 * Fetches the wikitext for the categories to which the upload will
	 * be added.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getCategories = function () {
		if ( this.categories.length === 0 ) {
			return this.config.format.uncategorized;
		}

		return this.categories.map( function ( cat ) {
			return '[[Category:' + cat + ']]';
		} ).join( '\n' );
	};

	/**
	 * Gets the wikitext for the license of the upload.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getLicense = function () {
		return this.config.format.license;
	};

	/**
	 * Get the source. This should be some sort of localised text for "Own work".
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getSource = function () {
		return this.config.format.ownwork;
	};

	/**
	 * Get the username.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getUser = function () {
		var username, namespace;
		// Do not localise, we don't know the language of target wiki
		namespace = 'User';
		username = mw.config.get( 'wgUserName' );
		if ( !username ) {
			// The user is not logged in locally. However, they might be logged in on the foreign wiki.
			// We should record their username there. (If they're not logged in there either, this will
			// record the IP address.) It's also possible that the user opened this dialog, got an error
			// about not being logged in, logged in in another browser tab, then continued uploading.
			username = '{{subst:REVISIONUSER}}';
		}
		return '[[' + namespace + ':' + username + '|' + username + ']]';
	};

	mw.ForeignStructuredUpload = ForeignStructuredUpload;
}( mediaWiki, jQuery, OO ) );
