/*!
 * MediaWiki Widgets - SearchInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Creates a mw.widgets.SearchInputWidget object.
	 *
	 * @class
	 * @extends mw.widgets.TitleInputWidget
	 *
	 * @constructor
	 * @cfg {boolean} [pushPending=true] Visually mark the input field as "pending", while
	 *  requesting suggestions.
	 */
	mw.widgets.SearchInputWidget = function MwWidgetsSearchInputWidget( config ) {
		config = config || {};

		// Parent constructor
		mw.widgets.SearchInputWidget.parent.call( this, config );

		// Initialization
		this.$element.addClass( 'mw-widget-searchInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-searchWidget-menu' );
		if ( !config.pushPending ) {
			this.pushPending = false;
		}
		this.setLookupsDisabled( !this.suggestions );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.SearchInputWidget, mw.widgets.TitleInputWidget );

	/* Methods */

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 */
	mw.widgets.SearchInputWidget.prototype.getSuggestionsPromise = function () {
		var api = new mw.Api();

		// reuse the searchSuggest function from mw.searchSuggest
		return mw.searchSuggest.request( api, this.getQueryValue(), $.noop, this.limit );
	};

	/**
	 * @inheritdoc mw.widgets.TitleInputWidget
	 */
	mw.widgets.SearchInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		// mw.widgets.TitleInputWidget uses response.query, which doesn't exist for opensearch,
		// so return the whole response (titles only, and links)
		return response || {};
	};

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 */
	mw.widgets.SearchInputWidget.prototype.getOptionsFromData = function ( data ) {
		var items = [],
			self = this;

		// mw.widgets.TitleWidget does a lot more work here, because the TitleOptionWidgets can
		// differ a lot, depending on the returned data from the request. With the request used here
		// we get only the search results.
		$.each( data[ 1 ], function ( i, result ) {
			items.push( new mw.widgets.TitleOptionWidget(
				// data[ 3 ][ i ] is the link for this result
				self.getOptionWidgetData( result, null, data[ 3 ][ i ] )
			) );
		} );

		return items;
	};

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 *
	 * @param {string} title
	 * @param {Object} data
	 * @param {string} url The Url to the result
	 */
	mw.widgets.SearchInputWidget.prototype.getOptionWidgetData = function ( title, data, url ) {
		// the values used in mw.widgets-TitleWidget doesn't exist here, that's why
		// the values are hard-coded here
		return {
			data: title,
			url: url,
			imageUrl: null,
			description: null,
			missing: false,
			redirect: false,
			disambiguation: false,
			query: this.getQueryValue()
		};
	};

}( jQuery, mediaWiki ) );
