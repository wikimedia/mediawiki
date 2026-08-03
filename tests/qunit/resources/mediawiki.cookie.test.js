( function () {

	let jqcookie;
	const NOW = 9012, // milliseconds
		DEFAULT_DURATION = 5678, // seconds
		defaults = {
			prefix: 'mywiki',
			domain: 'example.org',
			path: '/path',
			expires: DEFAULT_DURATION,
			secure: false
		},
		mwCookie = require( 'mediawiki.cookie' ),
		setDefaults = mwCookie.setDefaults,
		expiryDate = new Date();

	expiryDate.setTime( NOW + ( DEFAULT_DURATION * 1000 ) );

	QUnit.module( 'mediawiki.cookie', {
		beforeEach: function () {
			jqcookie = sinon.stub( mwCookie.jar, 'cookie' ).returns( null );
			this.clock = sinon.useFakeTimers( NOW );
			this.savedDefaults = setDefaults( defaults );
		},
		afterEach: function () {
			jqcookie.restore();
			this.clock.restore();
			setDefaults( this.savedDefaults );
		}
	} );

	QUnit.test( 'set( key, value )', ( assert ) => {
		let call;

		// Simple case
		mw.cookie.set( 'foo', 'bar' );

		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'mywikifoo' );
		assert.strictEqual( call[ 1 ], 'bar' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'example.org',
			path: '/path',
			secure: false
		} );

		mw.cookie.set( 'foo', null );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], null, 'null removes cookie' );

		mw.cookie.set( 'foo', undefined );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], 'undefined', 'undefined is value' );

		mw.cookie.set( 'foo', false );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], 'false', 'false is a value' );

		mw.cookie.set( 'foo', 0 );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], '0', '0 is value' );
	} );

	QUnit.test( 'set( key, value, expires )', ( assert ) => {
		let date, options;

		date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar' );
		options = jqcookie.lastCall.args[ 2 ];
		assert.deepEqual( options.expires, expiryDate, 'default expiration' );

		mw.cookie.set( 'foo', 'bar', date );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, date, 'custom expiration as Date' );

		date = new Date();
		date.setDate( date.getDate() + 1 );

		mw.cookie.set( 'foo', 'bar', 86400 );
		options = jqcookie.lastCall.args[ 2 ];
		assert.deepEqual( options.expires, date, 'custom expiration as lifetime in seconds' );

		mw.cookie.set( 'foo', 'bar', null );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, undefined, 'null forces session cookie' );

		// Per MainConfigSchema, if the CookieExpiration setting is 0,
		// then the default should be session cookies
		setDefaults( Object.assign( {}, defaults, { expires: 0 } ) );

		mw.cookie.set( 'foo', 'bar' );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, undefined, 'wgCookieExpiration=0 results in session cookies by default' );

		mw.cookie.set( 'foo', 'bar', date );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, date, 'custom expiration (with wgCookieExpiration=0)' );
	} );

	QUnit.test( 'set( key, value, options )', ( assert ) => {
		mw.cookie.set( 'foo', 'bar', {
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		let call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		}, 'Options (without expires)' );

		const date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar', {
			expires: date,
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		assert.strictEqual( jqcookie.callCount, 2 );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: date,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		}, 'Options (incl. expires)' );
	} );

	QUnit.test( 'get( key ) [default]', ( assert ) => {
		let key, value;

		mw.cookie.get( 'foo' );

		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Default prefix' );

		mw.cookie.get( 'foo', undefined );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for undefined' );

		mw.cookie.get( 'foo', null );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for null' );

		mw.cookie.get( 'foo', '' );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'foo', 'Don\'t use default prefix for empty string' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, null, 'Return null by default' );

		value = mw.cookie.get( 'foo', null, 'bar' );
		assert.strictEqual( value, 'bar', 'Custom default value' );
	} );

	QUnit.test( 'get( key ) [value]', ( assert ) => {
		jqcookie.returns( 'bar' );

		const value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, 'bar', 'Return value of cookie' );
	} );

	QUnit.test( 'get( key, prefix )', ( assert ) => {
		mw.cookie.get( 'foo', 'bar' );

		const key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'barfoo' );
	} );

	QUnit.test.each( 'jar', {
		simple: [ 'foo', 'bar' ],
		empty: [ 'foo', '' ],
		equals: [ 'foo', 'aaa=bbb' ],
		'quote in key': [ '"got', 'away from me' ],
		'quote in value around': [ 'foo', '"bar"' ],
		'quote in value middle': [ 'foo', 'I did not touch the "yellow" pencil.' ],
		'quote in value begin T143304': [ 'foo', '"quux' ]
	}, ( assert, [ name, value ] ) => {
		jqcookie.restore();
		mwCookie.jar.removeCookie( name );

		mwCookie.jar.cookie( name, value );
		assert.strictEqual( mwCookie.jar.cookie( name ), value, 'Return value' );

		mwCookie.jar.removeCookie( name );
	} );

	// https://github.com/carhartl/jquery-cookie/issues/50
	QUnit.test( 'jar [raw]', ( assert ) => {
		jqcookie.restore();

		mwCookie.jar.cookie.raw = true;
		document.cookie = 'foo=%20val';
		assert.strictEqual( mwCookie.jar.cookie( 'foo' ), '%20val', 'Return value' );
		document.cookie = 'foo=aaa=bbb%20';
		assert.strictEqual( mwCookie.jar.cookie( 'foo' ), 'aaa=bbb%20', 'Return value' );

		mwCookie.jar.removeCookie( 'foo' );
		delete mwCookie.jar.cookie.raw;
	} );

	QUnit.test( 'jar [default]', ( assert ) => {
		jqcookie.restore();
		mwCookie.jar.removeCookie( 'foo' );

		assert.strictEqual( mwCookie.jar.cookie( 'foo' ), null, 'Return value' );

		mwCookie.jar.removeCookie( 'foo' );
	} );

	QUnit.test( 'jar [JSON simple]', ( assert ) => {
		jqcookie.restore();
		mwCookie.jar.removeCookie( 'foo' );

		mwCookie.jar.cookie.json = true;
		mwCookie.jar.cookie( 'foo', { bar: 'quux' } );
		assert.deepEqual( mwCookie.jar.cookie( 'foo' ), { bar: 'quux' }, 'Return value' );

		mwCookie.jar.removeCookie( 'foo' );
		delete mwCookie.jar.cookie.json;
	} );

	QUnit.test( 'jar [JSON default]', ( assert ) => {
		jqcookie.restore();
		mwCookie.jar.removeCookie( 'foo' );

		mwCookie.jar.cookie.json = true;
		assert.strictEqual( mwCookie.jar.cookie( 'foo' ), null, 'Return value' );

		mwCookie.jar.removeCookie( 'foo' );
		delete mwCookie.jar.cookie.json;
	} );

	// https://github.com/carhartl/jquery-cookie/issues/132
	// https://github.com/carhartl/jquery-cookie/pull/145
	QUnit.test( 'jar [JSON invalid]', ( assert ) => {
		jqcookie.restore();
		mwCookie.jar.removeCookie( 'foo' );

		mwCookie.jar.cookie( 'foo', 'quux' );
		mwCookie.jar.cookie.json = true;
		assert.strictEqual( mwCookie.jar.cookie( 'foo' ), undefined, 'Return value' );

		mwCookie.jar.removeCookie( 'foo' );
		delete mwCookie.jar.cookie.json;
	} );

}() );
