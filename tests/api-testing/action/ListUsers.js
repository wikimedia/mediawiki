'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Listing Users', function () {
	let prefix;

	// users
	const user1 = action.getAnon();
	const user2 = action.getAnon();
	const user3 = action.getAnon();

	before( async () => {
		prefix = await utils.title();
		prefix = prefix.substring( 0, 7 );

		// NOTE: Because of T199393, the accounts have to be created sequentially.
		// Doing so in parallel triggers a race condition that often results in a DBQueryError.
		await user1.account( `${prefix}1` );
		await user2.account( `${prefix}2` );
		await user3.account( `${prefix}3` );
	} );

	it( 'should get a list of registered users that begin with a given prefix', async () => {
		const result = await user1.list( 'allusers', { auprefix: prefix.charAt( 0 ).toUpperCase() + prefix.slice( 1 ) } );

		assert.sameDeepMembers( result, [
			{ name: user1.username, userid: user1.userid },
			{ name: user2.username, userid: user2.userid },
			{ name: user3.username, userid: user3.userid }
		] );
		assert.lengthOf( result, 3 );
	} );
} );
