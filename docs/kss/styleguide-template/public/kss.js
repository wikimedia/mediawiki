;( function () {
	document.addEventListener( 'DOMContentLoaded', function( event ) {
		// Show sub-section links only of the current section.
		// Handlebar does not allow for conditions except with external helpers.
		var currentSection = document.getElementById( 'content' ).classList[0].split( '-' )[1];
		var otherSubSections = document.querySelectorAll( '.subsection:not(#subsection-' + currentSection + ')' );
		for( var i = 0; i < otherSubSections.length; i++ ) {
			otherSubSections[i].style.display = 'none';
		}
	} );
} ) ();
