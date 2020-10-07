'use strict';

const { assert, action, utils } = require( 'api-testing' );

describe( 'Categories', function testCategories() {
	const pageX = utils.title( 'CatTest_X_' );
	const pageY = utils.title( 'Talk:CatTest_Y_' );

	const catA = utils.title( 'Cat_A_' );
	const catB = utils.title( 'Cat_B_' );

	const titleA = `Category:${catA}`;
	const titleB = `Category:${catB}`;

	let alice;

	before( async () => {
		alice = await action.alice();

		await alice.edit( pageX, { text: `Foo [[${titleA}]]` } );
		await alice.edit( pageY, { text: `Bar [[${titleA}]] [[${titleB}]]` } );
	} );

	const listCategories = async ( page ) => {
		const result = await alice.prop(
			'categories',
			page
		);

		return result[ page ].categories.map( ( p ) => utils.dbkey( p.title ) );
	};

	const listMembers = async ( cat, param = {} ) => {
		const result = await alice.list(
			'categorymembers',
			{ cmtitle: cat, ...param }
		);

		return result.map( ( p ) => utils.dbkey( p.title ) );
	};

	it( 'can be added using wikitext syntax', async () => {
		const categoriesOfX = await listCategories( pageX );
		const categoriesOfY = await listCategories( pageY );

		assert.sameMembers( categoriesOfX, [ titleA ] );
		assert.sameMembers( categoriesOfY, [ titleA, titleB ] );
	} );

	it( 'can be listed', async () => {
		const membersOfA = await listMembers( titleA );
		const membersOfB = await listMembers( titleB );

		assert.sameMembers( membersOfA, [ pageX, pageY ] );
		assert.sameMembers( membersOfB, [ pageY ] );
	} );

	it( 'can be filtered by namespace', async () => {
		const membersOfA = await listMembers( titleA, { cmnamespace: 0 } );

		assert.sameMembers( membersOfA, [ pageX ] );
	} );

	it( 'are updated on edit', async () => {
		await alice.edit( pageX, { text: `Foo [[${titleA}]] [[${titleB}]]` } );
		await alice.edit( pageY, { text: `Bar [[${titleB}]]` } );

		const categoriesOfX = await listCategories( pageX );
		const categoriesOfY = await listCategories( pageY );

		assert.sameMembers( categoriesOfX, [ titleA, titleB ] );
		assert.sameMembers( categoriesOfY, [ titleB ] );

		const membersOfA = await listMembers( titleA );
		const membersOfB = await listMembers( titleB );

		assert.sameMembers( membersOfA, [ pageX ] );
		assert.sameMembers( membersOfB, [ pageX, pageY ] );
	} );

	it( 'can be nested', async () => {
		await alice.edit( titleB, { text: `Crud [[${titleA}]]` } );

		const membersOfA = await listMembers( titleA, { cmtype: 'subcat' } );

		assert.sameMembers( membersOfA, [ titleB ] );
	} );
} );
