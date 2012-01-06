module( 'mediawiki.jqueryMsg' );

test( '-- Initial check', function() {
	expect( 1 );
	ok( mw.jqueryMsg, 'mw.jqueryMsg defined' );
} );

test( 'mw.jqueryMsg Plural', function() {
	expect( 5 );
	var parser = mw.jqueryMsg.getMessageFunction();
	ok( parser, 'Parser Function initialized' );
	ok( mw.messages.set( 'plural-msg', 'Found $1 {{PLURAL:$1|item|items}}' ), 'mw.messages.set: Register' );
	equal( parser( 'plural-msg', 0 ) , 'Found 0 items', 'Plural test for english with zero as count' );
	equal( parser( 'plural-msg', 1 ) , 'Found 1 item', 'Singular test for english' );
	equal( parser( 'plural-msg', 2 ) , 'Found 2 items', 'Plural test for english' );
} );


test( 'mw.jqueryMsg Gender', function() {
	expect( 16 );
	//TODO: These tests should be for mw.msg once mw.msg integrated with mw.jqueryMsg
	var user = mw.user;
	user.options.set( 'gender', 'male' );
	var parser = mw.jqueryMsg.getMessageFunction();
	ok( parser, 'Parser Function initialized' );
	//TODO: English may not be the best language for these tests. Use a language like Arabic or Russian
	ok( mw.messages.set( 'gender-msg', '$1 reverted {{GENDER:$2|his|her|their}} last edit' ), 'mw.messages.set: Register' );
	equal( parser( 'gender-msg', 'Bob', 'male' ) , 'Bob reverted his last edit', 'Gender masculine' );
	equal( parser( 'gender-msg', 'Bob', user ) , 'Bob reverted his last edit', 'Gender masculine' );
	user.options.set( 'gender', 'unknown' );
	equal( parser( 'gender-msg', 'They', user ) , 'They reverted their last edit', 'Gender neutral or unknown' );
	equal( parser( 'gender-msg', 'Alice', 'female' ) , 'Alice reverted her last edit', 'Gender feminine' );
	equal( parser( 'gender-msg', 'User' ) , 'User reverted their last edit', 'Gender neutral' );
	equal( parser( 'gender-msg', 'User', 'unknown' ) , 'User reverted their last edit', 'Gender neutral' );
	ok( mw.messages.set( 'gender-msg-one-form', '{{GENDER:$1|User}} reverted last $2 {{PLURAL:$2|edit|edits}}' ), 'mw.messages.set: Register' );
	equal( parser( 'gender-msg-one-form', 'male', 10 ) , 'User reverted last 10 edits', 'Gender neutral and plural form' );
	equal( parser( 'gender-msg-one-form', 'female', 1 ) , 'User reverted last 1 edit', 'Gender neutral and singular form' );
	ok( mw.messages.set( 'gender-msg-lowercase', '{{gender:$1|he|she}} is awesome' ), 'mw.messages.set: Register' );
	equal( parser( 'gender-msg-lowercase', 'male' ) , 'he is awesome', 'Gender masculine' );
	equal( parser( 'gender-msg-lowercase', 'female' ) , 'she is awesome', 'Gender feminine' );
	ok( mw.messages.set( 'gender-msg-wrong', '{{gender}} is awesome' ), 'mw.messages.set: Register' );
	equal( parser( 'gender-msg-wrong', 'female' ) , ' is awesome', 'Wrong syntax used, but ignore the {{gender}}' );
} );
