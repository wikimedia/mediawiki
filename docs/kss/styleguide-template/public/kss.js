;( function () {
	$( function () {
		// Show sub-section links only of the current section.
		// Handlebar does not allow for conditions except with external helpers.
		var currentSection = $( '#content' )[0].classList[0].split( '-' )[1];
		$( '.subsection').not( '#subsection-' + currentSection ).css( 'display', 'none' );
	} );
} ) ();
