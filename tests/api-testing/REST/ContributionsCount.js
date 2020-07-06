'use strict';
const { REST, assert, action, utils, clientFactory } = require( 'api-testing' );

describe( 'GET /me/contributions/count', () => {
	const basePath = 'rest.php/coredev/v0';
	const arnoldsEdits = [];
	let arnoldAction;
	let samAction;
	let arnold;

	before( async () => {
		// Sam will be the same Sam for all tests, even in other files
		samAction = await action.user( 'Sam', [ 'suppress' ] );

		// Arnold will be a different Arnold every time
		arnoldAction = await action.getAnon();
		await arnoldAction.account( 'Arnold_' );
		arnold = clientFactory.getRESTClient( basePath, arnoldAction );

		let page = utils.title( 'UserContribution_' );

		// bob makes 1 edit
		const bobAction = await action.bob();
		await bobAction.edit( page, [ { text: 'Bob revision 1', summary: 'Bob made revision 1' } ] );

		for ( let i = 1; i <= 2; i++ ) {
			const revData = await arnoldAction.edit(
				page,
				{ text: `Arnold's revision ${i}`, summary: `Arnold made revision ${i}` }
			);
			await utils.sleep();
			arnoldsEdits[ i ] = revData;
			page = utils.title( 'UserContribution_' );
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
		// TBD
	} );

} );
