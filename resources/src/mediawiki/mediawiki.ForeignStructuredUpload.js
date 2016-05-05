( function ( mw, $, OO ) {
	/**
	 * @class mw.ForeignStructuredUpload
	 * @extends mw.ForeignUpload
	 *
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
	 * @inheritdoc
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
					}
					return upload.config;
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
		var i, category;

		for ( i = 0; i < categories.length; i++ ) {
			category = categories[ i ];
			this.categories.push( category );
		}
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
			// Replace "numbered parameters" with the given information
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
		return this.config.comment
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
		var i, desc, templateCalls = [];

		for ( i = 0; i < this.descriptions.length; i++ ) {
			desc = this.descriptions[ i ];
			templateCalls.push(
				this.config.format.description
					.replace( '$LANGUAGE', desc.language )
					.replace( '$TEXT', desc.text )
			);
		}

		return templateCalls.join( '\n' );
	};

	/**
	 * Fetches the wikitext for the categories to which the upload will
	 * be added.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getCategories = function () {
		var i, cat, categoryLinks = [];

		if ( this.categories.length === 0 ) {
			return this.config.format.uncategorized;
		}

		for ( i = 0; i < this.categories.length; i++ ) {
			cat = this.categories[ i ];
			categoryLinks.push( '[[Category:' + cat + ']]' );
		}

		return categoryLinks.join( '\n' );
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
