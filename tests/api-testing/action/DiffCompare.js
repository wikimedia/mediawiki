'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Diff Compare with Variables', function () {
	let wikiuser;
	const title = utils.title( 'DiffCompare' );
	const variables = {};

	before( async () => {
		wikiuser = await action.alice();
	} );

	it( 'should edit a page', async () => {
		const editPage = await wikiuser.edit( title, { text: 'Counting: \n* One' } );

		variables.revision1 = editPage.newrevid;
	} );

	it( 'should edit a page, revision 2', async () => {
		await wikiuser.edit( title, { text: 'Counting: \n* One \n* Two' } );
	} );

	it( 'should edit a page, revision 3', async () => {
		const editPage = await wikiuser.edit( title, { text: 'Counting: \n* One \n* Two \n* Three' } );

		variables.revision3 = editPage.newrevid;
	} );

	it( 'should edit a page, revision 4', async () => {
		const editPage = await wikiuser.edit( title, { text: 'Counting: \n* One \n* Two' } );

		variables.revision4 = editPage.newrevid;
	} );

	it( 'should compare revisions 1 and 4', async () => {
		const compareRevisions = await wikiuser.action( 'compare', { fromrev: variables.revision1, torev: variables.revision4 } );

		assert.match( compareRevisions.compare[ '*' ], /<td class=.diff-addedline.>.*\* Two/ );
	} );

	it( 'should compare revisions 3 and 4', async () => {
		const compareRevisions = await wikiuser.action( 'compare', { fromrev: variables.revision3, torev: variables.revision4 } );

		assert.match( compareRevisions.compare[ '*' ], /<td class=.diff-deletedline.>.*\* Three/ );
	} );
} );
