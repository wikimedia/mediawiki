'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The usercontribs list query', function testUserContribsListQuery() {
	const titleX = utils.title( 'Contribs_' );
	const titleY = utils.title( 'Talk:Contribs_' );

	const fiona = action.getAnon();
	const franky = action.getAnon();

	const edits = {};

	const assertContribs = ( expected, contribs ) => {
		assert.equal( contribs.length, expected.length, 'number of revisions' );

		// Establish chronological order
		contribs.sort( ( a, b ) => (
			( a.timestamp > b.timestamp ) ? 1 :
				( a.timestamp < b.timestamp ) ? -1 :
					a.revid - b.revid ) );

		for ( let i = 0; i < expected.length; i++ ) {
			assert.equal( contribs[ i ].comment, expected[ i ].param_summary, 'summary' );
			assert.equal( contribs[ i ].revid, expected[ i ].newrevid, 'revid' );
			assert.equal( contribs[ i ].user, expected[ i ].param_user, 'user' );
		}
	};

	before( async () => {
		await Promise.all( [
			fiona.account( 'Fiona_' ),
			franky.account( 'Franky_' )
		] );

		// Fiona edits X, second edit is minor, first edit is creation
		edits.fiona1 = await fiona.edit( titleX, { text: 'Fiona X ONE', summary: 'fiona x one' } );
		edits.fiona2 = await fiona.edit( titleX, { text: 'Fiona X TWO', summary: 'fiona x two', minor: true } );

		// Franky edits Y, second edit is minor, first edit is creation
		edits.franky1 = await franky.edit( titleY, { text: 'Franky Y ONE', summary: 'franky y one' } );
		edits.franky2 = await franky.edit( titleY, { text: 'Franky Y TWO', summary: 'franky y two', minor: true } );

		// Fiona edits Y, Franky edits X
		edits.fiona3 = await fiona.edit( titleY, { text: 'Fiona Y TREE', summary: 'fiona y three' } );
		edits.franky3 = await fiona.edit( titleX, { text: 'Franky X TREE', summary: 'franky x three' } );
	} );

	it( 'can lists all contributions for a given set of users', async () => {
		const contribs = await fiona.list( 'usercontribs', {
			ucuser: `${fiona.username}|${franky.username}`,
			ucprop: 'ids|user|comment|timestamp'
		} );

		const expected = [
			edits.fiona1,
			edits.fiona2,
			edits.franky1,
			edits.franky2,
			edits.fiona3,
			edits.franky3
		];

		assertContribs( expected, contribs );
	} );

	it( 'can filter by namespace', async () => {
		const contribs = await fiona.list( 'usercontribs', {
			ucuser: `${fiona.username}|${franky.username}`,
			ucprop: 'ids|user|comment|timestamp',
			ucnamespace: 1 // NS_TALK = 1
		} );

		const expected = [
			edits.franky1,
			edits.franky2,
			edits.fiona3
		];

		assertContribs( expected, contribs );
	} );

	it( 'can list top only', async () => {
		const contribs = await fiona.list( 'usercontribs', {
			ucuser: `${fiona.username}|${franky.username}`,
			ucprop: 'ids|user|comment|timestamp',
			uctoponly: true
		} );

		const expected = [
			edits.fiona3,
			edits.franky3
		];

		assertContribs( expected, contribs );
	} );

	it( 'can list creation only', async () => {
		const contribs = await fiona.list( 'usercontribs', {
			ucuser: `${fiona.username}|${franky.username}`,
			ucprop: 'ids|user|comment|timestamp',
			ucshow: 'new'
		} );

		const expected = [
			edits.fiona1,
			edits.franky1
		];

		assertContribs( expected, contribs );
	} );
} );
