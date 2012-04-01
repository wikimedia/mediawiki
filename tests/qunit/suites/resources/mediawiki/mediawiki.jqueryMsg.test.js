( function ( mw, $ ) {

QUnit.module( 'mediawiki.jqueryMsg', QUnit.newMwEnvironment( {
	setup: function () {
		this.orgMwLangauge = mw.language;
		mw.language = $.extend( true, {}, this.orgMwLangauge );
	},
	teardown: function () {
		mw.language = this.orgMwLangauge;
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


QUnit.test( 'Links', 5, function ( assert ) {
	var parser = mw.jqueryMsg.getMessageFunction();

	mw.messages.set( 'wikilink-msg', 'Click [[http://en.wikipedia.org here]]' );
	assert.equal( parser( 'wikilink-msg' ), 'Click unimplemented',
		'Wiki link is unimplemented' );

	mw.messages.set( 'link-msg', 'Click [http://en.wikipedia.org here]' );
	assert.equal( parser( 'link-msg' ),
		'Click <a href=\"http://en.wikipedia.org\">here</a>', 'Exernal link parsed' );

	mw.messages.set( 'link-msg-with-args', 'Click [$1]' );
	assert.equal( parser( 'link-msg-with-args', 'http://en.wikipedia.org' ),
		'Click http://en.wikipedia.org', 'Exernal link parsed with single argument' );

	mw.messages.set( 'link-msg-with-two-args', 'Click [$1 $2]' );
	assert.equal( parser( 'link-msg-with-two-args', 'http://en.wikipedia.org', 'here' ),
		'Click <a href=\"http://en.wikipedia.org\">here</a>',
		'Exernal link parsed with two arguments' );

	assert.equal( parser( 'link-msg-with-two-args',
		function ( e ) {
			alert( 'Did you click me?' );
		},
		'here'
		),
		'Click <a href="#">here</a>',
		'Exernal link parsed with two arguments, second as a click handler' );

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

}( mediaWiki, jQuery ) );
