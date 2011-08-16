module( 'jquery.highlightText' );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.highlightText, 'jQuery.fn.highlightText defined' );
} );

test( 'Check', function() {
	var cases = [
		{
			text: 'Blue Öyster Cult',
			highlight: 'Blue',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			text: 'Österreich',
			highlight: 'Österreich',
			expected: '<span class="highlight">Österreich</span>'
		},
		{
			desc: 'Highlighter broken on punctuation mark',
			text: 'So good. To be there',
			highlight: 'good',
			expected: 'So <span class="highlight">good</span>. To be there'
		}
	];
	expect(cases.length);
	var $fixture;

	$.each(cases, function( i, item ) {
		$fixture = $( '<p></p>' ).text( item.text );
		$fixture.highlightText( item.highlight );
		equals(
			$fixture.html(),
			$('<p>' + item.expected + '</p>').html(), // re-parse to normalize!
			item.desc || undefined
			);
	} );
} );
