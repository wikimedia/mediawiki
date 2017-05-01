/*!
 * MediaWiki Widgets - MediaResourceQueue class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * MediaWiki media resource queue.
	 *
	 * @class
	 * @extends mw.widgets.APIResultsQueue
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} maxHeight The maximum height of the media, used in the
	 *  search call to the API.
	 */
	mw.widgets.MediaResourceQueue = function MwWidgetsMediaResourceQueue( config ) {
		config = config || {};

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
		var defaultSource = [ {
			url: mw.util.wikiScript( 'api' ),
			local: ''
		} ];

		if ( !this.fileRepoPromise ) {
			this.fileRepoPromise = new mw.Api().get( {
				action: 'query',
				meta: 'filerepoinfo'
			} ).then(
				function ( resp ) {
					return resp.query && resp.query.repos || defaultSource;
				},
				function () {
					return $.Deferred().resolve( defaultSource );
				}
			);
		}

		return this.fileRepoPromise;
	};

	/**
	 * Get image maximum height
	 *
	 * @return {string} Image max height
	 */
	mw.widgets.MediaResourceQueue.prototype.getMaxHeight = function () {
		return this.maxHeight;
	};
}( jQuery, mediaWiki ) );
