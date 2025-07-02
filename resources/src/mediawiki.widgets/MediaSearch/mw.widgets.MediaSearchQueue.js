/*!
 * MediaWiki Widgets - MediaSearchQueue class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Media resource queue.
	 *
	 * @class
	 * @extends mw.widgets.MediaResourceQueue
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.MediaSearchQueue`.
	 * @param {Object} [config] Configuration options
	 * @param {number} config.maxHeight The maximum height of the media, used in the
	 *  search call to the API.
	 */
	mw.widgets.MediaSearchQueue = function MwWidgetsMediaSearchQueue( config = {} ) {
		// Parent constructor
		mw.widgets.MediaSearchQueue.super.call( this, config );
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaSearchQueue, mw.widgets.MediaResourceQueue );

	/**
	 * Override parent method to set up the providers according to
	 * the file repos.
	 *
	 * @return {jQuery.Promise} Promise that resolves when the resources are set up
	 */
	mw.widgets.MediaSearchQueue.prototype.setup = function () {
		return this.getFileRepos().then( ( sources ) => {
			if ( this.providers.length === 0 ) {
				// Set up the providers
				for ( let i = 0, len = sources.length; i < len; i++ ) {
					this.addProvider( new mw.widgets.MediaSearchProvider(
						sources[ i ].apiurl,
						{
							name: sources[ i ].name,
							local: sources[ i ].local,
							scriptDirUrl: sources[ i ].scriptDirUrl,
							userParams: {
								gsrsearch: this.getSearchQuery()
							},
							staticParams: {
								iiurlheight: this.getMaxHeight()
							}
						} )
					);
				}
			}
		} );
	};

	/**
	 * Set the search query.
	 *
	 * @param {string} searchQuery API search query
	 */
	mw.widgets.MediaSearchQueue.prototype.setSearchQuery = function ( searchQuery ) {
		this.setParams( { gsrsearch: searchQuery } );
	};

	/**
	 * Get the search query.
	 *
	 * @return {string} API search query
	 */
	mw.widgets.MediaSearchQueue.prototype.getSearchQuery = function () {
		return this.getParams().gsrsearch;
	};
}() );
