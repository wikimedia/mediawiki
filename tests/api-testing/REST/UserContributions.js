'use strict';

const { REST, assert, action, clientFactory, utils } = require( 'api-testing' );

describe( 'GET /me/contributions', () => {
	const basePath = 'rest.php/coredev/v0';
	const anon = new REST( basePath );
	const limit = 2;
	const alicesEdits = [];
	let alice;

	before( async () => {
		const aliceAction = await action.alice();
		alice = clientFactory.getRESTClient( basePath, aliceAction );

		let page = utils.title( 'UserContribution_' );

		// bob makes 1 edit
		const bobAction = await action.bob();
		await bobAction.edit( page, [ { text: 'Bob revision 1', summary: 'Bob made revision 1' } ] );

		// alice makes 4 edits
		for ( let i = 1; i <= 2; i++ ) {
			const revData = await aliceAction.edit( page, { text: `Alice revision ${i}`, summary: `Alice made revision ${i}` } );
			await utils.sleep();
			alicesEdits[ revData.newrevid ] = revData;
			page = utils.title( 'UserContribution_' );
		}

		// ensure current user has more than one edit
		// ensure user with revisions for 3 segments + a buffer
		// add edits by a second user
	} );

	it( 'Returns status 401 for anon', async () => {
		const { status, body } = await anon.get( '/me/contributions' );
		assert.equal( status, 401 );
		assert.nestedProperty( body, 'messageTranslations' );
	} );

	it( 'Returns status OK', async () => {
		const response = await alice.get( '/me/contributions' );
		assert.equal( response.status, 200 );
	} );

	it( 'Returns a list of page revisions', async () => {
		const { status, body } = await alice.get( `/me/contributions?limit=${limit}` );
		assert.equal( status, 200 );

		// assert body has property revisions
		assert.property( body, 'revisions' );
		const { revisions } = body;

		// assert body.revisions is array
		assert.isArray( revisions );

		// assert body.revisions length is limit
		assert.lengthOf( revisions, limit );

		const lastRevision = alicesEdits[ alicesEdits.length - 1 ];

		// assert body.revisions object schema is correct
		assert.hasAllDeepKeys( revisions[ 0 ], [
			'id', 'comment', 'timestamp', 'size', 'page'
		] );

		assert.equal( revisions[ 0 ].page.key, utils.dbkey( lastRevision.title ) );
		assert.equal( revisions[ 0 ].page.title, lastRevision.title );
		assert.equal( revisions[ 0 ].comment, lastRevision.param_summary );
		assert.equal( revisions[ 0 ].timestamp, lastRevision.newtimestamp );
		assert.isOk( Date.parse( revisions[ 0 ].timestamp ) );
		assert.isNotOk( Date.parse( 'xyz' ) );

		// assert body.revisions contains edits only by one user
		revisions.forEach( ( rev ) => {
			assert.property( alicesEdits, rev.id );
		} );
	} );

} );
