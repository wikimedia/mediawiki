'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The watchlist', function testWatch() {
	let alice;
	const walter = action.getAnon();
	const title = utils.title( 'Watch_' );
	const edits = {};

	before( async () => {
		[ , alice ] = await Promise.all( [
			walter.account( 'Walter_' ),
			action.alice()
		] );
	} );

	it( 'can have items added by an edit', async () => {
		edits.walter1 = await walter.edit( title, { watchlist: 'watch' } );
		const list = await walter.list( 'watchlist', {
			wltype: 'edit|new',
			wlprop: 'ids|title|flags|user|comment|timestamp'
		} );

		assert.sameTitle( list[ 0 ].title, title );
		assert.equal( list[ 0 ].type, 'new' );
		assert.equal( list[ 0 ].revid, edits.walter1.newrevid );
		assert.equal( list[ 0 ].user, edits.walter1.param_user );
		assert.equal( list[ 0 ].comment, edits.walter1.param_summary );
		assert.equal( list[ 0 ].timestamp, edits.walter1.newtimestamp );
	} );

	it( 'can have items removed using the unwatch flag', async () => {
		await walter.action( 'watch', {
			unwatch: true,
			title: title,
			token: await walter.token( 'watch' )
		}, 'POST' );

		const list = await walter.list( 'watchlist', {
			wltype: 'edit|new',
			wlprop: 'ids|title|flags|user|comment|timestamp'
		} );
		assert.empty( list );
	} );

	it( 'can have items added using the watch action', async () => {
		await walter.action( 'watch', {
			title: title,
			token: await walter.token( 'watch' )
		}, 'POST' );

		edits.alice2 = await alice.edit( title, {} );

		// FIXME: this is needed to force a sync with the replica database.
		//  This trick only works with a single replica. We need a better
		//  way to ensure a sync! Not to mention waiting for the job queue...
		await alice.getRevision( title );

		const list = await walter.list( 'watchlist', {
			wltype: 'edit|new',
			wlprop: 'ids|title|flags|user|comment|timestamp'
		} );

		assert.equal( list[ 0 ].type, 'edit' );
		assert.equal( list[ 0 ].revid, edits.alice2.newrevid );
		assert.equal( list[ 0 ].user, edits.alice2.param_user );
		assert.equal( list[ 0 ].comment, edits.alice2.param_summary );
		assert.equal( list[ 0 ].timestamp, edits.alice2.newtimestamp );
	} );
} );
