mw.page.ajaxCategories = new mw.ajaxCategories();
jQuery( document ).ready( function(){
	// Separate function for call to prevent jQuery
	// from executing it in the document context.
	mw.page.ajaxCategories.setup();
} );
