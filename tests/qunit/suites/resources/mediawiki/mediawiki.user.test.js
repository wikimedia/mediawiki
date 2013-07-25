( function ( mw, $ ) {
	QUnit.module( 'mediawiki.user', QUnit.newMwEnvironment() );

	QUnit.test( 'options', 1, function ( assert ) {
		assert.ok( mw.user.options instanceof mw.Map, 'options instance of mw.Map' );
	} );

	QUnit.test( 'user status', 11, function ( assert ) {
		/**
		 * Tests can be run under three different conditions:
		 *   1) From tests/qunit/index.html, user will be anonymous.
		 *   2) Logged in on [[Special:JavaScriptTest/qunit]]
		 *   3) Anonymously at the same special page.
		 */

		// Forge an anonymous user:
		mw.config.set( 'wgUserName', null );
		delete mw.config.values.wgUserId;

		assert.strictEqual( mw.user.getName(), null, 'user.getName() returns null when anonymous' );
		assert.strictEqual( mw.user.name(), null, 'user.name() compatibility' );
		assert.assertTrue( mw.user.isAnon(), 'user.isAnon() returns true when anonymous' );
		assert.assertTrue( mw.user.anonymous(), 'user.anonymous() compatibility' );
		assert.strictEqual( mw.user.getId(), 0, 'user.getId() returns 0 when anonymous' );

		// Not part of startUp module
		mw.config.set( 'wgUserName', 'John' );
		mw.config.set( 'wgUserId', 123 );

		assert.equal( mw.user.getName(), 'John', 'user.getName() returns username when logged-in' );
		assert.equal( mw.user.name(), 'John', 'user.name() compatibility' );
		assert.assertFalse( mw.user.isAnon(), 'user.isAnon() returns false when logged-in' );
		assert.assertFalse( mw.user.anonymous(), 'user.anonymous() compatibility' );
		assert.strictEqual( mw.user.getId(), 123, 'user.getId() returns correct ID when logged-in' );

		assert.equal( mw.user.id(), 'John', 'user.id Returns username when logged-in' );
	} );

	QUnit.asyncTest( 'getGroups', 3, function ( assert ) {
		mw.user.getGroups( function ( groups ) {
			// First group should always be '*'
			assert.equal( $.type( groups ), 'array', 'Callback gets an array' );
			assert.notStrictEqual( $.inArray( '*', groups ), -1, '"*"" is in the list' );
			// Sort needed because of different methods if creating the arrays,
			// only the content matters.
			assert.deepEqual( groups.sort(), mw.config.get( 'wgUserGroups' ).sort(), 'Array contains all groups, just like wgUserGroups' );
			QUnit.start();
		} );
	} );

	QUnit.asyncTest( 'getRights', 1, function ( assert ) {
		mw.user.getRights( function ( rights ) {
			assert.equal( $.type( rights ), 'array', 'Callback gets an array' );
			QUnit.start();
		} );
	} );
}( mediaWiki, jQuery ) );
