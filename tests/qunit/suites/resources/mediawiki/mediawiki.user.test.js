( function ( mw ) {

QUnit.module( 'mediawiki.user', QUnit.newMwEnvironment() );

QUnit.test( 'options', 1, function ( assert ) {
	assert.ok( mw.user.options instanceof mw.Map, 'options instance of mw.Map' );
});

QUnit.test( 'User login status', 5, function ( assert ) {
	/**
	 * Tests can be run under three different conditions:
	 *   1) From tests/qunit/index.html, user will be anonymous.
	 *   2) Logged in on [[Special:JavaScriptTest/qunit]]
	 *   3) Anonymously at the same special page.
	 */

	// Forge an anonymous user:
	mw.config.set( 'wgUserName', null);

	assert.strictEqual( mw.user.name(), null, 'user.name should return null when anonymous' );
	assert.ok( mw.user.anonymous(), 'user.anonymous should reutrn true when anonymous' );

	// Not part of startUp module
	mw.config.set( 'wgUserName', 'John' );

	assert.equal( mw.user.name(), 'John', 'user.name returns username when logged-in' );
	assert.ok( !mw.user.anonymous(), 'user.anonymous returns false when logged-in' );

	assert.equal( mw.user.id(), 'John', 'user.id Returns username when logged-in' );
});

}( mediaWiki ) );
