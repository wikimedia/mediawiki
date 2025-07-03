/*!
 * MediaWiki Widgets - MediaResourceQueue class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Media resource queue.
	 *
	 * @class
	 * @extends mw.widgets.APIResultsQueue
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.MediaResourceQueue`.
	 * @param {Object} [config] Configuration options
	 * @param {number} config.maxHeight The maximum height of the media, used in the
	 *  search call to the API.
	 */
	mw.widgets.MediaResourceQueue = function MwWidgetsMediaResourceQueue( config = {} ) {
		// Parent constructor
		mw.widgets.MediaResourceQueue.super.call( this, config );

		this.maxHeight = config.maxHeight || 200;
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaResourceQueue, mw.widgets.APIResultsQueue );

	/**
	 * Fetch the file repos.
	 *
	 * @return {jQuery.Promise} Promise that resolves when the resources are set up
	 */
	mw.widgets.MediaResourceQueue.prototype.getFileRepos = function () {
		const defaultSource = [ {
			url: mw.util.wikiScript( 'api' ),
			local: ''
		} ];

		if ( !this.fileRepoPromise ) {
			this.fileRepoPromise = new mw.Api().get( {
				action: 'query',
				meta: 'filerepoinfo'
			} ).then(
				( resp ) => resp.query && resp.query.repos || defaultSource,
				() => $.Deferred().resolve( defaultSource )
			);
		}

		return this.fileRepoPromise;
	};

	/**
	 * Get image maximum height.
	 *
	 * @return {string} Image max height
	 */
	mw.widgets.MediaResourceQueue.prototype.getMaxHeight = function () {
		return this.maxHeight;
	};
}() );
