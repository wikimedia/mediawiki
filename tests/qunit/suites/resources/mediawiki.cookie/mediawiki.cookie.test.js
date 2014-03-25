/*global sinon */
( function ( QUnit, sinon, mw, $ ) {

	var config = {
			wgCookiePrefix: 'prefix',
			wgCookieDomain: 'domain',
			wgCookiePath: 'path',
			wgCookieSecure: true,
			wgCookieExpiration: 5678
		},
		lifecycle = QUnit.newMwEnvironment( {
			setup: function() {
				sinon.spy( $, 'cookie' );
			},
			teardown: function() {
				$.cookie.restore();
			},
			config: config
	} );

	QUnit.module( 'mediawiki.cookie', lifecycle );

	QUnit.test( 'should set the cookie with options from the config by default', 6, function ( assert ) {
		var args, options;

		mw.cookie.set( 'foo', 'bar' );

		args = $.cookie.getCall( 0 ).args;
		options = args[ 2 ];
		assert.equal( args[ 0 ], 'prefixfoo' );
		assert.equal( args[ 1 ], 'bar' );
		assert.equal( options.prefix, 'prefix' );
		assert.equal( options.domain, 'domain' );
		assert.equal( options.path, 'path' );
		assert.equal( options.secure, true );
	} );

	QUnit.test( 'should set the cookie with the provided options', 4, function ( assert ) {
		var options;

		mw.cookie.set( 'foo', 'bar', null, {
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: false
		} );

		options = $.cookie.getCall( 0 ).args[ 2 ];
		assert.equal( options.prefix, 'myPrefix' );
		assert.equal( options.domain, 'myDomain' );
		assert.equal( options.path, 'myPath' );
		assert.equal( options.secure, false );
	} );

	QUnit.test( 'should set a session cookie when expire is undefined', 1, function ( assert ) {
		var options;

		mw.cookie.set( 'foo', 'bar' );

		options = $.cookie.getCall( 0 ).args[ 2 ];
		assert.equal( options.expires, undefined );
	} );

	QUnit.test( 'should set the cookie to expire when expire isn\'t null', 1, function ( assert ) {
		var options;

		mw.cookie.set( 'foo', 'bar', 1234 );

		options = $.cookie.getCall( 0 ).args[ 2 ];
		assert.equal( $.type( options.expires ), 'date' );
		// TODO (phuedx, 2014-03-21) Assert that options.expires is
		// now-ish plus 1234 seconds.
	} );

	QUnit.test( 'should set the cookie to expire when expire is 0 and wgCookieExpiration isn\'t null', 1, function ( assert ) {
		var options;

		mw.cookie.set( 'foo', 'bar', 0 );

		options = $.cookie.getCall( 0 ).args[ 2 ];
		assert.equal( $.type( options.expires ), 'date' );
		// TODO (phuedx, 2014-03-21) Assert that options.expires is
		// now-ish plus 5678 seconds.
	} );

	QUnit.test( 'should set a session cookie when expire is 0 and wgCookieExpiration is 0', 1, function ( assert ) {
		var options;

		mw.config.set( 'wgCookieExpiration', 0 );

		mw.cookie.set( 'foo', 'bar', 0 );

		options = $.cookie.getCall( 0 ).args[ 2 ];
		assert.equal( options.expires, null );
	} );

	QUnit.test( 'should get the cookie using the default prefix when prefix is undefined', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo' );

		key = $.cookie.getCall( 0 ).args[ 0 ];
		assert.equal( key, 'prefixfoo' );
	} );

	QUnit.test( 'should get the cookie using the prefix when prefix isn\'t null', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo', 'bar' );

		key = $.cookie.getCall( 0 ).args[ 0 ];
		assert.equal( key, 'barfoo' );
	} );

	QUnit.test( 'should return the value of the cookie', 1, function ( assert ) {
		var $cookie = $.cookie,
			value;

		$.cookie = sinon.stub().returns( 'bar' );

		value = mw.cookie.get( 'foo' );
		assert.equal( value, 'bar' );

		$.cookie = $cookie;
	} );

	QUnit.test( 'should return the default value when the cookie isn\'t set', 1, function ( assert ) {
		var value;

		value = mw.cookie.get( 'foo', null, 'bar' );
		assert.equal( value, 'bar' );
	} );

	QUnit.test( 'shouldn\'t get cookie with the default prefix when the prefix is an empty string', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo', '' );

		key = $.cookie.getCall( 0 ).args[ 0 ];
		assert.equal( key, 'foo' );
	} );

} ( QUnit, sinon, mediaWiki, jQuery ) );
