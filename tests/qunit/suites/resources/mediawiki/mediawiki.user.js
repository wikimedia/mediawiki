module( 'mediawiki.user.js' );

test( '-- Initial check', function() {
	expect(1);

	ok( mw.user, 'mw.user defined' );
});


test( 'options', function() {
	expect(1);

	ok( mw.user.options instanceof mw.Map, 'options instance of mw.Map' );
});

test( 'User login status', function() {
	expect(5);

	strictEqual( mw.user.name(), null, 'user.name should return null when anonymous' );
	ok( mw.user.anonymous(), 'user.anonymous should reutrn true when anonymous' );

	// Not part of startUp module
	mw.config.set( 'wgUserName', 'John' );

	equal( mw.user.name(), 'John', 'user.name returns username when logged-in' );
	ok( !mw.user.anonymous(), 'user.anonymous returns false when logged-in' );

	equal( mw.user.id(), 'John', 'user.id Returns username when logged-in' );
});
