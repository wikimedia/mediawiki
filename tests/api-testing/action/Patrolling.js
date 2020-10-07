'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The patrol action', function testEditPatrolling() {
	let alice, mindy, edit, rc;
	const pageTitle = utils.title( 'Patroll_' );

	before( async () => {
		[ alice, mindy ] = await Promise.all( [
			action.alice(),
			action.mindy()
		] );
	} );

	it( 'doesn\'t allow a users to patrol their own edit', async () => {
		edit = await alice.edit( pageTitle, { text: 'One', summary: 'first' } );

		const error = await alice.actionError(
			'patrol',
			{
				title: pageTitle,
				revid: edit.newrevid,
				token: await alice.token( 'patrol' )
			},
			'POST'
		);
		assert.equal( error.code, 'permissiondenied' );

		rc = await mindy.getChangeEntry(
			{
				rctitle: pageTitle,
				rcprop: 'ids|flags|patrolled'
			}
		);

		assert.equal( rc.type, 'new' );
		assert.notExists( rc.autopatrolled );
		assert.notExists( rc.patrolled );
		assert.exists( rc.unpatrolled );
	} );

	it( 'allows sysops to patrol an edit', async () => {
		const result = await mindy.action(
			'patrol',
			{
				title: pageTitle,
				revid: edit.newrevid,
				token: await mindy.token( 'patrol' )
			},
			'POST'
		);
		assert.equal( result.patrol.rcid, rc.rcid );

		rc = ( await mindy.getChangeEntry(
			{
				rctitle: pageTitle,
				rcprop: 'ids|flags|patrolled'
			}
		) );

		assert.equal( rc.type, 'new' );
		assert.exists( rc.patrolled );
		assert.notExists( rc.unpatrolled );
	} );

	it( 'doesn\'t allow regular users to see the patrol flags', async () => {
		const error = await alice.actionError(
			'query',
			{
				list: 'recentchanges',
				rctitle: pageTitle,
				rcprop: 'ids|flags|patrolled'
			}
		);

		assert.equal( error.code, 'permissiondenied' );
	} );
} );
