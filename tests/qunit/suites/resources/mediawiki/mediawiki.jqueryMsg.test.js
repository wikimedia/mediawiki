module( 'mediawiki.jqueryMsg' );

test( '-- Initial check', function () {
	expect( 1 );
	ok( mw.jqueryMsg, 'mw.jqueryMsg defined' );
} );

test( 'mw.jqueryMsg Plural', function () {
	expect( 3 );
	var parser = mw.jqueryMsg.getMessageFunction();

	mw.messages.set( 'plural-msg', 'Found $1 {{PLURAL:$1|item|items}}' );
	equal( parser( 'plural-msg', 0 ), 'Found 0 items', 'Plural test for english with zero as count' );
	equal( parser( 'plural-msg', 1 ), 'Found 1 item', 'Singular test for english' );
	equal( parser( 'plural-msg', 2 ), 'Found 2 items', 'Plural test for english' );
} );


test( 'mw.jqueryMsg Gender', function () {
	expect( 11 );
	// TODO: These tests should be for mw.msg once mw.msg integrated with mw.jqueryMsg
	// TODO: English may not be the best language for these tests. Use a language like Arabic or Russian
	var user = mw.user,
		parser = mw.jqueryMsg.getMessageFunction();

	// The values here are not significant,
	// what matters is which of the values is choosen by the parser
	mw.messages.set( 'gender-msg', '$1: {{GENDER:$2|blue|pink|green}}' );

	user.options.set( 'gender', 'male' );
	equal(
		parser( 'gender-msg', 'Bob', 'male' ),
		'Bob: blue',
		'Masculine from string "male"'
	);
	equal(
		parser( 'gender-msg', 'Bob', user ),
		'Bob: blue',
		'Masculine from mw.user object'
	);

	user.options.set( 'gender', 'unknown' );
	equal(
		parser( 'gender-msg', 'Foo', user ),
		'Foo: green',
		'Neutral from mw.user object' );
	equal(
		parser( 'gender-msg', 'Alice', 'female' ),
		'Alice: pink',
		'Feminine from string "female"' );
	equal(
		parser( 'gender-msg', 'User' ),
		'User: green',
		'Neutral when no parameter given' );
	equal(
		parser( 'gender-msg', 'User', 'unknown' ),
		'User: green',
		'Neutral from string "unknown"'
	);

	mw.messages.set( 'gender-msg-one-form', '{{GENDER:$1|User}}: $2 {{PLURAL:$2|edit|edits}}' );

	equal(
		parser( 'gender-msg-one-form', 'male', 10 ),
		'User: 10 edits',
		'Gender neutral and plural form'
	);
	equal(
		parser( 'gender-msg-one-form', 'female', 1 ),
		'User: 1 edit',
		'Gender neutral and singular form'
	);

	mw.messages.set( 'gender-msg-lowercase', '{{gender:$1|he|she}} is awesome' );
	equal(
		parser( 'gender-msg-lowercase', 'male' ),
		'he is awesome',
		'Gender masculine'
	);
	equal(
		parser( 'gender-msg-lowercase', 'female' ),
		'she is awesome',
		'Gender feminine'
	);

	mw.messages.set( 'gender-msg-wrong', '{{gender}} test' );
	equal(
		parser( 'gender-msg-wrong', 'female' ),
		' test',
		'Invalid syntax should result in {{gender}} simply being stripped away'
	);
} );


test( 'mw.jqueryMsg Grammar', function () {
	expect( 2 );
	var parser = mw.jqueryMsg.getMessageFunction();

	// Assume the grammar form grammar_case_foo is not valid in any language
	mw.messages.set( 'grammar-msg', 'Przeszukaj {{GRAMMAR:grammar_case_foo|{{SITENAME}}}}' );
	equal( parser( 'grammar-msg' ), 'Przeszukaj ' + mw.config.get( 'wgSiteName' ), 'Grammar Test with sitename' );

	mw.messages.set( 'grammar-msg-wrong-syntax', 'Przeszukaj {{GRAMMAR:grammar_case_xyz}}' );
	equal( parser( 'grammar-msg-wrong-syntax' ), 'Przeszukaj ' , 'Grammar Test with wrong grammar template syntax' );
} );
