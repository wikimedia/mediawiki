module( 'jquery.highlightText' );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.highlightText, 'jQuery.fn.highlightText defined' );
} );

test( 'Check', function() {
	var cases = [
		{
		        desc: 'Test 001',
			text: 'Blue Öyster Cult',
			highlight: 'Blue',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
		        desc: 'Test 002',
			text: 'Blue Öyster Cult',
			highlight: 'Blue ',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			desc: 'Test 003',
			text: 'Blue Öyster Cult',
			highlight: 'Blue Ö',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Ö</span>yster Cult'
		},
		{
			desc: 'Test 004',
			text: 'Blue Öyster Cult',
			highlight: 'Blue Öy',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Öy</span>ster Cult'
		},
		{
			desc: 'Test 005',
			text: 'Blue Öyster Cult',
			highlight: ' Blue',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			desc: 'Test 006',
			text: 'Blue Öyster Cult',
			highlight: ' Blue ',
			expected: '<span class="highlight">Blue</span> Öyster Cult'
		},
		{
			desc: 'Test 007',
			text: 'Blue Öyster Cult',
			highlight: ' Blue Ö',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Ö</span>yster Cult'
		},
		{
			desc: 'Test 008',
			text: 'Blue Öyster Cult',
			highlight: ' Blue Öy',
			expected: '<span class="highlight">Blue</span> <span class="highlight">Öy</span>ster Cult'
		},
		{
			desc: 'Test 009: Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Österreich',
			expected: '<span class="highlight">Österreich</span>'
		},
		{
			desc: 'Test 010: Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Ö',
			expected: '<span class="highlight">Ö</span>sterreich'
		},
		{
			desc: 'Test 011: Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Öst',
			expected: '<span class="highlight">Öst</span>erreich'
		},
		{
			desc: 'Test 012: Highlighter broken on starting Umlaut?',
			text: 'Österreich',
			highlight: 'Oe',
			expected: 'Österreich'
		},
		{
			desc: 'Test 013: Highlighter broken on punctuation mark?',
			text: 'So good. To be there',
			highlight: 'good',
			expected: 'So <span class="highlight">good</span>. To be there'
		},
		{
			desc: 'Test 014: Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: 'be',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Test 015: Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: ' be',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Test 016: Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: 'be ',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Test 017: Highlighter broken on space?',
			text: 'So good. To be there',
			highlight: ' be ',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			desc: 'Test 018: Highlighter broken on special character at the end?',
			text: 'So good. xbß',
			highlight: 'xbß',
			expected: 'So good. <span class="highlight">xbß</span>'
		},
		{
			desc: 'Test 019: Highlighter broken on special character at the end?',
			text: 'So good. xbß.',
			highlight: 'xbß.',
			expected: 'So good. <span class="highlight">xbß.</span>'
		},
		{
			desc: 'Test 020: Hebrew',
			text: 'חסיד אומות העולם',
			highlight: 'חסיד אומות העולם',
			expected: '<span class="highlight">חסיד</span> <span class="highlight">אומות</span> <span class="highlight">העולם</span>'
		},
		{
			desc: 'Test 021: Hebrew',
			text: 'חסיד אומות העולם',
			highlight: 'חסי',
			expected: '<span class="highlight">חסי</span>ד אומות העולם'
		},
		{
			desc: 'Test 022: Japanese',
			text: '諸国民の中の正義の人',
			highlight: '諸国民の中の正義の人',
			expected: '<span class="highlight">諸国民の中の正義の人</span>'
		},
		{
			desc: 'Test 023: Japanese',
			text: '諸国民の中の正義の人',
			highlight: '諸国',
			expected: '<span class="highlight">諸国</span>民の中の正義の人'
		},
		{
			desc: 'Test 024: French text and « french quotes » (guillemets)',
			text: "« L'oiseau est sur l’île »",
			highlight: "« L'oiseau est sur l’île »",
			expected: '<span class="highlight">«</span> <span class="highlight">L\'oiseau</span> <span class="highlight">est</span> <span class="highlight">sur</span> <span class="highlight">l’île</span> <span class="highlight">»</span>'
		},
		{
			desc: 'Test 025: French text and « french quotes » (guillemets)',
			text: "« L'oiseau est sur l’île »",
			highlight: "« L'oise",
			expected: '<span class="highlight">«</span> <span class="highlight">L\'oise</span>au est sur l’île »'
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
