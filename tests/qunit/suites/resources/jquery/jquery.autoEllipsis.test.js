module( 'jquery.autoEllipsis', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.autoEllipsis, 'jQuery.fn.autoEllipsis defined' );
});

function createWrappedDiv( text, width ) {
	var $wrapper = $( '<div>' ).css( 'width', width );
	var $div = $( '<div>' ).text( text );
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
	expect(4);

	// We need this thing to be visible, so append it to the DOM
	var origText = 'This is a really long random string and there is no way it fits in 100 pixels.';
	var $wrapper = createWrappedDiv( origText, '100px' );
	$( '#qunit-fixture' ).append( $wrapper );
	$wrapper.autoEllipsis( { position: 'right' } );

	// Verify that, and only one, span element was created
	var $span = $wrapper.find( '> span' );
	strictEqual( $span.length, 1, 'autoEllipsis wrapped the contents in a span element' );

	// Check that the text fits by turning on word wrapping
	$span.css( 'whiteSpace', 'nowrap' );
	ltOrEq( $span.width(), $span.parent().width(), "Text fits (making the span 'white-space:nowrap' does not make it wider than its parent)" );

	// Add two characters using scary black magic
	var spanText = $span.text();
	var d = findDivergenceIndex( origText, spanText );
	var spanTextNew = spanText.substr( 0, d ) + origText[d] + origText[d] + '...';

	gt( spanTextNew.length, spanText.length, 'Verify that the new span-length is indeed greater' );

	// Put this text in the span and verify it doesn't fit
	$span.text( spanTextNew );
	// In IE6 width works like min-width, allow IE6's width to be "equal to"
	if ( $.browser.msie && Number( $.browser.version ) === 6 ) {
		gtOrEq( $span.width(), $span.parent().width(), 'Fit is maximal (adding two characters makes it not fit any more) - IE6: Maybe equal to as well due to width behaving like min-width in IE6' );
	} else {
		gt( $span.width(), $span.parent().width(), 'Fit is maximal (adding two characters makes it not fit any more)' );
	}
});
