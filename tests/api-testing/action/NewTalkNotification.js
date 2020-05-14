'use strict';

const { action, assert } = require( 'api-testing' );

describe( 'Testing a new talk page notification', function () {
	let kam = action.getAnon();
	let alice;

	before( async () => {
		[ alice, kam ] = await Promise.all( [
			action.alice(),
			kam.account( 'Kam_' )
		] );
	} );

	it( 'should edit a user\'s talk page', async () => {
		await alice.edit( `User_talk:${kam.username}`, { text: 'Hi, Kam! ~~~~', summary: 'saying hello', createonly: true } );
	} );

	it( 'user should have a new message notification', async () => {
		// FIXME: https://phabricator.wikimedia.org/T230211
		const result = await kam.meta( 'userinfo', { uiprop: 'hasmsg' } );

		assert.exists( result.messages );
		assert.equal( result.name, kam.username );
		assert.equal( result.id, kam.userid );
	} );
} );
