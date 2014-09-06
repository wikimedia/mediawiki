;( function () {
	$( function () {
		var
			throttleTimer, highlightSection,
			currentSection = $( '#content' )[0].classList[0].split( '-' )[1],
			sectionOffsets = {};

		// Show sub-section links only of the current section.
		// Handlebar does not allow for conditions except with external helpers.
		$( '.subsection').not( '#subsection-' + currentSection ).hide();

		// Highlight sections on scroll
		$( '.section-heading' ).each( function () {
			var $this = $( this );
			sectionOffsets[ $this.attr( 'name' ) ] = $this.position().top;
		} );

		highlightSection = function () {
			var currentSubSection, scrollTop = $( document ).scrollTop();

			$.each( sectionOffsets, function ( section, offset ) {
				$( '.subsection a[href="section-' + currentSection + '.html#' + section + '"]' ).removeClass( 'active' );
				currentSubSection = ( scrollTop > offset ) ? section : currentSubSection;
			} );

			if( currentSubSection ) {
				$( '.subsection a[href="section-' + currentSection + '.html#' + currentSubSection + '"]' ).addClass( 'active' );
			}
		}

		$( window ).scroll( function () {
			if( throttleTimer ) {
				clearTimeout( throttleTimer );
			}

			throttleTimer = setTimeout( highlightSection, 100 );
		} );
	} );
} ) ();
