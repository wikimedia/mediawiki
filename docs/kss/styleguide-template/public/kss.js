;( function () {
	document.addEventListener( 'DOMContentLoaded', function( event ) {
		// Show sub-section links only of the current section.
		// Handlebar does not allow for conditions except with external helpers.
		var currentSection = document.getElementById( 'content' ).classList[0].split( '-' )[1];
		var otherSubSections = document.querySelectorAll( '.subsection:not(#subsection-' + currentSection + ')' );
		for( var i = 0; i < otherSubSections.length; i++ ) {
			otherSubSections[i].style.display = 'none';
		}


		// Highlight sections on scroll

		var sectionOffsets = {};
		var sectionHeadings = document.querySelectorAll( '.section-heading' )

		for( var i = 0; i < sectionHeadings.length; i++ ) {
			var el = sectionHeadings[ i ];
			sectionOffsets[ el.getAttribute( 'name' ) ] = el.offsetTop;
		}

		console.log( sectionOffsets );

		document.addEventListener( 'scroll', function ( event ) {
			var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
			var sections = Object.keys( sectionOffsets );
			var current;

			for( var i = 0; i < sections.length; i++ ) {
				var section = sections[ i ];
				var offset = sectionOffsets[ section ];
				document.querySelector( '.subsection a[href="#' + section + '"]' ).classList.remove( 'active' );
				if( scrollTop > offset ) {
					current = section;
				}
			}

			if( current ) {
				document.querySelector( '.subsection a[href="#' + current + '"]' ).classList.add( 'active' );
			}
		} );
	} );
} ) ();
