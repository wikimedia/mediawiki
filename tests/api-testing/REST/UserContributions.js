'use strict';
const { REST, assert, action, utils, clientFactory } = require( 'api-testing' );

describe( 'GET /me/contributions', () => {
	const basePath = 'rest.php/coredev/v0';
	const anon = new REST( basePath );
	const limit = 2;
	const arnoldsRevisions = [];
	const arnoldsEdits = [];
	const arnoldsTags = [];
	let arnold;
	let arnoldAction;
	let samAction;
	const revisionText = { 0: '12345678', 1: 'A', 2: 'ABCD', 3: 'AB', 4: 'ABCDEFGH', 5: 'A' };
	const expectedRevisionDeltas = { 1: 1, 2: -4, 3: 1, 4: 4, 5: -1 };

	before( async () => {
		// Sam will be the same Sam for all tests, even in other files
		samAction = await action.user( 'Sam', [ 'suppress' ] );

		// Arnold will be a different Arnold every time
		arnoldAction = await action.getAnon();
		await arnoldAction.account( 'Arnold_' );
		arnold = clientFactory.getRESTClient( basePath, arnoldAction );

		const oddEditsPage = utils.title( 'UserContribution_' );
		const evenEditsPage = utils.title( 'UserContribution_' );

		// Create a tag.
		await action.makeTag( 'api-test' );

		// bob makes 1 edit
		const bobAction = await action.bob();
		await bobAction.edit( evenEditsPage, [ {
			text: revisionText[ 0 ],
			summary: 'Bob made revision 1'
		} ] );

		// arnold makes 5 edits
		let page;
		for ( let i = 1; i <= 5; i++ ) {
			const oddEdit = i % 2;
			const tags = oddEdit ? 'api-test' : null;
			page = oddEdit ? oddEditsPage : evenEditsPage;
			arnoldsTags[ i ] = tags ? tags.split( '|' ) : [];

			const revData = await arnoldAction.edit( page, { text: revisionText[ i ], tags } );
			await utils.sleep();
			arnoldsRevisions[ revData.newrevid ] = revData;
			arnoldsEdits[ i ] = revData;
		}
	} );

	it( 'Returns status 401 for anon', async () => {
		const { status, body } = await anon.get( '/me/contributions' );
		assert.equal( status, 401 );
		assert.nestedProperty( body, 'messageTranslations' );
	} );

	it( 'Returns status OK', async () => {
		const response = await arnold.get( '/me/contributions' );
		assert.equal( response.status, 200 );
	} );

	it( 'Returns a list of arnold\'s edits', async () => {
		const { status, body } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.equal( status, 200 );

		// assert body has property revisions
		assert.property( body, 'revisions' );
		const { revisions } = body;

		// assert body.revisions is array
		assert.isArray( revisions );

		// assert body.revisions length is limit
		assert.lengthOf( revisions, limit );

		const lastRevision = arnoldsRevisions[ arnoldsRevisions.length - 1 ];

		// assert body.revisions object schema is correct
		assert.hasAllDeepKeys( revisions[ 0 ], [
			'id', 'comment', 'timestamp', 'delta', 'size', 'page', 'tags'
		] );

		assert.equal( revisions[ 0 ].page.key, utils.dbkey( lastRevision.title ) );
		assert.equal( revisions[ 0 ].page.title, lastRevision.title );
		assert.equal( revisions[ 0 ].comment, lastRevision.param_summary );
		assert.equal( revisions[ 0 ].timestamp, lastRevision.newtimestamp );
		assert.equal( revisions[ 0 ].size, revisionText[ 5 ].length );
		assert.equal( revisions[ 0 ].delta, expectedRevisionDeltas[ 5 ] );
		assert.isOk( Date.parse( revisions[ 0 ].timestamp ) );
		assert.isNotOk( Date.parse( 'xyz' ) );
		assert.isArray( revisions[ 0 ].tags );

		assert.isAbove( Date.parse( revisions[ 0 ].timestamp ),
			Date.parse( revisions[ 1 ].timestamp ) );

		assert.equal( revisions[ 1 ].size, revisionText[ 4 ].length );
		assert.equal( revisions[ 1 ].delta, expectedRevisionDeltas[ 4 ] );

		// assert body.revisions contains edits only by one user
		revisions.forEach( ( rev ) => {
			assert.property( arnoldsRevisions, rev.id );
		} );
	} );

	it( 'Returns a list filtered by tag', async () => {
		const taggedRevisions = [ arnoldsEdits[ 1 ], arnoldsEdits[ 3 ], arnoldsEdits[ 5 ] ];

		const { status, body } = await arnold.get( '/me/contributions?tag=api-test' );
		assert.equal( status, 200 );

		// assert body has property revisions
		assert.property( body, 'revisions' );
		const { revisions } = body;

		// assert body.revisions length
		assert.lengthOf( revisions, taggedRevisions.length );

		// assert that there are no more revisions found
		assert.propertyVal( body, 'older', null );

		// assert body.revisions has the correct content
		assert.equal( revisions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( revisions[ 1 ].id, arnoldsEdits[ 3 ].newrevid );
		assert.equal( revisions[ 2 ].id, arnoldsEdits[ 1 ].newrevid );
	} );

	it( 'Can fetch a chain of segments following the "older" field in the response', async () => {
		// get latest segment
		const { body: latestSegment } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.property( latestSegment, 'older' );
		assert.property( latestSegment, 'revisions' );
		assert.isArray( latestSegment.revisions );
		assert.lengthOf( latestSegment.revisions, 2 );

		// assert body.revisions has the correct content
		assert.equal( latestSegment.revisions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( latestSegment.revisions[ 1 ].id, arnoldsEdits[ 4 ].newrevid );

		assert.deepEqual( latestSegment.revisions[ 0 ].tags, arnoldsTags[ 5 ] );
		assert.deepEqual( latestSegment.revisions[ 1 ].tags, arnoldsTags[ 4 ] );

		// get older segment, using full url
		const req = clientFactory.getHttpClient( arnold );

		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'older' );
		assert.property( olderSegment, 'revisions' );
		assert.isArray( olderSegment.revisions );
		assert.lengthOf( olderSegment.revisions, 2 );

		// assert body.revisions has the correct content
		assert.equal( olderSegment.revisions[ 0 ].id, arnoldsEdits[ 3 ].newrevid );
		assert.equal( olderSegment.revisions[ 1 ].id, arnoldsEdits[ 2 ].newrevid );

		assert.deepEqual( olderSegment.revisions[ 0 ].tags, arnoldsTags[ 3 ] );
		assert.deepEqual( olderSegment.revisions[ 1 ].tags, arnoldsTags[ 2 ] );

		// get the next older segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.propertyVal( finalSegment, 'older', null );
		assert.property( finalSegment, 'revisions' );
		assert.isArray( finalSegment.revisions );
		assert.lengthOf( finalSegment.revisions, 1 );

		// assert body.revisions has the correct content
		assert.equal( finalSegment.revisions[ 0 ].id, arnoldsEdits[ 1 ].newrevid );

		assert.deepEqual( finalSegment.revisions[ 0 ].tags, arnoldsTags[ 1 ] );
	} );

	it( 'Returns 400 if segment size is out of bounds', async () => {
		const { status: minLimitStatus } = await arnold.get( '/me/contributions?limit=0' );
		assert.equal( minLimitStatus, 400 );

		const { status: maxLimitStatus } = await arnold.get( '/me/contributions?limit=30' );
		assert.equal( maxLimitStatus, 400 );
	} );

	it( 'Can fetch a chain of segments following the "newer" field in the response', async () => {
		const req = clientFactory.getHttpClient( arnold );

		// get latest segment
		const { body: latestSegment } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.property( latestSegment, 'newer' );

		// get next older segment
		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'newer' );

		// get the final segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.property( finalSegment, 'newer' );

		// Follow the chain of "newer" links back to the latest segment
		const { body: olderSegment2 } = await req.get( finalSegment.newer );
		assert.deepEqual( olderSegment, olderSegment2 );

		const { body: latestSegment2 } = await req.get( olderSegment.newer );
		assert.deepEqual( latestSegment, latestSegment2 );
	} );

	it( 'Returns a valid link to the latest segment', async () => {
		const req = clientFactory.getHttpClient( arnold );

		// get latest segment
		const { body: latestSegment } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.property( latestSegment, 'latest' );

		// get next older segment
		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'latest' );

		// get the final segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.property( finalSegment, 'latest' );

		// Follow all the "newer" links
		const { body: latestSegment2 } = await req.get( latestSegment.latest );
		assert.deepEqual( latestSegment, latestSegment2 );

		assert.deepEqual( latestSegment.latest, finalSegment.latest );
		assert.deepEqual( latestSegment.latest, olderSegment.latest );

	} );

	it( 'Segment links preserve tag filtering', async () => {
		const req = clientFactory.getHttpClient( arnold );

		// get latest segment
		const { body: latestSegment } = await arnold.get( '/me/contributions?limit=2&tag=api-test' );

		// assert body.revisions has the latest revisions that have the "api-test" tag (odd edits)
		assert.equal( latestSegment.revisions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( latestSegment.revisions[ 1 ].id, arnoldsEdits[ 3 ].newrevid );

		// get the final segment
		const { body: finalSegment } = await req.get( latestSegment.older );

		// assert body.revisions has the oldest revisions that have the "api-test" tag (odd edits)
		assert.equal( finalSegment.revisions[ 0 ].id, arnoldsEdits[ 1 ].newrevid );

		const { body: latestSegment2 } = await req.get( finalSegment.newer );
		assert.deepEqual( latestSegment, latestSegment2 );

		const { body: latestSegment3 } = await req.get( latestSegment.latest );
		assert.deepEqual( latestSegment, latestSegment3 );

		// assert that the "latest" links also preserve the "tag" parameter
		assert.deepEqual( finalSegment.latest, latestSegment.latest );
	} );

	it( 'Does not return suppressed revisions when requesting user does not have appropriate permissions', async () => {
		const { body: preDeleteBody } = await arnold.get( '/me/contributions?limit=10' );
		assert.lengthOf( preDeleteBody.revisions, 5 );

		const pageToDelete = utils.title( 'UserContribution_' );
		const editToDelete = await arnoldAction.edit( pageToDelete, [ { text: 'Delete me 1' } ] );
		await arnoldAction.edit( pageToDelete, [ { text: 'Delete me 2' } ] );

		await samAction.action( 'revisiondelete',
			{
				type: 'revision',
				token: await samAction.token(),
				target: editToDelete.title,
				hide: 'content|comment|user',
				ids: editToDelete.newrevid
			},
			'POST'
		);

		// Users without appropriate permissions cannot see suppressed revisions (even their own)
		const { body: arnoldGetBody } = await arnold.get( '/me/contributions?limit=10' );
		assert.lengthOf( arnoldGetBody.revisions, 6 );

		await samAction.action( 'revisiondelete',
			{
				type: 'revision',
				token: await samAction.token(),
				target: editToDelete.title,
				show: 'content|comment|user',
				ids: editToDelete.newrevid
			},
			'POST'
		);

		// Users with appropriate permissions can see suppressed revisions
		const { body: arnoldGetBody2 } = await arnold.get( '/me/contributions?limit=10' );
		assert.lengthOf( arnoldGetBody2.revisions, 7 );
	} );

} );
