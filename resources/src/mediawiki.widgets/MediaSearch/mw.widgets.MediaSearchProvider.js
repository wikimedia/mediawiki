/*!
 * MediaWiki Widgets - MediaSearchProvider class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Media search provider.
	 *
	 * @class
	 * @extends mw.widgets.MediaResourceProvider
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.MediaSearchProvider`.
	 * @param {string} apiurl The API url
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.MediaSearchProvider = function MwWidgetsMediaSearchProvider( apiurl, config = {} ) {
		config.staticParams = Object.assign( {
			generator: 'search',
			gsrnamespace: mw.config.get( 'wgNamespaceIds' ).file,
			uselang: mw.config.get( 'wgUserLanguage' )
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
		return results.sort( ( a, b ) => a.index - b.index );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaSearchProvider.prototype.isValid = function () {
		return this.getUserParams().gsrsearch && mw.widgets.MediaSearchProvider.super.prototype.isValid.call( this );
	};
}() );
