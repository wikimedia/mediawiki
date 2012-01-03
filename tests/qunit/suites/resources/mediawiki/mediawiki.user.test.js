module( 'mediawiki.user', QUnit.newMwEnvironment() );

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

	/**
	 * Tests can be run under three different conditions:
	 *   1) From tests/qunit/index.html, user will be anonymous.
	 *   2) Logged in on [[Special:JavaScriptTest/qunit]]
	 *   3) Anonymously at the same special page.
	 */

	// Forge an anonymous user:
	mw.config.set( 'wgUserName', null);

	strictEqual( mw.user.name(), null, 'user.name should return null when anonymous' );
	ok( mw.user.anonymous(), 'user.anonymous should reutrn true when anonymous' );

	// Not part of startUp module
	mw.config.set( 'wgUserName', 'John' );

	equal( mw.user.name(), 'John', 'user.name returns username when logged-in' );
	ok( !mw.user.anonymous(), 'user.anonymous returns false when logged-in' );

	equal( mw.user.id(), 'John', 'user.id Returns username when logged-in' );
});
