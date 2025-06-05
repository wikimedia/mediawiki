( function () {

	// Dummy variables
	const funky = function () {};
	const arry = [];

	QUnit.module( 'mw.Map' );

	QUnit.test( 'Store simple string key', ( assert ) => {
		const conf = new mw.Map();

		assert.true( conf.set( 'foo', 'Bar' ), 'set' );
		assert.strictEqual( conf.get( 'foo' ), 'Bar', 'get' );
	} );

	QUnit.test( 'Store number-like key', ( assert ) => {
		const conf = new mw.Map();

		assert.true( conf.set( '42', 'X' ), 'set' );
		assert.strictEqual( conf.get( '42' ), 'X', 'get' );
	} );

	QUnit.test( 'get()', ( assert ) => {
		let conf = new mw.Map();

		assert.strictEqual( conf.get( 'example' ), null, 'default fallback' );
		assert.strictEqual( conf.get( 'example', arry ), arry, 'array fallback' );
		assert.strictEqual( conf.get( 'example', funky ), funky, 'function fallback' );
		assert.strictEqual( conf.get( 'example', undefined ), undefined, 'undefined fallback' );

		// Numbers are not valid keys. Ignore any stored keys that could match after casting.
		assert.true( conf.set( '7', 'I used to be a number' ) );
		assert.strictEqual( conf.get( 7 ), null, 'ignore number key (single)' );
		assert.deepEqual( conf.get( [ 7 ] ), {}, 'ignore number key (multiple)' );
		assert.strictEqual( conf.get( 7, 42 ), 42, 'ignore number key (fallback)' );

		// Functions are not valid keys.
		assert.true( conf.set( String( funky ), 'I used to be a function' ) );
		assert.strictEqual( conf.get( funky ), null, 'ignore function key' );

		conf = new mw.Map();
		conf.set( 'foo', 'bar' );
		conf.set( 'x', [ 'y', 'z' ] );
		conf.set( 'num', 7 );
		conf.set( 'num2', 42 );
		assert.deepEqual(
			conf.get( [ 'foo', 'x', 'num' ] ),
			{ foo: 'bar', x: [ 'y', 'z' ], num: 7 },
			'get multiple'
		);
		assert.deepEqual(
			conf.get( [ 'foo', 'bar', 'x', 'y' ] ),
			{ foo: 'bar', bar: null, x: [ 'y', 'z' ], y: null },
			'get multiple with some unknown'
		);

		assert.propEqual(
			conf.get(),
			{ foo: 'bar', x: [ 'y', 'z' ], num: 7, num2: 42 },
			'get all values'
		);
	} );

	// Expose 'values' getter with all values, for developer convenience on the console
	QUnit.test( 'values', ( assert ) => {
		const conf = new mw.Map();
		conf.set( { num: 7, num2: 42 } );
		conf.set( 'foo', 'bar' );

		assert.propEqual( conf.values, { num: 7, num2: 42, foo: 'bar' } );
	} );

	QUnit.test( 'set()', ( assert ) => {
		const conf = new mw.Map();

		// There should not be an implied default value
		assert.false( conf.set( 'no-value' ), 'reject without value argument' );

		assert.false( conf.set( funky, 'Funky' ), 'reject Function key' );
		assert.false( conf.set( arry, 'Arry' ), 'reject Array key' );
		assert.false( conf.set( 7, 'Nummy' ), 'reject number key' );
		assert.false( conf.set( null, 'Null' ), 'reject null key' );
		assert.false( conf.set( {}, 'Object' ), 'reject plain object as key' );

		// Support storing `undefined`, get() will not return the default.
		assert.true( conf.set( 'example', undefined ), 'Store the undefined value' );
		assert.strictEqual( conf.get( 'example' ), undefined, 'Get the undefined value' );
		assert.strictEqual( conf.get( 'example', 42 ), undefined, 'Get the undefined value (ignore default)' );

		assert.true( conf.set( { key1: { x: 'x' }, key2: [ 'y' ] } ), 'set multiple' );
		assert.deepEqual( conf.get( 'key1' ), { x: 'x' } );
		assert.deepEqual( conf.get( 'key2' ), [ 'y' ] );
	} );

	QUnit.test( 'exists()', ( assert ) => {
		const conf = new mw.Map();

		assert.false( conf.exists( 'doesNotExist' ), 'unknown' );

		assert.true( conf.set( 'undef', undefined ) );
		assert.true( conf.exists( 'undef' ), 'known with undefined value' );

		assert.true( conf.set( '7', 42 ) );
		assert.false( conf.exists( 7 ), 'unknown, with known number-like key' );
	} );

	// Confirm protection against Object.prototype inheritance
	QUnit.test( 'Avoid prototype pollution', ( assert ) => {
		const conf = new mw.Map();

		assert.strictEqual( conf.get( 'constructor' ), null, 'Get unknown "constructor"' );
		assert.strictEqual( conf.get( 'hasOwnProperty' ), null, 'Get unkonwn "hasOwnProperty"' );

		conf.set(
			'hasOwnProperty',
			() => true
		);
		assert.strictEqual( conf.get( 'example', 'missing' ), 'missing', 'Use original hasOwnProperty method (positive)' );

		conf.set( 'example', 'Foo' );
		conf.set(
			'hasOwnProperty',
			() => false
		);
		assert.strictEqual( conf.get( 'example' ), 'Foo', 'Use original hasOwnProperty method (negative)' );

		assert.true( conf.set( 'constructor', 42 ), 'Set "constructor"' );
		assert.strictEqual( conf.get( 'constructor' ), 42, 'Get "constructor"' );
	} );
}() );
