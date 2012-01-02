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
	equal( parser('plural-msg', 0 ) , 'Found 0 items', 'Plural test for english with zero as count' );
	equal( parser('plural-msg', 1 ) , 'Found 1 item', 'Singular test for english' );
	equal( parser('plural-msg', 2 ) , 'Found 2 items', 'Plural test for english' );
} );

