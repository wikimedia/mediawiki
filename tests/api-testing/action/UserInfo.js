'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( "Changing a user's `editfont` option", function getUserOptions() {
	let Rick = action.getAnon();
	const randomPage = utils.title( 'random' );
	const text = 'Random text';

	before( async () => {
		Rick = await Rick.account( 'Rick_' );
	} );

	it( 'should make edits to a page and update the editcount', async () => {
		const result = await Rick.meta( 'userinfo', { uiprop: 'editcount' } );
		const editcount = result.editcount;

		await Rick.edit( randomPage, text );
		await Rick.edit( randomPage, text );
		await Rick.edit( randomPage, text );

		const updatedResult = await Rick.meta( 'userinfo', { uiprop: 'editcount' } );
		const updatedCount = updatedResult.editcount;

		assert.equal( updatedCount, editcount + 3 );
	} );

	it( 'should get users default editfont preference', async () => {
		const result = await Rick.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( result.options.editfont, 'monospace' );
	} );

	it( 'should change editfont preference from monospace to serif', async () => {
		const token = await Rick.token();
		const result = await Rick.action( 'options', { change: 'editfont=serif', token }, 'POST' );

		assert.equal( result.options, 'success' );
	} );

	it( 'should get users updated editfont preference', async () => {
		const result = await Rick.meta( 'userinfo', { uiprop: 'options' } );
		assert.equal( result.options.editfont, 'serif' );
	} );
} );
