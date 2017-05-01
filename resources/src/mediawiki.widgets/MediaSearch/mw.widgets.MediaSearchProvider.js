/*!
 * MediaWiki Widgets - MediaSearchProvider class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

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
	mw.widgets.MediaSearchProvider = function MwWidgetsMediaSearchProvider( apiurl, config ) {
		config = config || {};

		config.staticParams = $.extend( {
			generator: 'search',
			gsrnamespace: mw.config.get( 'wgNamespaceIds' ).file
		}, config.staticParams );

		// Parent constructor
		mw.widgets.MediaSearchProvider.super.call( this, apiurl, config );
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaSearchProvider, mw.widgets.MediaResourceProvider );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaSearchProvider.prototype.getContinueData = function ( howMany ) {
		return {
			gsroffset: this.getOffset(),
			gsrlimit: howMany || this.getDefaultFetchLimit()
		};
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaSearchProvider.prototype.setContinue = function ( continueData ) {
		// Update the offset for next time
		this.setOffset( continueData.gsroffset );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaSearchProvider.prototype.sort = function ( results ) {
		return results.sort( function ( a, b ) {
			return a.index - b.index;
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaSearchProvider.prototype.isValid = function () {
		return this.getUserParams().gsrsearch && mw.widgets.MediaSearchProvider.super.prototype.isValid.call( this );
	};
}( jQuery, mediaWiki ) );
