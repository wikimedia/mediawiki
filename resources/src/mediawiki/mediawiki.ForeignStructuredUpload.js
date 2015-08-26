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
	 * See https://commons.wikimedia.org/wiki/Commons:Structured_data for
	 * a more detailed description of how that system works.
	 *
	 * @TODO this currently only supports uploads under CC-BY-SA 4.0,
	 *     and should really have support for more licenses.
	 *
	 * @constructor
	 * @param {string} [targetHost="commons.wikimedia.org"] Used to set up the target
	 *     wiki. If not remote, this class behaves identically to mw.Upload (unless further subclassed)
	 * @param {Object} [apiconfig] Passed to the constructor of mw.ForeignApi or mw.Api, as needed.
	 */
	function ForeignStructuredUpload( targetHost, apiconfig ) {
		this.date = undefined;
		this.descriptions = [];
		this.categories = [];

		mw.ForeignUpload.call( this, targetHost, apiconfig );
	}

	OO.inheritClass( ForeignStructuredUpload, mw.ForeignUpload );

	/**
	 * Add categories to the upload.
	 * @param {string[]} categories Array of categories to which this upload will be added.
	 */
	ForeignStructuredUpload.prototype.addCategories = function ( categories ) {
		var i, category;

		for ( i = 0; i < categories.length; i++ ) {
			category = categories[i];
			this.categories.push( category );
		}
	};

	/**
	 * Add a description to the upload.
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
	 * @param {Date} date
	 */
	ForeignStructuredUpload.prototype.setDate = function ( date ) {
		this.date = date;
	};

	/**
	 * Get the text of the file page, to be created on upload. Brings together
	 * several different pieces of information to create useful text.
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getText = function () {
		return [
			'{{',
			this.getTemplateName(),
			'|description=',
			this.getDescriptions(),
			'|date=',
			this.getDate(),
			'|source=',
			this.getUser(),
			'|author=',
			this.getUser(),
			'}}\n\n',
			this.getLicense(),
			'\n\n',
			this.getCategories()
		].join( '' );
	};

	/**
	 * Gets the wikitext for the creation date of this upload.
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
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getTemplateName = function () {
		return 'Information';
	};

	/**
	 * Fetches the wikitext for any descriptions that have been added
	 * to the upload.
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getDescriptions = function () {
		var i, desc,
			desctext = '';

		for ( i = 0; i < this.descriptions.length; i++ ) {
			desc = this.descriptions[i];
			desctext += '{{' + desc.language + '|' + desc.text + '}}';
		}

		return desctext;
	};

	/**
	 * Fetches the wikitext for the categories to which the upload will
	 * be added.
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getCategories = function () {
		var i, cat,
			cattext = '';

		for ( i = 0; i < this.categories.length; i++ ) {
			cat = this.categories[i];
			cattext += '[[Category:' + cat + ']]';
		}

		return cattext;
	};

	/**
	 * Gets the wikitext for the license of the upload. Abstract for now.
	 * @abstract
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getLicense = function () {
		return '';
	};

	/**
	 * Get the username.
	 * @return {string}
	 */
	ForeignStructuredUpload.prototype.getUser = function () {
		return mw.user.getName();
	};

	mw.ForeignStructuredUpload = ForeignStructuredUpload;
}( mediaWiki, OO ) );
