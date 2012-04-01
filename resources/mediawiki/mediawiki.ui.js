/**
 * Implements mediaWiki.ui library
 *
 * This library gives abstract access to various parts of the MediaWiki UI.
 * Note that not every UI piece belongs in this library. Things which are legacy
 * or not abstract like addPortletLink should be left out of here until they are
 * replaced with something cleanly abstract.
 */
( function ( $, mw ) {

	function SearchBox( form ) {
		this.$form = $( form );
		this.$input = this.$form.find( 'input[name="search"]:first' )
	}

	var ui = {
		searchBoxes: function() {
			var query = [
				// class="mw-search", should be used in all new skins
				'form.mw-search',
				// Legacy form IDs for old skins not implementing .mw-search
				'form#searchform', 'form#searchform2', 'form#powersearch', 'form#search'
			];
			var searchBoxes = [];
			return $( query.join( ', ' ) ).map( function() {
				var $this = $( this );
				return new SearchBox( this );
			} );
		}
	};

	mw.ui = ui;

} )( jQuery, mediaWiki );
