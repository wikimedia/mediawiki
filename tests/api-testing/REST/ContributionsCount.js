'use strict';
const { REST, assert, action, utils, clientFactory } = require( 'api-testing' );

describe( 'GET /me/contributions/count', () => {
	const basePath = 'rest.php/coredev/v0';
	let arnold;
	let arnoldAction;
	let samAction;

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
		await bobAction.edit( evenEditsPage, [ { summary: 'Bob made revision 1' } ] );

		// arnold makes 2 edits
		let page;
		for ( let i = 1; i <= 2; i++ ) {
			const oddEdit = i % 2;
			const tags = oddEdit ? 'api-test' : null;
			page = oddEdit ? oddEditsPage : evenEditsPage;

			await arnoldAction.edit( page, { tags } );
			await utils.sleep();
		}
	} );

	it( 'Returns status 401 for anon', async () => {
		const anon = new REST( basePath );
		const { status, body } = await anon.get( '/me/contributions/count' );
		assert.equal( status, 401 );
		assert.nestedProperty( body, 'messageTranslations' );
	} );

	it( 'Returns status OK', async () => {
		const response = await arnold.get( '/me/contributions/count' );
		assert.equal( response.status, 200 );
	} );

	it( 'Returns the number of arnold\'s edits', async () => {
		const { status, body } = await arnold.get( '/me/contributions/count' );
		assert.equal( status, 200 );

		// assert body has property count with the correct value
		assert.propertyVal( body, 'count', 2 );
	} );

	it( 'Does not return suppressed revisions when requesting user does not have appropriate permissions', async () => {
		const { body: preDeleteBody } = await arnold.get( '/me/contributions/count' );
		assert.propertyVal( preDeleteBody, 'count', 2 );

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
		const { body: arnoldGetBody } = await arnold.get( '/me/contributions/count' );
		assert.propertyVal( arnoldGetBody, 'count', 3 );

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
		const { body: arnoldGetBody2 } = await arnold.get( '/me/contributions/count' );
		assert.propertyVal( arnoldGetBody2, 'count', 4 );
	} );

	it( 'Returns the number of arnold\'s edits filtered by tag', async () => {
		const { status, body } = await arnold.get( '/me/contributions/count', { tag: 'api-test' } );
		assert.equal( status, 200 );

		// assert body has property count with the correct number of tagged edits
		assert.propertyVal( body, 'count', 1 );
	} );

} );
