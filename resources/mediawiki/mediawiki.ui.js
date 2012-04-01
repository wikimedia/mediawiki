/**
 * Implements mediaWiki.ui library
 *
 * This library gives abstract access to various parts of the MediaWiki UI.
 * Note that not every UI piece belongs in this library. Things which are legacy
 * or not abstract like addPortletLink should be left out of here until they are
 * replaced with something cleanly abstract.
 */
( function ( mw, $ ) {

	// Start mw.ui empty so that we can keep constructors
	// and mw.ui methods next to each other.
	var ui = {};

	/** Search Boxes */

	/**
	 * UI Search Box.
	 * This type encompases search forms within the skin.
	 * A search box will contain a form in $form to which events, etc... may be bound.
	 * As well as an $input for the area that users will be typing their search query into.
	 * In the future this may be expanded to support more advanced abstract search elements.
	 * @param {HTMLFormElement} The search form.
	 */
	function SearchBox( form ) {
		this.$form = $( form );
		this.$input = this.$form.find( 'input[name="search"]:first' )
	}

	// Searchbox cache.
	var searchBoxes;

	/**
	 * Return an array containing all of the SearchBox UI areas on the page.
	 * @return {Array} of {SearchBox}es.
	 */
	ui.searchBoxes = function () {
		if ( !searchBoxes ) {
			// class="mw-search", should be used in all new skins
			var query = 'form.mw-search'
				// Legacy form IDs for compatibility with old skins
				// not yet implementing .mw-search
				+ ', form#searchform, form#searchform2, form#powersearch, form#search';
			searchBoxes = [];
			$( query ).each( function () {
				searchBoxes.push( new SearchBox( this ) );
			} );
		}
		return searchBoxes;
	};

	// Setup mw.ui outside.
	mw.ui = ui;

}( mediaWiki, jQuery ) );
