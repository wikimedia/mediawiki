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
		this.date = undefined;
		this.descriptions = [];
		this.categories = [];

		mw.ForeignUpload.call( this, target, apiconfig );
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
		return (
			'{{' +
			this.getTemplateName() +
			'\n|description=' +
			this.getDescriptions() +
			'\n|date=' +
			this.getDate() +
			'\n|source=' +
			this.getSource() +
			'\n|author=' +
			this.getUser() +
			'\n}}\n\n' +
			this.getLicense() +
			'\n\n' +
			this.getCategories()
		);
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
		var i, desc, templateCalls = [];

		for ( i = 0; i < this.descriptions.length; i++ ) {
			desc = this.descriptions[ i ];
			templateCalls.push( '{{' + desc.language + '|' + desc.text + '}}' );
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
		return '{{own}}';
	};

	/**
	 * Get the username.
	 *
	 * @private
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getUser = function () {
		return mw.config.get( 'wgUserName' );
	};

	mw.ForeignStructuredUpload = ForeignStructuredUpload;
}( mediaWiki, OO ) );
