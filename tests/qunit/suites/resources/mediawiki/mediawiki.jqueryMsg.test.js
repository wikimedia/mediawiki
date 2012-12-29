( function ( mw, $ ) {

QUnit.module( 'mediawiki.jqueryMsg', QUnit.newMwEnvironment( {
	setup: function () {
		this.orgMwLangauge = mw.language;
		mw.language = $.extend( true, {}, this.orgMwLangauge );
		jQuery.fn.getOuterHtml = function() {
			var $div = $( '<div>' );
			$div.append( $( this ).eq( 0 ).clone() );
			return $div.html();
		};
	},
	teardown: function () {
		mw.language = this.orgMwLangauge;
		delete jQuery.fn.getOuterHtml;
	}
}) );

var mwLanguageCache = {};
function getMwLanguage( langCode, cb ) {
	if ( mwLanguageCache[langCode] !== undefined ) {
		mwLanguageCache[langCode].add( cb );
		return;
	}
	mwLanguageCache[langCode] = $.Callbacks( 'once memory' );
	mwLanguageCache[langCode].add( cb );
	$.ajax({
		url: mw.util.wikiScript( 'load' ),
		data: {
			skin: mw.config.get( 'skin' ),
			lang: langCode,
			debug: mw.config.get( 'debug' ),
			modules: [
				'mediawiki.language.data',
				'mediawiki.language'
			].join( '|' ),
			only: 'scripts'
		},
		dataType: 'script'
	}).done( function () {
		mwLanguageCache[langCode].fire( mw.language );
	}).fail( function () {
		mwLanguageCache[langCode].fire( false );
	});
}

QUnit.test( 'Plural', 3, function ( assert ) {
	var parser = mw.jqueryMsg.getMessageFunction();

	mw.messages.set( 'plural-msg', 'Found $1 {{PLURAL:$1|item|items}}' );
	assert.equal( parser( 'plural-msg', 0 ), 'Found 0 items', 'Plural test for english with zero as count' );
	assert.equal( parser( 'plural-msg', 1 ), 'Found 1 item', 'Singular test for english' );
	assert.equal( parser( 'plural-msg', 2 ), 'Found 2 items', 'Plural test for english' );
} );


QUnit.test( 'Gender', 11, function ( assert ) {
	// TODO: These tests should be for mw.msg once mw.msg integrated with mw.jqueryMsg
	// TODO: English may not be the best language for these tests. Use a language like Arabic or Russian
	var user = mw.user,
		parser = mw.jqueryMsg.getMessageFunction();

	// The values here are not significant,
	// what matters is which of the values is choosen by the parser
	mw.messages.set( 'gender-msg', '$1: {{GENDER:$2|blue|pink|green}}' );

	user.options.set( 'gender', 'male' );
	assert.equal(
		parser( 'gender-msg', 'Bob', 'male' ),
		'Bob: blue',
		'Masculine from string "male"'
	);
	assert.equal(
		parser( 'gender-msg', 'Bob', user ),
		'Bob: blue',
		'Masculine from mw.user object'
	);

	user.options.set( 'gender', 'unknown' );
	assert.equal(
		parser( 'gender-msg', 'Foo', user ),
		'Foo: green',
		'Neutral from mw.user object' );
	assert.equal(
		parser( 'gender-msg', 'Alice', 'female' ),
		'Alice: pink',
		'Feminine from string "female"' );
	assert.equal(
		parser( 'gender-msg', 'User' ),
		'User: green',
		'Neutral when no parameter given' );
	assert.equal(
		parser( 'gender-msg', 'User', 'unknown' ),
		'User: green',
		'Neutral from string "unknown"'
	);

	mw.messages.set( 'gender-msg-one-form', '{{GENDER:$1|User}}: $2 {{PLURAL:$2|edit|edits}}' );

	assert.equal(
		parser( 'gender-msg-one-form', 'male', 10 ),
		'User: 10 edits',
		'Gender neutral and plural form'
	);
	assert.equal(
		parser( 'gender-msg-one-form', 'female', 1 ),
		'User: 1 edit',
		'Gender neutral and singular form'
	);

	mw.messages.set( 'gender-msg-lowercase', '{{gender:$1|he|she}} is awesome' );
	assert.equal(
		parser( 'gender-msg-lowercase', 'male' ),
		'he is awesome',
		'Gender masculine'
	);
	assert.equal(
		parser( 'gender-msg-lowercase', 'female' ),
		'she is awesome',
		'Gender feminine'
	);

	mw.messages.set( 'gender-msg-wrong', '{{gender}} test' );
	assert.equal(
		parser( 'gender-msg-wrong', 'female' ),
		' test',
		'Invalid syntax should result in {{gender}} simply being stripped away'
	);
} );

QUnit.test( 'Grammar', 2, function ( assert ) {
	var parser = mw.jqueryMsg.getMessageFunction();

	// Assume the grammar form grammar_case_foo is not valid in any language
	mw.messages.set( 'grammar-msg', 'Przeszukaj {{GRAMMAR:grammar_case_foo|{{SITENAME}}}}' );
	assert.equal( parser( 'grammar-msg' ), 'Przeszukaj ' + mw.config.get( 'wgSiteName' ), 'Grammar Test with sitename' );

	mw.messages.set( 'grammar-msg-wrong-syntax', 'Przeszukaj {{GRAMMAR:grammar_case_xyz}}' );
	assert.equal( parser( 'grammar-msg-wrong-syntax' ), 'Przeszukaj ' , 'Grammar Test with wrong grammar template syntax' );
} );

QUnit.test( 'Output matches PHP parser', mw.libs.phpParserData.tests.length, function ( assert ) {
	mw.messages.set( mw.libs.phpParserData.messages );
	$.each( mw.libs.phpParserData.tests, function ( i, test ) {
		QUnit.stop();
		getMwLanguage( test.lang, function ( langClass ) {
			QUnit.start();
			if ( !langClass ) {
				assert.ok( false, 'Language "' + test.lang + '" failed to load' );
				return;
			}
			mw.config.set( 'wgUserLanguage', test.lang ) ;
			var parser = new mw.jqueryMsg.parser( { language: langClass } );
			assert.equal(
				parser.parse( test.key, test.args ).html(),
				test.result,
				test.name
			);
		} );
	} );
});

QUnit.test( 'Links', 6, function ( assert ) {
	var parser = mw.jqueryMsg.getMessageFunction(),
		expectedListUsers,
		expectedDisambiguationsText,
		expectedMultipleBars,
		expectedSpecialCharacters,
		specialCharactersPageName;

	/*
	 The below three are all identical to or based on real messages.  For disambiguations-text,
	 the bold was removed because it is not yet implemented.
	*/

	mw.messages.set( 'statistics-users', '注册[[Special:ListUsers|用户]]' );

	expectedListUsers = '注册' + $( '<a>' ).attr( {
		title: 'Special:ListUsers',
		href: mw.util.wikiGetlink( 'Special:ListUsers' )
	} ).text( '用户' ).getOuterHtml();

	assert.equal(
		parser( 'statistics-users' ),
		expectedListUsers,
		'Piped wikilink'
	);

	expectedDisambiguationsText = 'The following pages contain at least one link to a disambiguation page.\nThey may have to link to a more appropriate page instead.<br>\nA page is treated as a disambiguation page if it uses a template that is linked from ' +
		$( '<a>' ).attr( {
			title: 'MediaWiki:Disambiguationspage',
			href: mw.util.wikiGetlink( 'MediaWiki:Disambiguationspage' )
		} ).text( 'MediaWiki:Disambiguationspage' ).getOuterHtml() + '.';
	mw.messages.set( 'disambiguations-text', 'The following pages contain at least one link to a disambiguation page.\nThey may have to link to a more appropriate page instead.<br />\nA page is treated as a disambiguation page if it uses a template that is linked from [[MediaWiki:Disambiguationspage]].' );
	assert.equal(
		parser( 'disambiguations-text' ),
		expectedDisambiguationsText,
		'Wikilink without pipe'
	);

	mw.messages.set( 'version-entrypoints-index-php', '[https://www.mediawiki.org/wiki/Manual:index.php index.php]' );
	assert.equal(
		parser( 'version-entrypoints-index-php' ),
		'<a href="https://www.mediawiki.org/wiki/Manual:index.php">index.php</a>',
		'External link'
	);

	// Not supported currently, but should not parse as text either.
	mw.messages.set( 'pipe-trick', '[[Tampa, Florida|]]' );
	assert.throws(
		parser( 'pipe-trick' ),
		Error,
		'Pipe trick is not supported'
	);

	expectedMultipleBars = $( '<a>' ).attr( {
		title: 'Main Page',
		href: mw.util.wikiGetlink( 'Main Page' )
	} ).text( 'Main|Page' ).getOuterHtml();
	mw.messages.set( 'multiple-bars', '[[Main Page|Main|Page]]' );
	assert.equal(
		parser( 'multiple-bars' ),
		expectedMultipleBars,
		'Bar in anchor'
	);

	specialCharactersPageName = '"Who" wants to be a millionaire & live on \'Exotic Island\'?';
	expectedSpecialCharacters = $( '<a>' ).attr( {
		title: specialCharactersPageName,
		href: mw.util.wikiGetlink( specialCharactersPageName )
	} ).text( specialCharactersPageName ).getOuterHtml();

	mw.messages.set( 'special-characters', '[[' + specialCharactersPageName + ']]' );
	assert.equal(
		parser( 'special-characters' ),
		expectedSpecialCharacters,
		'Special characters'
	);
});

QUnit.test( 'Int magic word', 4, function ( assert ) {
	var parser = mw.jqueryMsg.getMessageFunction(),
	    newarticletextSource = 'You have followed a link to a page that does not exist yet. To create the page, start typing in the box below (see the [[{{Int:Helppage}}|help page]] for more info). If you are here by mistake, click your browser\'s back button.',
		expectedNewarticletext;

	mw.messages.set( 'helppage', 'Help:Contents' );

	expectedNewarticletext = 'You have followed a link to a page that does not exist yet. To create the page, start typing in the box below (see the <a href="' +
		mw.util.wikiGetlink( mw.msg( 'helppage' ) ) +
		'">help page</a> for more info). If you are here by mistake, click your browser\'s back button.';

	mw.messages.set( 'newarticletext', newarticletextSource );

	assert.equal(
		parser( 'newarticletext' ),
		expectedNewarticletext,
		'Link with nested message'
	);

	mw.messages.set( 'portal-url', 'Project:Community portal' );
	mw.messages.set( 'see-portal-url', '{{Int:portal-url}} is an important community page.' );
	assert.equal(
		parser( 'see-portal-url' ),
		'Project:Community portal is an important community page.',
		'Nested message'
	);

	mw.messages.set( 'newarticletext-lowercase',
		newarticletextSource.replace( 'Int:Helppage', 'int:helppage' ) );

	assert.equal(
		parser( 'newarticletext-lowercase' ),
		expectedNewarticletext,
		'Link with nested message, lowercase include'
	);

	mw.messages.set( 'uses-missing-int', '{{int:doesnt-exist}}' );

	assert.equal(
		parser( 'uses-missing-int' ),
		'[doesnt-exist]',
		'int: where nested message does not exist'
	);
});

// Tests that getMessageFunction is used for messages with curly braces or square brackets,
// but not otherwise.
QUnit.test( 'Calls to mw.msg', 8, function ( assert ) {
	// Should be
	var map, oldGMF, outerCalled, innerCalled;

	map = new mw.Map();
	map.set( {
		'curly-brace': '{{int:message}}',
		'single-square-bracket': '[https://www.mediawiki.org/ MediaWiki]',
		'double-square-bracket': '[[Some page]]',
		'regular': 'Other message'
	} );

	oldGMF = mw.jqueryMsg.getMessageFunction;

	mw.jqueryMsg.getMessageFunction = function() {
		outerCalled = true;
		return function() {
			innerCalled = true;
		};
	};

	function verifyGetMessageFunction( key, shouldCall ) {
		outerCalled = false;
		innerCalled = false;
		( new mw.Message( map, key ) ).parser();
		assert.strictEqual( outerCalled, shouldCall, 'Outer function called for ' + key );
		assert.strictEqual( innerCalled, shouldCall, 'Inner function called for ' + key );
	}

	verifyGetMessageFunction( 'curly-brace', true );
	verifyGetMessageFunction( 'single-square-bracket', true );
	verifyGetMessageFunction( 'double-square-bracket', true );
	verifyGetMessageFunction( 'regular', false );

	mw.jqueryMsg.getMessageFunction = oldGMF;
} );

}( mediaWiki, jQuery ) );
