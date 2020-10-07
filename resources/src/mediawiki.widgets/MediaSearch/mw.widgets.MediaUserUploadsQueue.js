/*!
 * MediaWiki Widgets - MediaUserUploadsQueue class.
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
	mw.widgets.MediaUserUploadsQueue = function MwWidgetsMediaUserUploadsQueue( config ) {
		config = config || {};

		// Parent constructor
		mw.widgets.MediaUserUploadsQueue.super.call( this, config );

		if ( !mw.user.isAnon() ) {
			this.setUser( mw.user.getName() );
		}
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaUserUploadsQueue, mw.widgets.MediaResourceQueue );

	/**
	 * Override parent method to set up the providers according to
	 * the file repos
	 *
	 * @return {jQuery.Promise} Promise that resolves when the resources are set up
	 */
	mw.widgets.MediaUserUploadsQueue.prototype.setup = function () {
		var i, len,
			queue = this;

		return this.getFileRepos().then( function ( sources ) {
			if ( queue.providers.length === 0 ) {
				// Set up the providers
				for ( i = 0, len = sources.length; i < len; i++ ) {
					queue.addProvider( new mw.widgets.MediaUserUploadsProvider(
						sources[ i ].apiurl,
						{
							name: sources[ i ].name,
							local: sources[ i ].local,
							scriptDirUrl: sources[ i ].scriptDirUrl,
							userParams: {
								gaiuser: queue.getUser()
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
	 * Set the user nae
	 *
	 * @param {string} user User name
	 */
	mw.widgets.MediaUserUploadsQueue.prototype.setUser = function ( user ) {
		this.setParams( { gaiuser: user } );
	};

	/**
	 * Get the user name
	 *
	 * @return {string} API search query
	 */
	mw.widgets.MediaUserUploadsQueue.prototype.getUser = function () {
		return this.getParams().gaiuser;
	};
}() );
