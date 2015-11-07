( function ( mw, OO ) {
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
		var crossWikiMsg;

		this.date = undefined;
		this.descriptions = [];
		this.categories = [];

		// parent constructor
		mw.ForeignUpload.call( this, target, apiconfig );

		if ( target !== 'local' ) {
			// preload information template message
			this.apiPromise = this.apiPromise.then( function ( api ) {
				api.loadMessages( [
					// Use this template, instead of a pre-defined Template:Information, if defined
					'upload-information-template',
					// Use this as the {{own}} template, instead of {{own}}, if defined
					'upload-information-own',
					// If this message exists, don't wrap the descriptions into language templates
					'upload-information-language',
					'upload-information-crosswikifrom'
				] );

				crossWikiMsg = mw.message( 'upload-information-crosswikifrom' );
				if ( crossWikiMsg.exists() ) {
					this.setComment( crossWikiMsg.replace( '$HOST', location.host ) );
				}

				return api;
			} );
		}
	}

	OO.inheritClass( ForeignStructuredUpload, mw.ForeignUpload );

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
		var infoMsg = mw.message( 'upload-information-template' );

		return infoMsg.plain()
			// replace "magic words" with the given information
			.replace( '$TEMPLATENAME', this.getTemplateName() )
			.replace( '$DESCRIPTION', this.getDescriptions() )
			.replace( '$DATE', this.getDate() )
			.replace( '$SOURCE', this.getSource() )
			.replace( '$AUTHOR', this.getUser() )
			.replace( '$LICENSE', this.getLicense() )
			.replace( '$CATEGORIES', this.getCategories() );
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
	 * Gets the name of the template to use for creating the file metadata.
	 * Override in subclasses for other templates.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getTemplateName = function () {
		return 'Information';
	};

	/**
	 * Fetches the wikitext for any descriptions that have been added
	 * to the upload.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getDescriptions = function () {
		var i, desc,
			templateCalls = [],
			useLang = mw.message( 'upload-information-language' ).plain() === '-';

		for ( i = 0; i < this.descriptions.length; i++ ) {
			desc = this.descriptions[ i ];
			if ( useLang ) {
				templateCalls.push( '{{' + desc.language + '|1=' + desc.text + '}}' );
			} else {
				templateCalls.push( desc.text );
			}
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
			return '{{subst:unc}}';
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
		// Make sure this matches the messages for different targets in
		// mw.ForeignStructuredUpload.BookletLayout.prototype.renderUploadForm
		return this.target === 'shared' ? '{{self|cc-by-sa-4.0}}' : '';
	};

	/**
	 * Get the source. This should be some sort of localised text for "Own work".
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getSource = function () {
		var msg = mw.message( 'upload-information-own' );
		if ( msg.plain() !== '-' ) {
			return msg.plain();
		} else {
			return '{{own}}';
		}
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
}( mediaWiki, OO ) );
