/*!
 * MediaWiki Widgets - MediaSearchQueue class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * MediaWiki media resource queue.
	 *
	 * @class
	 * @extends mw.widgets.MediaResourceQueue
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} maxHeight The maximum height of the media, used in the
	 *  search call to the API.
	 */
	mw.widgets.MediaSearchQueue = function MwWidgetsMediaSearchQueue( config ) {
		config = config || {};

		// Parent constructor
		mw.widgets.MediaSearchQueue.super.call( this, config );

		this.searchQuery = '';
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaSearchQueue, mw.widgets.MediaResourceQueue );

	/**
	 * Override parent method to set up the providers according to
	 * the file repos
	 *
	 * @return {jQuery.Promise} Promise that resolves when the resources are set up
	 */
	mw.widgets.MediaSearchQueue.prototype.setup = function () {
		var i, len,
			queue = this;

		return this.getFileRepos().then( function ( sources ) {
			if ( queue.providers.length === 0 ) {
				// Set up the providers
				for ( i = 0, len = sources.length; i < len; i++ ) {
					queue.providers.push( new mw.widgets.MediaSearchProvider(
						sources[ i ].apiurl,
						{
							name: sources[ i ].name,
							local: sources[ i ].local,
							scriptDirUrl: sources[ i ].scriptDirUrl,
							userParams: {
								gsrsearch: queue.getSearchQuery()
							},
							staticParams: {
								iiurlheight: queue.getMaxHeight()
							}
						} )
					);
				}
			}
		} );
	};

	/**
	 * Set the search query
	 *
	 * @param {string} searchQuery API search query
	 */
	mw.widgets.MediaSearchQueue.prototype.setSearchQuery = function ( searchQuery ) {
		this.setParams( { gsrsearch: searchQuery } );
	};

	/**
	 * Get the search query
	 *
	 * @return {string} API search query
	 */
	mw.widgets.MediaSearchQueue.prototype.getSearchQuery = function () {
		return this.getParams().gsrsearch;
	};
}() );
