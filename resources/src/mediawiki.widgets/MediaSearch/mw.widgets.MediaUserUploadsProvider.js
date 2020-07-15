/*!
 * MediaWiki Widgets - MediaUserUploadsProvider class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * MediaWiki media search provider.
	 *
	 * @class
	 * @extends mw.widgets.MediaResourceProvider
	 *
	 * @constructor
	 * @param {string} apiurl The API url
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.MediaUserUploadsProvider = function MwWidgetsMediaUserUploadsProvider( apiurl, config ) {
		config = config || {};

		config.staticParams = $.extend( {
			generator: 'allimages',
			gaisort: 'timestamp',
			gaidir: 'descending'
		}, config.staticParams );

		// Parent constructor
		mw.widgets.MediaUserUploadsProvider.super.call( this, apiurl, config );
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaUserUploadsProvider, mw.widgets.MediaResourceProvider );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaUserUploadsProvider.prototype.getContinueData = function ( howMany ) {
		return {
			gaicontinue: this.getOffset() || undefined,
			gailimit: howMany || this.getDefaultFetchLimit()
		};
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaUserUploadsProvider.prototype.setContinue = function ( continueData ) {
		// Update the offset for next time
		this.setOffset( continueData.gaicontinue );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaUserUploadsProvider.prototype.sort = function ( results ) {
		return results.sort( function ( a, b ) {
			// timestamps are strings
			return a.timestamp < b.timestamp ? 1 :
				( a.timestamp === b.timestamp ? 0 : -1 );
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaUserUploadsProvider.prototype.isValid = function () {
		return this.getUserParams().gaiuser && mw.widgets.MediaUserUploadsProvider.super.prototype.isValid.call( this );
	};
}() );
