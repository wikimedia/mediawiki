const { action, assert, utils } = require( 'api-testing' );

describe( 'The rollback action', function testEditRollback() {
	let alice, bob, mindy;

	before( async () => {
		[ alice, bob, mindy ] = await Promise.all( [
			action.alice(),
			action.bob(),
			action.mindy()
		] );
	} );

	it( 'rolls back consecutive edits', async () => {
		const title = utils.title( 'Rollback_' );
		const edits = {};

		edits.alice1 = await alice.edit( title, { text: 'One', summary: 'first' } );
		edits.bob2 = await bob.edit( title, { text: 'Two', summary: 'second' } );
		edits.alice3 = await alice.edit( title, { text: 'Three', summary: 'third' } );
		edits.bob4 = await bob.edit( title, { text: 'Four', summary: 'fourth' } );
		edits.bob5 = await bob.edit( title, { text: 'Five', summary: 'fifth' } );

		const result = await mindy.action( 'rollback', {
			title,
			user: bob.username,
			summary: 'revert vandalism',
			token: await mindy.token( 'rollback' )
		}, 'POST' );

		assert.sameTitle( result.rollback.title, title );
		assert.equal( result.rollback.old_revid, edits.bob5.newrevid );
		assert.equal( result.rollback.last_revid, edits.alice3.newrevid );

		const rev = await mindy.getRevision( title );
		assert.equal( rev.revid, result.rollback.revid );
		assert.equal( rev.slots.main[ '*' ], 'Three' );
		assert.equal( rev.user, mindy.username );
	} );

	it( 'doesn\'t roll back edits by another user', async () => {
		const title = utils.title( 'Rollback_' );

		await alice.edit( title, { text: 'One', summary: 'first' } );
		await bob.edit( title, { text: 'Two', summary: 'second' } );
		await alice.edit( title, { text: 'Three', summary: 'third' } );

		const error = await mindy.actionError( 'rollback', {
			title,
			user: bob.username,
			summary: 'revert vandalism',
			token: await mindy.token( 'rollback' )
		}, 'POST' );

		assert.equal( error.code, 'alreadyrolled' );
	} );

	it( 'doesn\'t allow a regular user to roll back edits', async () => {
		const title = utils.title( 'Rollback_' );

		await alice.edit( title, { text: 'One', summary: 'first' } );
		await bob.edit( title, { text: 'Two', summary: 'second' } );

		const error = await alice.actionError( 'rollback', {
			title,
			user: bob.username,
			summary: 'revert vandalism',
			token: await alice.token( 'rollback' )
		}, 'POST' );

		assert.equal( error.code, 'permissiondenied' );
	} );
} );
