( function ( QUnit, mw, $ ) {

	var now = 9012; // (ms)

	QUnit.module( 'mediawiki.cookie', QUnit.newMwEnvironment( {
		setup: function () {
			this.spy( $, 'cookie' );

			this.sandbox.useFakeTimers( now );
		},
		config: {
			wgCookiePrefix: 'prefix',
			wgCookieDomain: 'domain',
			wgCookiePath: 'path',
			wgCookieSecure: true,
			wgCookieExpiration: 5678 // (s)
		}
	} ) );

	QUnit.test( 'should set the cookie with options from the config by default', 5, function ( assert ) {
		var args, options;

		mw.cookie.set( 'foo', 'bar' );

		args = $.cookie.lastCall.args;
		options = args[ 2 ];
		assert.strictEqual( args[ 0 ], 'prefixfoo' );
		assert.strictEqual( args[ 1 ], 'bar' );
		assert.strictEqual( options.domain, 'domain' );
		assert.strictEqual( options.path, 'path' );
		assert.strictEqual( options.secure, true );
	} );

	QUnit.test( 'should set the cookie with the provided options', 3, function ( assert ) {
		var options;

		mw.cookie.set( 'foo', 'bar', null, {
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: false
		} );

		options = $.cookie.lastCall.args[ 2 ];
		assert.strictEqual( options.domain, 'myDomain' );
		assert.strictEqual( options.path, 'myPath' );
		assert.strictEqual( options.secure, false );
	} );

	QUnit.test( 'shouldn\'t pass through invalid options to jQuery.cookie', 1, function( assert ) {
		var options;

		mw.cookie.set( 'foo', 'bar', null, {
			invalid: true,
		} );

		options = $.cookie.lastCall.args[ 2 ];
		assert.strictEqual( options.invalid, undefined );
	} );

	QUnit.test( 'should use expire as the cookie expiry date when expire is a Date', 1, function ( assert ) {
		var expire, expires;

		expire = new Date( 1234 );
		mw.cookie.set( 'foo', 'bar', expire );

		expires = $.cookie.lastCall.args[ 2 ].expires;
		assert.strictEqual( expires, expire );
	} );

	QUnit.test( 'should calculate when the cookie expires from wgCookieExpiration when expire is undefined', 2, function ( assert ) {
		var expires, expectedExpires;

		mw.cookie.set( 'foo', 'bar' );

		expires = $.cookie.lastCall.args[ 2 ].expires;
		expectedExpires = now + ( 5678 * 1000 );
		assert.strictEqual( $.type( expires ), 'date' );
		assert.strictEqual( expires.getTime(), expectedExpires );
	} );

	QUnit.test( 'should set a session cookie when expires is null', 1, function( assert ) {
		var expires;

		mw.cookie.set( 'foo', 'bar', null );

		expires = $.cookie.lastCall.args[ 2 ].expires;
		assert.strictEqual( expires, null );
	} );

	QUnit.test( 'should delete the cookie when the value is an empty string', 1, function ( assert ) {
		var value;

		mw.cookie.set( 'foo', '' );

		value = $.cookie.lastCall.args[ 1 ];
		assert.strictEqual( value, null );
	} );

	QUnit.test( 'should get the cookie using the default prefix when prefix is undefined', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo' );

		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'prefixfoo' );
	} );

	QUnit.test( 'should get the cookie using the prefix when prefix isn\'t null', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo', 'bar' );

		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'barfoo' );
	} );

	QUnit.test( 'should return the value of the cookie', 1, function ( assert ) {
		var $cookie = $.cookie,
			value;

		$.cookie = sinon.stub().returns( 'bar' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, 'bar' );

		$.cookie = $cookie;
	} );

	QUnit.test( 'should return the default value when the cookie isn\'t set', 1, function ( assert ) {
		var value;

		value = mw.cookie.get( 'foo', null, 'bar' );
		assert.strictEqual( value, 'bar' );
	} );

	QUnit.test( 'shouldn\'t get the cookie with the default prefix when the prefix is an empty string', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo', '' );

		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'foo' );
	} );

} ( QUnit, mediaWiki, jQuery ) );
