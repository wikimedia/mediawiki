;( function () {
	$( function () {
		// Show sub-section links only of the current section.
		// Handlebar does not allow for conditions except with external helpers.
		var currentSection = $( '#content' )[0].classList[0].split( '-' )[1];
		$( '.subsection').not( '#subsection-' + currentSection ).css( 'display', 'none' );


		// Highlight sections on scroll
		var sectionOffsets = {};
		$( '.section-heading' ).each( function () {
			var $this = $( this );
			sectionOffsets[ $this.attr( 'name' ) ] = $this.position().top;
		} );

		$( window ).scroll( function () {
			var currentSubSection, scrollTop = $( document ).scrollTop();

			$.each( sectionOffsets, function ( section, offset ) {
				$( '.subsection a[href="section-' + currentSection + '.html#' + section + '"]' ).removeClass( 'active' );
				currentSubSection = ( scrollTop > offset ) ? section : currentSubSection;
			} );

			if( currentSubSection ) {
				$( '.subsection a[href="section-' + currentSection + '.html#' + currentSubSection + '"]' ).addClass( 'active' );
			}
		} );
	} );
} ) ();
