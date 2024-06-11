'use strict';

const { action, assert } = require( 'api-testing' );

describe( "Changing a user's preferences", () => {
	let alice;
	let token;

	before( async () => {
		alice = await action.alice();
		token = await alice.token();
	} );

	it( 'should get users default date settings', async () => {
		const info = await alice.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( info.options.date, 'default' );
	} );

	it( 'should change date preference from default to dmy', async () => {
		const result = await alice.action( 'options', { change: 'date=dmy', token }, 'POST' );

		assert.equal( result.options, 'success' );
	} );

	it( 'should get users updated date preference', async () => {
		const info = await alice.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( info.options.date, 'dmy' );
	} );

	it( 'should allow arbitrary userjs option', async () => {
		const result = await alice.action( 'options', {
			change: 'userjs-api-test=Hello World',
			token
		}, 'POST' );
		assert.equal( result.options, 'success' );

		const info = await alice.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( info.options[ 'userjs-api-test' ], 'Hello World' );
	} );

	it( 'should not be able to set arbitrary option', async () => {
		await alice.action( 'options', {
			change: 'api-test-yadda=Hello World',
			token
		}, 'POST' );

		const info = await alice.meta( 'userinfo', { uiprop: 'options' } );
		assert.notProperty( info.options, 'api-test-yadda' );
	} );

} );
