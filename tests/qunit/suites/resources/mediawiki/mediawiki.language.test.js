QUnit.module( 'mediawiki.language', QUnit.newMwEnvironment({
	setup: function () {
		this.liveLangData = mw.language.data.values;
		mw.language.data.values = $.extend( true, {}, this.liveLangData );
	},
	teardown: function () {
		// Restore
		mw.language.data.values = this.liveLangData;
	}
}) );

QUnit.test( 'mw.language getData and setData', function ( assert ) {
	QUnit.expect( 2 );

	mw.language.setData( 'en', 'testkey', 'testvalue' );
	assert.equal(  mw.language.getData( 'en', 'testkey' ), 'testvalue', 'Getter setter test for mw.language' );
	assert.equal(  mw.language.getData( 'en', 'invalidkey' ), undefined, 'Getter setter test for mw.language with invalid key' );
} );

function grammarTest( langCode, test ) {
	// The test works only if the content language is opt.language
	// because it requires [lang].js to be loaded.
	QUnit.test( 'Grammar test for lang=' + langCode, function ( assert ) {
		QUnit.expect( test.length );

		for ( var i = 0 ; i < test.length; i++ ) {
			assert.equal(
				mw.language.convertGrammar( test[i].word, test[i].grammarForm ),
				test[i].expected,
				test[i].description
			);
		}
	});
}

var grammarTests = {
	bs: [
		{
			word: 'word',
			grammarForm: 'instrumental',
			expected: 's word',
			description: 'Grammar test for instrumental case'
		},
		{
			word: 'word',
			grammarForm: 'lokativ',
			expected: 'o word',
			description: 'Grammar test for lokativ case'
		}
	],

	he: [
		{
			word: "ויקיפדיה",
			grammarForm: 'prefixed',
			expected: "וויקיפדיה",
			description: 'Duplicate the "Waw" if prefixed'
		},
		{
			word: "וולפגנג",
			grammarForm: 'prefixed',
			expected: "וולפגנג",
			description: 'Duplicate the "Waw" if prefixed, but not if it is already duplicated.'
		},
		{
			word: "הקובץ",
			grammarForm: 'prefixed',
			expected: "קובץ",
			description: 'Remove the "He" if prefixed'
		},
		{
			word: 'Wikipedia',
			grammarForm: 'תחילית',
			expected: '־Wikipedia',
			description: 'GAdd a hyphen (maqaf) before non-Hebrew letters'
		},
		{
			word: '1995',
			grammarForm: 'תחילית',
			expected: '־1995',
			description: 'Add a hyphen (maqaf) before numbers'
		}
	],

	hsb: [
		{
			word: 'word',
			grammarForm: 'instrumental',
			expected: 'z word',
			description: 'Grammar test for instrumental case'
		},
		{
			word: 'word',
			grammarForm: 'lokatiw',
			expected: 'wo word',
			description: 'Grammar test for lokatiw case'
		}
	],

	dsb: [
		{
			word: 'word',
			grammarForm: 'instrumental',
			expected: 'z word',
			description: 'Grammar test for instrumental case'
		},
		{
			word: 'word',
			grammarForm: 'lokatiw',
			expected: 'wo word',
			description: 'Grammar test for lokatiw case'
		}
	],

	hy: [
		{
			word: 'Մաունա',
			grammarForm: 'genitive',
			expected: 'Մաունայի',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'հետո',
			grammarForm: 'genitive',
			expected: 'հետոյի',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'գիրք',
			grammarForm: 'genitive',
			expected: 'գրքի',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'ժամանակի',
			grammarForm: 'genitive',
			expected: 'ժամանակիի',
			description: 'Grammar test for genitive case'
		}
	],

	fi: [
		{
			word: 'talo',
			grammarForm: 'genitive',
			expected: 'talon',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'linux',
			grammarForm: 'genitive',
			expected: 'linuxin',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'talo',
			grammarForm: 'elative',
			expected: 'talosta',
			description: 'Grammar test for elative case'
		},
		{
			word: 'pastöroitu',
			grammarForm: 'partitive',
			expected: 'pastöroitua',
			description: 'Grammar test for partitive case'
		},
		{
			word: 'talo',
			grammarForm: 'partitive',
			expected: 'taloa',
			description: 'Grammar test for partitive case'
		},
		{
			word: 'talo',
			grammarForm: 'illative',
			expected: 'taloon',
			description: 'Grammar test for illative case'
		},
		{
			word: 'linux',
			grammarForm: 'inessive',
			expected: 'linuxissa',
			description: 'Grammar test for inessive case'
		}
	],

	ru: [
		{
			word: 'тесть',
			grammarForm: 'genitive',
			expected: 'тестя',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'привилегия',
			grammarForm: 'genitive',
			expected: 'привилегии',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'установка',
			grammarForm: 'genitive',
			expected: 'установки',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'похоти',
			grammarForm: 'genitive',
			expected: 'похотей',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'доводы',
			grammarForm: 'genitive',
			expected: 'доводов',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'песчаник',
			grammarForm: 'genitive',
			expected: 'песчаника',
			description: 'Grammar test for genitive case'
		}
	],


	hu: [
		{
			word: 'Wikipédiá',
			grammarForm: 'rol',
			expected: 'Wikipédiáról',
			description: 'Grammar test for rol case'
		},
		{
			word: 'Wikipédiá',
			grammarForm: 'ba',
			expected: 'Wikipédiába',
			description: 'Grammar test for ba case'
		},
		{
			word: 'Wikipédiá',
			grammarForm: 'k',
			expected: 'Wikipédiák',
			description: 'Grammar test for k case'
		}
	],

	ga: [
		{
			word: 'an Domhnach',
			grammarForm: 'ainmlae',
			expected: 'Dé Domhnaigh',
			description: 'Grammar test for ainmlae case'
		},
		{
			word: 'an Luan',
			grammarForm: 'ainmlae',
			expected: 'Dé Luain',
			description: 'Grammar test for ainmlae case'
		},
		{
			word: 'an Satharn',
			grammarForm: 'ainmlae',
			expected: 'Dé Sathairn',
			description: 'Grammar test for ainmlae case'
		}
	],

	uk: [
		{
			word: 'тесть',
			grammarForm: 'genitive',
			expected: 'тестя',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'Вікіпедія',
			grammarForm: 'genitive',
			expected: 'Вікіпедії',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'установка',
			grammarForm: 'genitive',
			expected: 'установки',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'похоти',
			grammarForm: 'genitive',
			expected: 'похотей',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'доводы',
			grammarForm: 'genitive',
			expected: 'доводов',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'песчаник',
			grammarForm: 'genitive',
			expected: 'песчаника',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'Вікіпедія',
			grammarForm: 'accusative',
			expected: 'Вікіпедію',
			description: 'Grammar test for accusative case'
		}
	],

	sl: [
		{
			word: 'word',
			grammarForm: 'orodnik',
			expected: 'z word',
			description: 'Grammar test for orodnik case'
		},
		{
			word: 'word',
			grammarForm: 'mestnik',
			expected: 'o word',
			description: 'Grammar test for mestnik case'
		}
	],

	os: [
		{
			word: 'бæстæ',
			grammarForm: 'genitive',
			expected: 'бæсты',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'бæстæ',
			grammarForm: 'allative',
			expected: 'бæстæм',
			description: 'Grammar test for allative case'
		},
		{
			word: 'Тигр',
			grammarForm: 'dative',
			expected: 'Тигрæн',
			description: 'Grammar test for dative case'
		},
		{
			word: 'цъити',
			grammarForm: 'dative',
			expected: 'цъитийæн',
			description: 'Grammar test for dative case'
		},
		{
			word: 'лæппу',
			grammarForm: 'genitive',
			expected: 'лæппуйы',
			description: 'Grammar test for genitive case'
		},
		{
			word: '2011',
			grammarForm: 'equative',
			expected: '2011-ау',
			description: 'Grammar test for equative case'
		}
	],

	la: [
		{
			word: 'Translatio',
			grammarForm: 'genitive',
			expected: 'Translationis',
			description: 'Grammar test for genitive case'
		},
		{
			word: 'Translatio',
			grammarForm: 'accusative',
			expected: 'Translationem',
			description: 'Grammar test for accusative case'
		},
		{
			word: 'Translatio',
			grammarForm: 'ablative',
			expected: 'Translatione',
			description: 'Grammar test for ablative case'
		}
	]
};

$.each( grammarTests, function ( langCode, test ) {
	if ( langCode === mw.config.get( 'wgUserLanguage' ) ) {
		grammarTest( langCode, test );
	}
});
