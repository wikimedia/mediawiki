'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Testing Revisions', function () {
	const title = utils.title( 'Revision_' );
	const revisions = {};
	let alice;

	before( async () => {
		alice = await action.alice();

		const create = await alice.edit( title, { text: 'Creating revision page ...', createonly: true, summary: 'create revision page' } );
		revisions.create = create.newrevid;

		const revision1 = await alice.edit( title, { text: 'Revision 1', summary: 'revision 1' } );
		revisions.rev1 = revision1.newrevid;

		const revision2 = await alice.edit( title, { text: 'Revision 2', summary: 'revision 2' } );
		revisions.rev2 = revision2.newrevid;
	} );

	it( 'should get revision history', async () => {
		const results = await alice.prop( 'revisions', title, { rvlimit: 5, rvprop: 'ids|user|comment' } );

		assert.sameDeepMembers( results[ title ].revisions, [
			{ revid: revisions.rev2, parentid: revisions.rev1, user: alice.username, comment: 'revision 2' },
			{ revid: revisions.rev1, parentid: revisions.create, user: alice.username, comment: 'revision 1' },
			{ revid: revisions.create, parentid: 0, user: alice.username, comment: 'create revision page' }
		] );
	} );
} );
