jQuery( document ).ready( function( $ ) {

	// Skip this feature if the browser does not support session storage
	if ( !window.sessionStorage ) {
		return;
	}

	// Handle the value of previous request 
	if ( window.sessionStorage.mwLastRequestSearchText ) {
		var value = window.sessionStorage.mwLastRequestSearchText;

		// We don't want to insert anything on Special:Search
		var specialPage = mw.config.get( 'wgCanonicalSpecialPageName' );
		if ( specialPage !== 'Search' ) {
			var uri = new mw.Uri( mw.config.get( 'wgServer' ) + mw.config.get( 'wgScript' ) );
			uri.extend( {
				title: (new mw.Title( 'Search', -1 )).getPrefixedText(),
				search: value,
				fulltext: 1
			})
			mw.notify( mw.message( 'search-notwhatlookingfor', value, uri ) );
		}

		// Once we've shown the message drop the data
		// We don't need to keep this around for refresh or anything
		delete window.sessionStorage.mwLastRequestSearchText;
	}

	/* Implement "Not what you were looking for?" handling for "Go" searches */
	function submitHandler( e ) {
		// Get the search value
		var value = this.$input.val();

		// Before continuing with the search store it in session storage
		window.sessionStorage.mwLastRequestSearchText = value;
	}

	mw.ui.searchBoxes().each( function() {
		var searchBox = this;
		searchBox.$form.submit( function( e ) {
			submitHandler.call( searchBox, e );
		} );
	} );

} );
