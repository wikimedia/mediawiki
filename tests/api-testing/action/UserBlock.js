'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The block/unblock action', function testBlockingAUser() {
	const pageTitle = utils.title( 'Block_' );
	let eve = action.getAnon();
	let mindy;

	before( async () => {
		[ eve, mindy ] = await Promise.all( [
			eve.account( 'Eve_' ),
			action.mindy()
		] );
	} );

	it( 'allows an admin to block a user', async () => {
		await eve.edit( pageTitle, { text: 'One', summary: 'first' } );

		const result = await mindy.action( 'block', {
			user: eve.username,
			reason: 'testing',
			token: await mindy.token()
		}, 'POST' );

		assert.exists( result.block.id );
		assert.equal( result.block.userID, eve.userid );
		assert.equal( result.block.user, eve.username );
	} );

	it( 'prevents a blocked user from editing', async () => {
		const error = await eve.actionError(
			'edit',
			{
				title: pageTitle,
				text: 'Two',
				summary: 'second',
				token: await eve.token( 'csrf' )
			},
			'POST'
		);
		assert.equal( error.code, 'blocked' );
	} );

	it( 'allows an admin to unblock a user', async () => {
		const result = await mindy.action( 'unblock', {
			user: eve.username,
			reason: 'testing',
			token: await mindy.token()
		}, 'POST' );

		assert.exists( result.unblock.id );
		assert.equal( result.unblock.userid, eve.userid );
		assert.equal( result.unblock.user, eve.username );
	} );

	it( 'allows a user to edit after being unblocked', async () => {
		await eve.edit( pageTitle, { text: 'Three', summary: 'third' } );
	} );
} );
