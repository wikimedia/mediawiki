( function ( $ ) {
	QUnit.module( 'jquery.highlightText', QUnit.newMwEnvironment() );

	QUnit.test( 'Check', function ( assert ) {
		var $fixture,
			cases = [
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
					desc: 'Test 018: en de Highlighter broken on special character at the end?',
					text: 'So good. xbß',
					highlight: 'xbß',
					expected: 'So good. <span class="highlight">xbß</span>'
				},
				{
					desc: 'Test 019: en de Highlighter broken on special character at the end?',
					text: 'So good. xbß.',
					highlight: 'xbß.',
					expected: 'So good. <span class="highlight">xbß.</span>'
				},
				{
					desc: 'Test 020: RTL he Hebrew',
					text: 'חסיד אומות העולם',
					highlight: 'חסיד אומות העולם',
					expected: '<span class="highlight">חסיד</span> <span class="highlight">אומות</span> <span class="highlight">העולם</span>'
				},
				{
					desc: 'Test 021: RTL he Hebrew',
					text: 'חסיד אומות העולם',
					highlight: 'חסי',
					expected: '<span class="highlight">חסי</span>ד אומות העולם'
				},
				{
					desc: 'Test 022: ja Japanese',
					text: '諸国民の中の正義の人',
					highlight: '諸国民の中の正義の人',
					expected: '<span class="highlight">諸国民の中の正義の人</span>'
				},
				{
					desc: 'Test 023: ja Japanese',
					text: '諸国民の中の正義の人',
					highlight: '諸国',
					expected: '<span class="highlight">諸国</span>民の中の正義の人'
				},
				{
					desc: 'Test 024: fr French text and « french quotes » (guillemets)',
					text: '« L\'oiseau est sur l’île »',
					highlight: '« L\'oiseau est sur l’île »',
					expected: '<span class="highlight">«</span> <span class="highlight">L\'oiseau</span> <span class="highlight">est</span> <span class="highlight">sur</span> <span class="highlight">l’île</span> <span class="highlight">»</span>'
				},
				{
					desc: 'Test 025: fr French text and « french quotes » (guillemets)',
					text: '« L\'oiseau est sur l’île »',
					highlight: '« L\'oise',
					expected: '<span class="highlight">«</span> <span class="highlight">L\'oise</span>au est sur l’île »'
				},
				{
					desc: 'Test 025a: fr French text and « french quotes » (guillemets) - does it match the single strings "«" and "L" separately?',
					text: '« L\'oiseau est sur l’île »',
					highlight: '« L',
					expected: '<span class="highlight">«</span> <span class="highlight">L</span>\'oiseau est sur <span class="highlight">l</span>’île »'
				},
				{
					desc: 'Test 026: ru Russian',
					text: 'Праведники мира',
					highlight: 'Праведники мира',
					expected: '<span class="highlight">Праведники</span> <span class="highlight">мира</span>'
				},
				{
					desc: 'Test 027: ru Russian',
					text: 'Праведники мира',
					highlight: 'Праве',
					expected: '<span class="highlight">Праве</span>дники мира'
				},
				{
					desc: 'Test 028 ka Georgian',
					text: 'მთავარი გვერდი',
					highlight: 'მთავარი გვერდი',
					expected: '<span class="highlight">მთავარი</span> <span class="highlight">გვერდი</span>'
				},
				{
					desc: 'Test 029 ka Georgian',
					text: 'მთავარი გვერდი',
					highlight: 'მთა',
					expected: '<span class="highlight">მთა</span>ვარი გვერდი'
				},
				{
					desc: 'Test 030 hy Armenian',
					text: 'Նոնա Գափրինդաշվիլի',
					highlight: 'Նոնա Գափրինդաշվիլի',
					expected: '<span class="highlight">Նոնա</span> <span class="highlight">Գափրինդաշվիլի</span>'
				},
				{
					desc: 'Test 031 hy Armenian',
					text: 'Նոնա Գափրինդաշվիլի',
					highlight: 'Նոն',
					expected: '<span class="highlight">Նոն</span>ա Գափրինդաշվիլի'
				},
				{
					desc: 'Test 032: th Thai',
					text: 'พอล แอร์ดิช',
					highlight: 'พอล แอร์ดิช',
					expected: '<span class="highlight">พอล</span> <span class="highlight">แอร์ดิช</span>'
				},
				{
					desc: 'Test 033: th Thai',
					text: 'พอล แอร์ดิช',
					highlight: 'พอ',
					expected: '<span class="highlight">พอ</span>ล แอร์ดิช'
				},
				{
					desc: 'Test 034: RTL ar Arabic',
					text: 'بول إيردوس',
					highlight: 'بول إيردوس',
					expected: '<span class="highlight">بول</span> <span class="highlight">إيردوس</span>'
				},
				{
					desc: 'Test 035: RTL ar Arabic',
					text: 'بول إيردوس',
					highlight: 'بو',
					expected: '<span class="highlight">بو</span>ل إيردوس'
				}
			];

		$.each( cases, function ( i, item ) {
			$fixture = $( '<p>' ).text( item.text ).highlightText( item.highlight );
			assert.equal(
				$fixture.html(),
				// Re-parse to normalize
				$( '<p>' ).html( item.expected ).html(),
				item.desc || undefined
			);
		} );
	} );
}( jQuery ) );
