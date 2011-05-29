module( 'jquery.autoEllipsis.js' );

test( '-- Initial check', function(){
	expect(1);
	ok( jQuery.fn.autoEllipsis, 'jQuery.fn.autoEllipsis defined' );
});

function createWrappedDiv( text ) {
	var $wrapper = $( '<div />' ).css( 'width', '100px' );
	var $div = $( '<div />' ).text( text );
	$wrapper.append( $div );
	return $wrapper;
}

function findDivergenceIndex( a, b ) {
	var i = 0;
	while ( i < a.length && i < b.length && a[i] == b[i] ) {
		i++;
	}
	return i;
}

test( 'Position right', function() {
	expect(3);

	// We need this thing to be visible, so append it to the DOM
	var origText = 'This is a really long random string and there is no way it fits in 100 pixels.';
	var $wrapper = createWrappedDiv( origText );
	$( 'body' ).append( $wrapper );
	$wrapper.autoEllipsis( { position: 'right' } );

	// Verify that, and only one, span element was created
	var $span = $wrapper.find( '> span' );
	deepEqual( $span.length, 1, 'autoEllipsis wrapped the contents in a span element' );

	// Check that the text fits by turning on word wrapping
	$span.css( 'whiteSpace', 'nowrap' );
	deepEqual( $span.width() <= $span.parent().width(), true, "Text fits (span's width is no larger than its parent's width)" );

	// Add one character using scary black magic
	var spanText = $span.text();
	var d = findDivergenceIndex( origText, spanText );
	spanText = spanText.substr( 0, d ) + origText[d] + '...';

	// Put this text in the span and verify it doesn't fit
	$span.text( spanText );
	deepEqual( $span.width() > $span.parent().width(), true, 'Fit is maximal (adding one character makes it not fit any more)' );

	// Clean up
	$wrapper.remove();
});
