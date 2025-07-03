/*!
 * MediaWiki Widgets - MediaUserUploadsQueue class.
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
	 * @description Create an instance of `mw.widgets.MediaUserUploadsQueue`.
	 * @param {Object} [config] Configuration options
	 * @param {number} config.maxHeight The maximum height of the media, used in the
	 *  search call to the API.
	 */
	mw.widgets.MediaUserUploadsQueue = function MwWidgetsMediaUserUploadsQueue( config = {} ) {
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
	 * the file repos.
	 *
	 * @return {jQuery.Promise} Promise that resolves when the resources are set up
	 */
	mw.widgets.MediaUserUploadsQueue.prototype.setup = function () {
		return this.getFileRepos().then( ( sources ) => {
			if ( this.providers.length === 0 ) {
				// Set up the providers
				for ( let i = 0, len = sources.length; i < len; i++ ) {
					this.addProvider( new mw.widgets.MediaUserUploadsProvider(
						sources[ i ].apiurl,
						{
							name: sources[ i ].name,
							local: sources[ i ].local,
							scriptDirUrl: sources[ i ].scriptDirUrl,
							userParams: {
								gaiuser: this.getUser()
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
	 * Set the user name.
	 *
	 * @param {string} user User name
	 */
	mw.widgets.MediaUserUploadsQueue.prototype.setUser = function ( user ) {
		this.setParams( { gaiuser: user } );
	};

	/**
	 * Get the user name.
	 *
	 * @return {string} API search query
	 */
	mw.widgets.MediaUserUploadsQueue.prototype.getUser = function () {
		return this.getParams().gaiuser;
	};
}() );
