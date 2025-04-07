'use strict';

const { action, assert } = require( 'api-testing' );

describe( 'Block', () => {
	const ip = '::' + Math.floor( Math.random() * 65534 ).toString( 16 );
	it( 'should not allow multiblocks without newblock (T389028)', async () => {
		const mindy = await action.mindy();
		const token = await mindy.token();
		const promises = [
			mindy.request( { action: 'block', user: ip, token: token }, 'POST' ),
			mindy.request( { action: 'block', user: ip, token: token }, 'POST' )
		];
		const res = await Promise.all( promises );
		assert.lengthOf( res, 2 );
		assert.equal( res[ 0 ].status, 200 );
		assert.equal( res[ 1 ].status, 200 );

		const goodIndex = 'block' in res[ 0 ].body ? 0 : 1;
		const goodBody = res[ goodIndex ].body;
		const badBody = res[ +!goodIndex ].body;

		assert.property( goodBody, 'block' );
		assert.isOk( goodBody.block.id );

		assert.property( badBody, 'error' );
		assert.equal( badBody.error.code, 'alreadyblocked' );
	} );
} );
