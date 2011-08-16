module( 'jquery.highlightText' );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.highlightText, 'jQuery.fn.highlightText defined' );
} );

test( 'Check', function() {
	expect(3);
	var $fixture;
   
	$fixture = $( '<p>Blue Öyster Cult</p>' );
	$fixture.highlightText( 'Blue' );
	equal(
		'<span class="highlight">Blue</span> Öyster Cult',
		$fixture.html()
		);

	$fixture = $( '<p>Österreich</p>' );
	$fixture.highlightText( 'Österreich' );
	equal(
		'<span class="highlight">Österreich</span>',
		$fixture.html()
		);

	/**
	 * Highlighter broken on punctuation mark.
	 */
	/**
	$fixture = $( '<p>So good. To be there</p>' );
	$fixture.highlightText( 'good' );
	equal(
		'So <span class="highlight">good</span>. To be there',
		$fixture.html()
		);
	*/
} );
