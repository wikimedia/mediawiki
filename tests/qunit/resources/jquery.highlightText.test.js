QUnit.module( 'jquery.highlightText', () => {

	QUnit.test.each( 'highlightText()', [
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
		// Highlighter on starting Umlaut
		{
			text: 'Österreich',
			highlight: 'Österreich',
			expected: '<span class="highlight">Österreich</span>'
		},
		{
			text: 'Österreich',
			highlight: 'Ö',
			expected: '<span class="highlight">Ö</span>sterreich'
		},
		{
			text: 'Österreich',
			highlight: 'Öst',
			expected: '<span class="highlight">Öst</span>erreich'
		},
		{
			text: 'Österreich',
			highlight: 'Oe',
			expected: 'Österreich'
		},
		{
			text: 'So good. To be there',
			highlight: 'good',
			expected: 'So <span class="highlight">good</span>. To be there'
		},
		// Highlighter on space
		{
			text: 'So good. To be there',
			highlight: 'be',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			text: 'So good. To be there',
			highlight: ' be',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			text: 'So good. To be there',
			highlight: 'be ',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		{
			text: 'So good. To be there',
			highlight: ' be ',
			expected: 'So good. To <span class="highlight">be</span> there'
		},
		// Highlighter with special character at the end
		{
			text: 'So good. xbß',
			highlight: 'xbß',
			expected: 'So good. <span class="highlight">xbß</span>'
		},
		{
			text: 'So good. xbß.',
			highlight: 'xbß.',
			expected: 'So good. <span class="highlight">xbß.</span>'
		},
		{
			text: 'חסיד אומות העולם',
			highlight: 'חסיד אומות העולם',
			expected: '<span class="highlight">חסיד</span> <span class="highlight">אומות</span> <span class="highlight">העולם</span>'
		},
		{
			text: 'חסיד אומות העולם',
			highlight: 'חסי',
			expected: '<span class="highlight">חסי</span>ד אומות העולם'
		},
		// Japanese (ja)
		{
			text: '諸国民の中の正義の人',
			highlight: '諸国民の中の正義の人',
			expected: '<span class="highlight">諸国民の中の正義の人</span>'
		},
		{
			text: '諸国民の中の正義の人',
			highlight: '諸国',
			expected: '<span class="highlight">諸国</span>民の中の正義の人'
		},
		// French (fr) text and french guillemets quotes
		{
			text: '« L\'oiseau est sur l’île »',
			highlight: '« L\'oiseau est sur l’île »',
			expected: '<span class="highlight">«</span> <span class="highlight">L\'oiseau</span> <span class="highlight">est</span> <span class="highlight">sur</span> <span class="highlight">l’île</span> <span class="highlight">»</span>'
		},
		{
			text: '« L\'oiseau est sur l’île »',
			highlight: '« L\'oise',
			expected: '<span class="highlight">«</span> <span class="highlight">L\'oise</span>au est sur l’île »'
		},
		{
			// does it match the single strings "«" and "L" separately?
			text: '« L\'oiseau est sur l’île »',
			highlight: '« L',
			expected: '<span class="highlight">«</span> <span class="highlight">L</span>\'oiseau est sur <span class="highlight">l</span>’île »'
		},
		// Russian (ru)
		{
			text: 'Праведники мира',
			highlight: 'Праведники мира',
			expected: '<span class="highlight">Праведники</span> <span class="highlight">мира</span>'
		},
		{
			text: 'Праведники мира',
			highlight: 'Праве',
			expected: '<span class="highlight">Праве</span>дники мира'
		},
		// Georgian (ka)
		{
			text: 'მთავარი გვერდი',
			highlight: 'მთავარი გვერდი',
			expected: '<span class="highlight">მთავარი</span> <span class="highlight">გვერდი</span>'
		},
		{
			text: 'მთავარი გვერდი',
			highlight: 'მთა',
			expected: '<span class="highlight">მთა</span>ვარი გვერდი'
		},
		// Armenian (hy)
		{
			text: 'Նոնա Գափրինդաշվիլի',
			highlight: 'Նոնա Գափրինդաշվիլի',
			expected: '<span class="highlight">Նոնա</span> <span class="highlight">Գափրինդաշվիլի</span>'
		},
		{
			text: 'Նոնա Գափրինդաշվիլի',
			highlight: 'Նոն',
			expected: '<span class="highlight">Նոն</span>ա Գափրինդաշվիլի'
		},
		// Thai (th)
		{
			text: 'พอล แอร์ดิช',
			highlight: 'พอล แอร์ดิช',
			expected: '<span class="highlight">พอล</span> <span class="highlight">แอร์ดิช</span>'
		},
		{
			text: 'พอล แอร์ดิช',
			highlight: 'พอ',
			expected: '<span class="highlight">พอ</span>ล แอร์ดิช'
		},
		// RTL: Arabic (ar)
		{
			text: 'بول إيردوس',
			highlight: 'بول إيردوس',
			expected: '<span class="highlight">بول</span> <span class="highlight">إيردوس</span>'
		},
		{
			text: 'بول إيردوس',
			highlight: 'بو',
			expected: '<span class="highlight">بو</span>ل إيردوس'
		}
	], ( assert, item ) => {
		const $fixture = $( '<p>' ).text( item.text ).highlightText( item.highlight );
		assert.strictEqual(
			$fixture.html(),
			// Re-parse to normalize
			$( '<p>' ).html( item.expected ).html()
		);
	} );
} );
