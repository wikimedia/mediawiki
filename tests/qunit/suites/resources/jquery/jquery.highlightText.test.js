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
			text: 'Blue Öyster Cult',
			highlight: 'Blue ',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			text: 'Blue Öyster Cult',
			highlight: 'Blue Ö',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Ö</span>yster Cult'
		},
		{
			text: 'Blue Öyster Cult',
			highlight: 'Blue Öy',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Öy</span>ster Cult'
		},
		{
			text: 'Blue Öyster Cult',
			highlight: ' Blue',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			text: 'Blue Öyster Cult',
			highlight: ' Blue ',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			text: 'Blue Öyster Cult',
			highlight: ' Blue Ö',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Ö</span>yster Cult'
		},
		{
			text: 'Blue Öyster Cult',
			highlight: ' Blue Öy',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Öy</span>ster Cult'
		},
		{
			desc: 'Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Österreich',
			expected: '<span class="highlight">Österreich</span>'
		},
		{
			desc: 'Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Ö',
			expected: '<span class="highlight">Ö</span>sterreich</span>'
		},
		{
			desc: 'Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Öst',
			expected: '<span class="highlight">Öst</span>erreich'
		},
		{
			desc: 'Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Oe',
			expected: 'Österreich'
		},
		{
			desc: 'Highlighter broken on punctuation mark?',
			text: 'So good. To be there',
			highlight: 'good',
			expected: 'So <span class="highlight">good</span>. To be there'
		},
		{
			desc: 'Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: 'be',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: ' be',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: 'be ',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: ' be ',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Highlighter broken on special character at the end?',
			text: 'So good. xbß',
			highlight: 'xbß',
			expected: 'So good. <span class="highlight">xbß</span>'
		},
		{
			desc: 'Highlighter broken on special character at the end?',
			text: 'So good. xbß.',
			highlight: 'xbß.',
			expected: 'So good. <span class="highlight">xbß.</span>'
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
