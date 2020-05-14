'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Recent Changes', function () {
	const title = utils.title( 'Recent_Changes_' );
	let alice;

	before( async () => {
		alice = await action.alice();
	} );

	it( 'should create page and get new page recent changes', async () => {
		const edit = await alice.edit( title, { text: 'Recent changes testing', createonly: true } );
		const results = await alice.list( 'recentchanges', { rctype: 'new', rctitle: title } );

		assert.equal( results[ 0 ].type, 'new' );
		assert.sameTitle( results[ 0 ].title, title );
		assert.equal( results[ 0 ].pageid, edit.pageid );
		assert.equal( results[ 0 ].revid, edit.newrevid );
	} );

	it( 'should edit page and get most recent edit changes', async () => {
		const rev1 = await alice.edit( title, { text: 'Recent changes testing..R1' } );
		const results = await alice.list( 'recentchanges', { rctype: 'edit', rctitle: title } );

		assert.equal( results[ 0 ].type, 'edit' );
		assert.sameTitle( results[ 0 ].title, title );
		assert.equal( results[ 0 ].pageid, rev1.pageid );
		assert.equal( results[ 0 ].revid, rev1.newrevid );
	} );
} );
