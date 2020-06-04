'use strict';

const { action, assert } = require( 'api-testing' );

describe( "Changing a user's preferences", function () {
	let alice;

	before( async () => {
		alice = await action.alice();
	} );

	it( 'should get users default date settings', async () => {
		const result = await alice.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( result.options.date, 'default' );
	} );

	it( 'should change date preference from default to dmy', async () => {
		const token = await alice.token();
		const result = await alice.action( 'options', { change: 'date=dmy', token }, 'POST' );

		assert.equal( result.options, 'success' );
	} );

	it( 'should get users updated date preference', async () => {
		const result = await alice.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( result.options.date, 'dmy' );
	} );

} );
