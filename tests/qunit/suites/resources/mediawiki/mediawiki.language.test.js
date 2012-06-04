module( 'mediawiki.language' );

test( '-- Initial check', function() {
	expect( 1 );
	ok( mw.language, 'mw.language defined' );
} );

test( 'mw.language getData and setData', function() {
	expect( 2 );
	mw.language.setData( "en", "testkey", "testvalue" );
	equal(  mw.language.getData( "en", "testkey" ),"testvalue", 'Getter setter test for mw.language' );
	equal(  mw.language.getData( "en", "invalidkey" ), undefined, 'Getter setter test for mw.language with invalid key' );
} );

var grammartest = function( options ) {
	var opt = $.extend({
		language: '',
		test: []
	}, options );
	// The test works only if the content language is opt.language
	// because it requires [lang].js to be loaded.
	if( mw.config.get ( 'wgContentLanguage' ) === opt.language ) {
		test( "-- Grammar Test for "+ opt.language, function() {
			expect( opt.test.length );
			for ( var i= 0 ; i < opt.test.length; i++ ) {
				equal( mw.language.convertGrammar( opt.test[i].word, opt.test[i].grammarForm ), opt.test[i].expected, opt.test[i].description );
			}
		} );
	}
}

grammartest({
	language: 'bs',
	test: [
		{ word: 'word', grammarForm: 'instrumental', expected: 's word', description: 'Grammar test for Bosnian, instrumental case' },
		{ word: 'word', grammarForm: 'lokativ', expected: 'o word', description: 'Grammar test for Bosnian, lokativ case' }
	]
});

grammartest({
	language: 'he',
	test: [
		{ word: "ויקיפדיה", grammarForm: 'prefixed', expected: "וויקיפדיה", description: 'Grammar test for Hebrew, Duplicate the "Waw" if prefixed' },
		{ word: "וולפגנג", grammarForm: 'prefixed', expected: "וולפגנג", description: 'Grammar test for Hebrew, Duplicate the "Waw" if prefixed, but not if it is already duplicated.' },
		{ word: "הקובץ", grammarForm: 'prefixed', expected: "קובץ", description: 'Grammar test for Hebrew, Remove the "He" if prefixed' },
		{ word: 'Wikipedia', grammarForm: 'תחילית', expected: '־Wikipedia', description: 'Grammar test for Hebrew, Add a hyphen (maqaf) before non-Hebrew letters' },
		{ word: '1995', grammarForm: 'תחילית', expected: '־1995', description: 'Grammar test for Hebrew, Add a hyphen (maqaf) before numbers' }
	]
});

grammartest({
	language: 'hsb',
	test: [
		{ word: 'word', grammarForm: 'instrumental', expected: 'z word', description: 'Grammar test for Upper Sorbian, instrumental case' },
		{ word: 'word', grammarForm: 'lokatiw', expected: 'wo word', description: 'Grammar test for Upper Sorbian, lokatiw case' }
	]
});

grammartest({
	language: 'dsb',
	test: [
		{ word: 'word', grammarForm: 'instrumental', expected: 'z word', description: 'Grammar test for Lower Sorbian, instrumental case' },
		{ word: 'word', grammarForm: 'lokatiw', expected: 'wo word', description: 'Grammar test for Lower Sorbian, lokatiw case' }
	]
});

grammartest({
	language: 'hy',
	test: [
		{ word: 'Մաունա', grammarForm: 'genitive', expected: 'Մաունայի', description: 'Grammar test for Armenian, genitive case' },
		{ word: 'հետո', grammarForm: 'genitive', expected: 'հետոյի', description: 'Grammar test for Armenian, genitive case' },
		{ word: 'գիրք', grammarForm: 'genitive', expected: 'գրքի', description: 'Grammar test for Armenian, genitive case' },
		{ word: 'ժամանակի', grammarForm: 'genitive', expected: 'ժամանակիի', description: 'Grammar test for Armenian, genitive case' }
	]
});

grammartest({
	language: 'fi',
	test: [
		{ word: 'talo', grammarForm: 'genitive', expected: 'talon', description: 'Grammar test for Finnish, genitive case' },
		{ word: 'linux', grammarForm: 'genitive', expected: 'linuxin', description: 'Grammar test for Finnish, genitive case' },
		{ word: 'talo', grammarForm: 'elative', expected: 'talosta', description: 'Grammar test for Finnish, elative case' },
		{ word: 'pastöroitu', grammarForm: 'partitive', expected: 'pastöroitua', description: 'Grammar test for Finnish, partitive case' },
		{ word: 'talo', grammarForm: 'partitive', expected: 'taloa', description: 'Grammar test for Finnish, partitive case' },
		{ word: 'talo', grammarForm: 'illative', expected: 'taloon', description: 'Grammar test for Finnish, illative case' },
		{ word: 'linux', grammarForm: 'inessive', expected: 'linuxissa', description: 'Grammar test for Finnish, inessive case' }
	]
});

grammartest({
	language: 'ru',
	test: [
		{ word: 'тесть', grammarForm: 'genitive', expected: 'тестя', description: 'Grammar test for Russian, genitive case' },
		{ word: 'привилегия', grammarForm: 'genitive', expected: 'привилегии', description: 'Grammar test for Russian, genitive case' },
		{ word: 'установка', grammarForm: 'genitive', expected: 'установки', description: 'Grammar test for Russian, genitive case' },
		{ word: 'похоти', grammarForm: 'genitive', expected: 'похотей', description: 'Grammar test for Russian, genitive case' },
		{ word: 'доводы', grammarForm: 'genitive', expected: 'доводов', description: 'Grammar test for Russian, genitive case' },
		{ word: 'песчаник', grammarForm: 'genitive', expected: 'песчаника', description: 'Grammar test for Russian, genitive case' }
	]
});

grammartest({
	language: 'hu',
	test: [
		{ word: 'Wikipédiá', grammarForm: 'rol', expected: 'Wikipédiáról', description: 'Grammar test for Hungarian, rol case' },
		{ word: 'Wikipédiá', grammarForm: 'ba', expected: 'Wikipédiába', description: 'Grammar test for Hungarian, ba case' },
		{ word: 'Wikipédiá', grammarForm: 'k', expected: 'Wikipédiák', description: 'Grammar test for Hungarian, k case' }
	]
});

grammartest({
	language: 'ga',
	test: [
		{ word: 'an Domhnach', grammarForm: 'ainmlae', expected: 'Dé Domhnaigh', description: 'Grammar test for Irish, ainmlae case' },
		{ word: 'an Luan', grammarForm: 'ainmlae', expected: 'Dé Luain', description: 'Grammar test for Irish, ainmlae case' },
		{ word: 'an Satharn', grammarForm: 'ainmlae', expected: 'Dé Sathairn', description: 'Grammar test for Irish, ainmlae case' }
	]
});

grammartest({
	language: 'uk',
	test: [
		{ word: 'тесть', grammarForm: 'genitive', expected: 'тестя', description: 'Grammar test for Ukrainian, genitive case' },
		{ word: 'Вікіпедія', grammarForm: 'genitive', expected: 'Вікіпедії', description: 'Grammar test for Ukrainian, genitive case' },
		{ word: 'установка', grammarForm: 'genitive', expected: 'установки', description: 'Grammar test for Ukrainian, genitive case' },
		{ word: 'похоти', grammarForm: 'genitive', expected: 'похотей', description: 'Grammar test for Ukrainian, genitive case' },
		{ word: 'доводы', grammarForm: 'genitive', expected: 'доводов', description: 'Grammar test for Ukrainian, genitive case' },
		{ word: 'песчаник', grammarForm: 'genitive', expected: 'песчаника', description: 'Grammar test for Ukrainian, genitive case' },
		{ word: 'Вікіпедія', grammarForm: 'accusative', expected: 'Вікіпедію', description: 'Grammar test for Ukrainian, accusative case' }
	]
});


grammartest({
	language: 'sl',
	test: [
		{ word: 'word', grammarForm: 'orodnik', expected: 'z word', description: 'Grammar test for Slovenian, orodnik case' },
		{ word: 'word', grammarForm: 'mestnik', expected: 'o word', description: 'Grammar test for Slovenian, mestnik case' }
	]
});

grammartest({
	language: 'os',
	test: [
		{ word: 'бæстæ', grammarForm: 'genitive', expected: 'бæсты', description: 'Grammar test for Ossetian, genitive case' },
		{ word: 'бæстæ', grammarForm: 'allative', expected: 'бæстæм', description: 'Grammar test for Ossetian, allative case' },
		{ word: 'Тигр', grammarForm: 'dative', expected: 'Тигрæн', description: 'Grammar test for Ossetian, dative case' },
		{ word: 'цъити', grammarForm: 'dative', expected: 'цъитийæн', description: 'Grammar test for Ossetian, dative case' },
		{ word: 'лæппу', grammarForm: 'genitive', expected: 'лæппуйы', description: 'Grammar test for Ossetian, genitive case' },
		{ word: '2011', grammarForm: 'equative', expected: '2011-ау', description: 'Grammar test for Ossetian, equative case' }
	]
});

grammartest({
	language: 'la',
	test: [
		{ word: 'Translatio', grammarForm: 'genitive', expected: 'Translationis', description: 'Grammar test for Latin, genitive case' },
		{ word: 'Translatio', grammarForm: 'accusative', expected: 'Translationem', description: 'Grammar test for Latin, accusative case' },
		{ word: 'Translatio', grammarForm: 'ablative', expected: 'Translatione', description: 'Grammar test for Latin, ablative case' }
	]
});